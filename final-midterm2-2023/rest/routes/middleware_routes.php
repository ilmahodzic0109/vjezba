<?php

/*use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('/*', function() {
    if(
        strpos(Flight::request()->url, '/final/share_classes') === 0 ||
        strpos(Flight::request()->url, '/final/share_class_categories') === 0 ||
        strpos(Flight::request()->url, '/final/login') === 0 
    ) {
        return TRUE;
    } else {
        $token = Flight::request()->getHeader("Authorization");

        if (!$token) {
            Flight::halt(401, "Missing authentication header");
        }

        // Remove 'Bearer ' prefix from token
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        try {
            $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            Flight::set('user', $decoded_token->user);
            Flight::set('jwt_token', $token);
            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});

Flight::map('error', function($e){
    // We want to log every error that happens
    file_put_contents('logs.txt', $e . PHP_EOL, FILE_APPEND | LOCK_EX);

    Flight::halt($e->getCode(), $e->getMessage());
    Flight::stop($e->getCode());
});*/