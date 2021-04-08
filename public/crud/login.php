<?php

$users = new Users();

use \Firebase\JWT\JWT;

if ($method == 'POST') {
    // METHOD : POST api/login
    // get post from client
    $json = file_get_contents('php://input');
    $post = json_decode($json); // decode to object

    // check input
    if ($post->email == "" || $post->password == "") {
        $response['status'] = 400;
        $response['data'] = array('error' => 'Datos incompletos');
    } else {
        $data = $users->checkLogin($post->email, md5($post->password));
        if (empty($data)) {
            $response['status'] = 400;
            $response['data'] = array('error' => 'Hay un error el email y/o contraseña es invalida');
        } else {
            $iss = "localhost";
            $iat = time();
            $nbf = $iat + 10;
            $exp = $iat + 180;
            $aud = "myusers";
            $user_arr_data = array(
                "id" => $data->id,
                "name" => $data->name,
                "email" => $data->email
            );

            $secret_key = $_ENV['ACCESS_TOKEN_SECRET'];

            $payload_info = array(
                "iss" => $iss,
                "iat" => $iat,
                "nbf" => $nbf,
                "exp" => $exp,
                "aud" => $aud,
                "data" => $user_arr_data
            );

            $jwt = JWT::encode($payload_info, $secret_key, 'HS512');
            $response['status'] = 200;
            $response['data'] = array('success' => 'Bearer ' . $jwt);
            $_SERVER['Authorization'] = $jwt;
        }
    }
}
