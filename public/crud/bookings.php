<?php

$bookings = new Bookings();

if ($method == 'GET') {
    if (!isset($url_array[1])) { // if parameter id not exist
        // METHOD : GET api/bookings
        $data = $bookings->getAllBookings();
        $response['status'] = 200;
        $response['data'] = $data;
    } else { // if parameter id exist
        // METHOD : GET api/bookings/:id
        $bookingid = $url_array[1];
        $data = $bookings->getBookings($bookingid);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Objeto no encontrado');
        } else {
            $response['status'] = 200;
            $response['data'] = $data;
        }
    }
} elseif ($method == 'POST') {
    // METHOD : POST api/bookings
    // get post from client
    require_once('customers.class.php');
    $json = file_get_contents('php://input');
    $post = json_decode($json); // decode to object

    // check input
    if (
        $post->firstname == "" || $post->lastname == "" || $post->car == "" || $post->license == "" || $post->startkm == "" || $post->startdate == ""
    ) {
        $response['status'] = 400;
        $response['data'] = array('error' => 'Datos incompletos');
    } else {
        $customers = new Customers();
        $customerid = $customers->insertCustomers($post->firstname, $post->lastname, $post->license);
        if (!empty($customerid)) {
            $status = $bookings->insertBookings($customerid, $post->car, $post->startdate, $post->startkm);
            if (!empty($status)) {
                $response['status'] = 201;
                $response['data'] = array('id' => $status);
            } else {
                $response['status'] = 400;
                $response['data'] = array('error' => 'Hay un error');
            }
        } else {
            $response['status'] = 400;
            $response['data'] = array('error' => 'Hay un error');
        }            
    }
} elseif ($method == 'PUT') {
    require_once('cars.class.php');
    require_once('invoices.class.php');
    // METHOD : PUT api/bookings/:id
    if (isset($url_array[1])) {
        $bookingid = $url_array[1];
        // check if id exist in database
        $data = $bookings->getBookings($bookingid);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');
        } else {
            // get post from client
            $json = file_get_contents('php://input');
            $post = json_decode($json); // decode to object

            // check input completeness
            if ($post->enddate == "" || $post->endkm == "") {
                $response['status'] = 400;
                $response['data'] = array('error' => 'Datos incompletos');
            } else {
                $status = $bookings->updateBookings($post->enddate, $post->endkm, $bookingid);
                if ($status == 1) {
                    $carid = $data->carid;
                    $customerid = $data->customerid;
                    $totalkm = $post->endkm - $data->startkm;
                    $cars = new Cars();
                    $car = $cars->getCars($carid);
                    $cost = $car->costformula;
                    $totalcost = $cost * $totalkm;
                    $invoices = new Invoices();
                    $invoice = $invoices->insertInvoices($bookingid, $totalcost, $totalkm, $carid, $customerid);
                    if ($invoice == 1) {
                        $response['status'] = 200;
                        $response['data'] = array('success' => 'Los datos se editaron correctamente y se genero la factura');
                    } else {
                        $response['status'] = 400;
                        $response['data'] = array('error' => 'Hubo un error generando la factura');
                    }
                } else {
                    $response['status'] = 400;
                    $response['data'] = array('error' => 'Hay un error');
                }
            }
        }
    }
} elseif ($method == 'DELETE') {
    // METHOD : DELETE api/bookings/:id
    if (isset($url_array[1])) {
        $bookingidid = $url_array[1];
        // check if id exist in database
        $data = $bookings->getBookings($bookingidid);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');
        } else {
            $status = $bookings->deleteBookings($bookingidid);
            if ($status == 1) {
                $response['status'] = 200;
                $response['data'] = array('success' => 'Datos eliminados con éxito');
            } else {
                $response['status'] = 400;
                $response['data'] = array('error' => 'Hay un error');
            }
        }
    }
}
