<?php

interface Middeware {
    public static function handle(Closure $next);
}

class VerfiyCsrfToekn implements Middeware {

    public static function handle(Closure $next)
    {
        echo "验证csrf Token \r\n";
        $next();
    }
}

class VerfiyAuth implements Middeware {

    public static function handle(Closure $next)
    {
        echo "验证是否登录 \r\n";
        $next();
    }
}

class SetCookie implements Middeware {
    public static function handle(Closure $next)
    {
        $next();
        echo "设置cookie信息！\r\n";
    }
}

$handle = function() {
    echo "当前要执行的程序!\r\n";
};


$pipe_arr = [
    'VerfiyCsrfToekn',
    'VerfiyAuth',
    'SetCookie'
];

$callback = array_reduce($pipe_arr, function($stack, $pipe) {
    return function() use ($stack, $pipe){
        return $pipe::handle($stack);
    };
}, $handle);

call_user_func($callback);


