<?php

define('API_VERSION', '0.23.0');

require __DIR__ . '/../vendor/autoload.php';
require "cors.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function deliver_response($response)
{
	// Define HTTP responses
	$http_response_code = array(
		100 => 'Continue',  
		101 => 'Switching Protocols',  
		200 => 'OK',
		201 => 'Created',  
		202 => 'Accepted',  
		203 => 'Non-Authoritative Information',  
		204 => 'No Content',  
		205 => 'Reset Content',  
		206 => 'Partial Content',  
		300 => 'Multiple Choices',  
		301 => 'Moved Permanently',  
		302 => 'Found',  
		303 => 'See Other',  
		304 => 'Not Modified',  
		305 => 'Use Proxy',  
		306 => '(Unused)',  
		307 => 'Temporary Redirect',  
		400 => 'Bad Request',  
		401 => 'Unauthorized',  
		402 => 'Payment Required',  
		403 => 'Forbidden',  
		404 => 'Not Found',  
		405 => 'Method Not Allowed',  
		406 => 'Not Acceptable',  
		407 => 'Proxy Authentication Required',  
		408 => 'Request Timeout',  
		409 => 'Conflict',  
		410 => 'Gone',  
		411 => 'Length Required',  
		412 => 'Precondition Failed',  
		413 => 'Request Entity Too Large',  
		414 => 'Request-URI Too Long',  
		415 => 'Unsupported Media Type',  
		416 => 'Requested Range Not Satisfiable',  
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',  
		501 => 'Not Implemented',  
		502 => 'Bad Gateway',  
		503 => 'Service Unavailable',  
		504 => 'Gateway Timeout',  
		505 => 'HTTP Version Not Supported'
		);

	// Set HTTP Response
	header('HTTP/1.1 '.$response['status'].' '.$http_response_code[ $response['status'] ]);
	// Set HTTP Response Content Type
	header('Content-Type: application/json; charset=utf-8');
	// Format data into a JSON response
	$json_response = json_encode($response['data']);
	// Deliver formatted data
	echo $json_response;

	exit;
}

function getDbStats()
{
	// Set automatic endpoints
	$directory = 'crud' . DIRECTORY_SEPARATOR;

	$dbstats = array();
	foreach (glob($directory . "*.class.php") as $filename) {
		if (!is_dir($filename)) {
			require_once $filename;
			$replace = array(
				$directory, '.php', '.class'
			);
			$filename = str_replace($replace, '', $filename);
			$class = ucfirst($filename);
			$obj = new $class;
			$dbstats += [
				$filename => count($obj->getAll()),
			];
		}
	}
	return $dbstats;
}

$url_array = explode('/', $_SERVER['REQUEST_URI']);
array_shift($url_array); // remove first value as it's empty

// get the action (resource, collection)
$action = $url_array[0];
// get the method
$method = $_SERVER['REQUEST_METHOD'];

if ($action == 'status') {
	$status = [
		'stats' => getDbStats(),
		'MySQL' => 'OK',
		'version' => API_VERSION,
		'timestamp' => date('d-m-Y', time()),
	];
	$response['status'] = 200;
	$response['data'] = $status;
} else if (empty($action)) {
	// Set default HTTP response
	$url = $_ENV['APP_DOMAIN'];

	$endpoints_base = [
		'this help' => $url . '',
		'status' => $url . '/status',
	];

	// Set automatic endpoints
	$directory = 'crud' . DIRECTORY_SEPARATOR;

	$endpoints_files = array();
	foreach (glob($directory . "*.php") as $filename) {
		if (!is_dir($filename)) {
			$replace = array(
				$directory, '.php', '.class'
			);
			$filename = str_replace($replace, '', $filename);
			$endpoints_files += [
				$filename => $url . '/' . $filename,
			];
		}
	}
	
	$message = [
		'endpoints' => $endpoints_base + $endpoints_files,
		'version' => API_VERSION,
		'timestamp' => date('d-m-Y',time()),
	];    
	$response['status'] = 200;
	$response['data'] = $message;
} else {
	if (file_exists('crud/' . $action . '.php')) {
		// Login use class Users
		if ($action == 'login') {
			require_once 'crud/users.class.php';
			require_once 'crud/' . $action . '.php';
		} else {
			require_once 'crud/' . $action . '.class.php';
			require_once 'crud/' . $action . '.php';
		}
	} else {
		$response['status'] = 404;
		$response['data'] = 'Endpoint invalid';
	}
}

// Return Response to browser
deliver_response($response);