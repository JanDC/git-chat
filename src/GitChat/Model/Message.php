<?php

namespace GitChat\Model;

class Message
{
    /** @var  String */
    private $message;
    /** @var  \DateTime */
    private $time;
    /** @var  String */
    private $id;
    /** @var  String */
    private $username;

    public function __construct($username, \DateTime $time, $message)
    {
        $this->setUsername($username)->setTime($time)->setMessage($message);
    }

    public function toArray()
    {
        return ['message' => $this->getMessage(), 'time' => $this->getTime()->format('d/m/Y H:i:s'), 'username' => $this->getUsername()];
    }

    /**
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param String $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return Message
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return String
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param String $id
     * @return Message
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return String
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param String $username
     * @return Message
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }


}