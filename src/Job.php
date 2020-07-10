<?php


namespace Soen\Delay\Alone;


class Job
{
    public $id;
    public $topic;
    protected $delay;
    protected $ttr;
    protected $body;
    public function __construct($id, $body, $delay, $topic, $ttr)
    {
        $this->setId($id);
        $this->setBody($body);
        $this->setDelay($delay);
        $this->setTopic($topic);
        $this->setTtr($ttr);
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }
    /**
     * @return mixed
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }
    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $ttr
     */
    public function setTtr($ttr)
    {
        $this->ttr = $ttr;
    }
    /**
     * @return mixed
     */
    public function getTtr()
    {
        return $this->ttr;
    }

}