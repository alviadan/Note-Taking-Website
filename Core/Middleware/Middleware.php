<?php

namespace Core\Middleware;

class Middleware{
    const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ];

    public static function resolve($key){
        if(!$key){
            return;
        }

        $middleware = static::MAP[$key];

        if(! $middleware){
            throw new \Exception("No mathcing middleware found fot the '{$key}'. ");
        }

        (new $middleware)->handle();
    }
}