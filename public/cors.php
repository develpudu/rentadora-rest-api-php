<?php
// CORS
$origin = ($_SERVER['SERVER_NAME'] == $_ENV['FRONTEND_URL_DEVELOPMENT']) ? 'https://' . $_ENV['FRONTEND_URL_DEVELOPMENT'] : 'https://' . $_ENV['FRONTEND_URL_PRODUCTION'];
header('Access-Control-Allow-Origin: ' . $origin);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: HEAD, DELETE, POST, PUT, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Expose-Headers: Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
