<?php

namespace GitChat\Service;

use GitChat\Repository\MessageRepository;

class ChatService
{
    public function __construct(array $configuration)
    {
        $this->messageRepository = new MessageRepository($configuration);
    }

    public function getHistory($limit = null)
    {
        return $this->messageRepository->getMessages($limit,true);
    }

    public function addMessage($message)
    {
        $this->messageRepository->pushMessage($message);
    }

}