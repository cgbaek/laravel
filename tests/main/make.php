<?php
//https://github.com/cxp1539/laravel-core-learn/tree/master/code
interface log
{
    public function write();
}

interface database
{
    public function connect();
}

class config
{
    public function dsn()
    {
        echo "dsn \r\n";
    }
}

class mysql implements database
{
    protected $config;
    public function __construct(config $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        $this->config->dsn();
        echo "connect mysql \r\n";
    }
}


// 文件记录日志
class FileLog implements Log
{
    public function write(){
        echo 'file log write...';
    }
}

// 数据库记录日志
class DatabaseLog implements Log
{
    public function write(){
        echo 'database log write...';
    }
}

class User
{
    protected $log;
    protected $database;

    public function __construct(DatabaseLog $log, mysql $database)
    {
        $this->log = $log;
        $this->database = $database;
    }
    public function login()
    {
        $this->database->connect();
        // 登录成功，记录登录日志
        echo 'login success...';
        $this->log->write();
    }
}

function make($concrete){
    $reflector = new ReflectionClass($concrete);
    $constructor = $reflector->getConstructor();
    // 如果没有构造函数，则直接创建对象
    if(is_null($constructor)) {
        return $reflector->newInstance();
    }else {
        // 构造函数依赖的参数
        $dependencies = $constructor->getParameters();
        // 根据参数返回实例
        $instances = getDependencies($dependencies);
        return $reflector->newInstanceArgs($instances);
    }

}

function getDependencies($paramters) {
    $dependencies = [];
    foreach ($paramters as $paramter) {
        $dependencies[] = make($paramter->getClass()->name);
    }
    return $dependencies;
}

$user = make('User');
$user->login();
exit;
