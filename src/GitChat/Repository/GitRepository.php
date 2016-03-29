<?php

namespace GitChat\Repository;


use Gitonomy\Git\Commit;
use Gitonomy\Git\Repository;
use Symfony\Component\Process\Process;

abstract class GitRepository
{
    /** @var Repository $git_repository */
    private $git_repository;

    /** @var  string */
    private $shell_user;

    /** @var  string */
    private $shell_password;

    public function __construct(array $configuration)
    {
        $directory = $configuration['repository']['directory'];
        $this->git_repository = new Repository($directory);

        if ($user = $configuration['repository']['shell_user']) {
            $this->setUserCredentials($user);
        }
    }

    protected function getGitLibRepository()
    {
        return $this->git_repository;
    }

    protected function hasUserCredentials()
    {
        return $this->shell_user && $this->shell_password;
    }

    /**
     * @param $message
     * @param bool $branch
     * @param array $previous_messages
     */
    protected function commitAsUser($message, $branch = false, $previous_messages = [])
    {

        $logdata = json_encode($previous_messages);
        $overwrite_log = "echo '$logdata' > log";

        $this->executeProcessAsUser($overwrite_log, $this->getGitLibRepository()->getWorkingDir());
        $this->executeProcessAsUser('git add . ', $this->getGitLibRepository()->getWorkingDir());

        if ($branch) {
            $this->switchToBranch($branch);
        }

        $commit_command = 'git commit -am"' . $message . '"';
        $this->executeProcessAsUser($commit_command, $this->git_repository->getWorkingDir());

        $this->pushAsUser();

    }

    protected function pushAsUser()
    {
        $push_command = 'git push';
        $this->executeProcessAsUser($push_command, $this->git_repository->getWorkingDir());
    }

    private function executeProcessAsUser($command, $workingDir)
    {
        if (!$this->hasUserCredentials()) {
            throw new \LogicException('This function can only be called if user credentials are supplied');
        }

        $switch_user_and_execute = "sudo -u $this->shell_user $command";
        /** @var Process $process */
        $process = new Process($switch_user_and_execute, $workingDir, [], $this->shell_password);
        $process->enableOutput()->start();
        while ($process->isRunning()) {

        }
        var_dump($process->getExitCodeText(), $process->getCommandLine());
    }

    private function setUserCredentials($user)
    {
        $this->shell_user = $user['username'];
        $this->shell_password = $user['password'];
    }

    protected function switchToBranch($branch)
    {
        $stash = "git stash";
        $change_branch = "git checkout $branch";
        $unstash = "git stash pop";

        $this->executeProcessAsUser($stash, $this->git_repository->getWorkingDir());
        $this->executeProcessAsUser($change_branch, $this->git_repository->getWorkingDir());
        $this->executeProcessAsUser($unstash, $this->git_repository->getWorkingDir());
    }


}