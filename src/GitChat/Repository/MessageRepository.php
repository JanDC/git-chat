<?php

namespace GitChat\Repository;

use GitChat\Model\Message;
use Gitonomy\Git\Commit;

class MessageRepository extends GitRepository
{

    /**
     * @param null|integer $limit
     * @param bool $as_array
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

    /**
     * @param string $message
     * @param bool $branch
     */
    public function pushMessage($message, $branch = false)
    {
        if (empty($message)) {
            return;
        }
        if ($this->hasUserCredentials()) {
            $this->commitAsUser($message, $branch, $this->getMessages(null, true));
        } else {
            $this->getGitLibRepository()->run('commit', ['--all', '--message=' . $message . '']);
        }
    }


    private function parseCommitToMessage(Commit $commit)
    {
        return new Message($commit->getAuthorName(), $commit->getAuthorDate(), $commit->getMessage());
    }
}