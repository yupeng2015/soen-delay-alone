使用composer安装：
composer require soen/delay-alone

#### 创建demo示例：
##### demo项目根目录创建public.php:
```php
<?php
require_once './vendor/autoload.php';
$redisConfig = [
    'host'          =>  'redis',
    'port'          =>  '6379',
    'database'      =>  '0',
    'password'      =>  '',
    'timeout'       =>  60
];
```
##### 根目录创建index.php（扫描数据服务端）:
```php
<?php
require_once 'public.php';
$redis = (new \Soen\Delay\Alone\Redis($redisConfig))->getDriver();
$deplayer = new \Soen\Delay\Alone\Polling(1, $redis);
$deplayer->run();
```
执行`php index.php`,会运行延迟队列的服务端，扫描到期的数据并移到延迟队列

##### 根目录创建push.php（提供者）*:
```php
<?php
require_once 'public.php';
$redis = (new \Soen\Delay\Alone\Redis($redisConfig))->getDriver();
$client = new \Soen\Delay\Alone\Client\Client($redis);

//生成随机数 body 数据
$strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
$name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
/**
 * 随机生成ID，固定topic1， 随机body数据，延迟队列延迟10秒更新
 */
$has = $client->push(rand(1,999),'topic1',['a'=>$name,'b'=>$name],10);
if($has){
    echo '数据提交成功'.PHP_EOL;
}else {
    echo '提交失败'.PHP_EOL;
}

```
cli内执行 `php push.php`, 就会提交一组等待处理的数据

##### 根目录创建pop.php（消费者）:
```php
<?php
require_once 'public.php';
$redis = (new \Soen\Delay\Alone\Redis($redisConfig))->getDriver();
$client = new Soen\Delay\Alone\Client\Client($redis);
$data = $client->bPop('topic1');
var_dump($data);
```
cli执行`php pop.php`,进行队列消费























