<?php

$invoices = new Invoices();

if ($method == 'GET') {
    if (!isset($url_array[1])) { // if parameter id not exist
        // METHOD : GET api/invoices
        $data = $invoices->getAll();
        $response['status'] = 200;
        $response['data'] = $data;
    } else { // if parameter id exist
        // METHOD : GET api/invoices/:id
        $bookingid = $url_array[1];
        $data = $invoices->getInvoices($bookingid);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Objeto no encontrado');
        } else {
            $response['status'] = 200;
            $response['data'] = $data;
        }
    }
} elseif ($method == 'DELETE') {
    // METHOD : DELETE api/invoices/:id
    if (isset($url_array[1])) {
        $id = $url_array[1];
        // check if id exist in database
        $data = $invoices->getInvoices($id);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');
        } else {
            $status = $invoices->deleteInvoices($id);
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
