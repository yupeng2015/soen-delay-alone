<?php


namespace Soen\Delay\Alone\Client;


use Soen\Delay\Alone\Config;
use Soen\Delay\Alone\Job;

class Client
{
    /**
     * @var \Redis
     */
    public $driver;
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param $id
     * @param $topic
     * @param $body
     * @param $delayTime
     * @param int $readyMaxLifetime
     * @return bool
     */
    public function push ($id, $topic, array $body, $delayTime, $readyMaxLifetime = 604800) {
        $key = Config::PREFIX_JOB_POOL . $id;
        $this->driver->multi();
        $this->driver->hMset($key, [
                'id'    => $id,
                'delay'    => $delayTime,
                'topic' =>  Config::PREFIX_READY_QUEUE . $topic,
                'ttr'   =>  100,
                'body'  =>  json_encode($body, JSON_UNESCAPED_UNICODE)
            ]);
        $this->driver->expire($key, $delayTime + $readyMaxLifetime);
        $this->driver->zAdd(Config::JOB_BUCKETS, time() + $delayTime, $id);
        $result = $this->driver->exec();
        foreach ($result as $status) {
            if (!$status) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $topic
     * @param int $timeout
     * @return bool|Job
     */
    public function bPop ($topic, $timeout = 3600) {
        /* brPop方式读取 */
//       $result = $this->driver->brPop([Config::PREFIX_READY_QUEUE . $topic], $timeout);
        $jobId = $this->driver->rPop(Config::PREFIX_READY_QUEUE . $topic);
        if(empty($jobId)){
            return null;
        }
        $jobDetail= $this->driver->hGetAll(Config::PREFIX_JOB_POOL . $jobId);
        if (!$jobDetail || empty($jobDetail['topic']) || empty($jobDetail['body'])) {
            return null;
        }
        $this->driver->del(Config::PREFIX_JOB_POOL . $jobId);
        $data = new Job($jobDetail['id'], $jobDetail['body'], $jobDetail['delay'], $jobDetail['topic'], $jobDetail['ttr']);
        return $data;
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove ($id) {
        $this->driver->multi();
        $this->driver->zRem(Config::JOB_BUCKETS, $id);
        $this->driver->del(Config::PREFIX_JOB_POOL . $id);
        $result = $this->driver->exec();
        foreach ($result as $key=>$val){
            if(!$val){
                return false;
            }
        }
        return true;
    }

}