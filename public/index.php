<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
require_once("cors.php");
function deliver_response($response){
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

$url_array = explode('/', $_SERVER['REQUEST_URI']);
array_shift($url_array); // remove first value as it's empty
// remove 2nd and 3rd array, because it's directory
//array_shift($url_array); // 2nd = 'NativeREST'
//array_shift($url_array); // 3rd = 'api'

// get the action (resource, collection)
$action = $url_array[0];
// get the method
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
	case 'customers':
		require_once("crud/customers.class.php");
		require_once("crud/customers.php");
		break;

    case 'cars':
        require_once("crud/cars.class.php");
        require_once("crud/cars.php");
        break;

	case 'login':
		require_once("crud/users.class.php");
		require_once("crud/login.php");
		break;
		
    case 'status':
        // TODO: Hacer un conteo de cada endpoint
        // $status = [
        //     'stats' => getDbStats(),
        //     'MySQL' => 'OK',
        //     'version' => API_VERSION,
        //     'timestamp' => date('d-m-Y',time()),
        // ];
        $response['status'] = 404;
        $response['data'] = null;        
        break;
    default:
		// Set default HTTP response
		$url = $_ENV['APP_DOMAIN'];
        $endpoints = [
			'cars' => $url . '/customers',
            'cars' => $url . '/cars',
			'login' => $url . '/login',
            'status' => $url . '/status',
            'this help' => $url . '',
        ];
        $message = [
            'endpoints' => $endpoints,
			'version' => $_ENV['API_VERSION'],
            'timestamp' => date('d-m-Y',time()),
        ];    
        $response['status'] = 200;
        $response['data'] = $message;
        break;
}

// Return Response to browser
deliver_response($response);