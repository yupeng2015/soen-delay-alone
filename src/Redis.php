<?php


namespace Soen\Delay\Alone;


class Redis
{
    /**
     * @var \Redis 
     */
    public $driver;
    function __construct( $config = [])
    {
        if (!empty($config)) {
            $this->config = $config;
        }
        $this->driver = new \Redis();
        $host = $this->config['host'];
        $port = $this->config['port'];
        $timeout = $this->config['timeout'];
        $database = $this->config['database'];
        $password= $this->config['password'];
        $hasConn = $this->driver->connect($host, $port, $timeout);
        if(!$hasConn){
            throw new \RuntimeException('链接异常');
        }
        // 假设密码是字符串 0 也能通过这个校验
        if ('' != (string)$password) {
            $this->driver->auth($password);
        }
        $this->driver->select($database);
    }
    
    function getDriver(){
        return $this->driver;
    }
}