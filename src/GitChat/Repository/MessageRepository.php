<?php

namespace GitChat\Repository;


use GitChat\Model\Message;
use Gitonomy\Git\Commit;

class MessageRepository extends GitRepository
{

    /**
     * @param string $message
     */
    public function pushMessage($message)
    {
        if (empty($message)) {
            return;
        }
        unlink($this->getGitLibRepository()->getWorkingDir() . '/log');
        file_put_contents($this->getGitLibRepository()->getWorkingDir() . '/log', print_r($this->getGitLibRepository()->getLog()->getCommits(), true));
        $this->getGitLibRepository()->run('add', ['.']);
        $this->getGitLibRepository()->run('commit', ['--all', '--message=' . $message . '']);
        $this->getGitLibRepository()->run('push');
    }

    /**
     * @param null|integer $limit
     *
     * @return array
     */
    public function getMessages($limit = null, $as_array = false)
    {
        $log = $this->getGitLibRepository()->getLog(null, null, null, $limit);
        $commits = $log->getCommits();
        $messages = array_map(function (Commit $commit) {
            return $this->parseCommitToMessage($commit);
        }, $commits);
        usort($messages, function (Message $message_a, Message $message_b) {
            return $message_a->getTime() > $message_b->getTime();
        });

        if ($as_array) {
            return array_map(function (Message $message) {
                return $message->toArray();
            }, $messages);
        }

        return $messages;
    }


    private function parseCommitToMessage(Commit $commit)
    {
        return new Message($commit->getAuthorName(), $commit->getAuthorDate(), $commit->getMessage());
    }
}