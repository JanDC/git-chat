<?php

namespace GitChat\Repository;


use Gitonomy\Git\Repository;

abstract class GitRepository
{
    /** @var Repository $git_repository */
    private $git_repository;

    public function __construct(array $configuration)
    {
        $directory = $configuration['repository']['directory'];
        $this->git_repository = new Repository($directory);
    }

    protected function getGitLibRepository(){
        return $this->git_repository;
    }


}