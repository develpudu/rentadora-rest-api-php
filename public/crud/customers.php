<?php

$customers = new Customers();

if ($method == 'GET') {
    if (!isset($url_array[1])) { // if parameter id not exist
        // METHOD : GET api/customers
        $data = $customers->getAll();
        $response['status'] = 200;
        $response['data'] = $data;
    } else { // if parameter id exist
        // METHOD : GET api/customers/:id
        $id = $url_array[1];
        $data = $customers->getCustomers($id);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Objeto no encontrado');
        } else {
            $response['status'] = 200;
            $response['data'] = $data;
        }
    }
} elseif ($method == 'POST') {
    // METHOD : POST api/customers
    // get post from client
    $json = file_get_contents('php://input');
    $post = json_decode($json); // decode to object

    // check input
    if (
        $post->fistname == "" || $post->lastname == "" || $post->license == ""
    ) {
        $response['status'] = 400;
        $response['data'] = array('error' => 'Datos incompletos');
    } else {
        $status = $customers->insertCustomers($post->fistname, $post->lastname, $post->license);
        if ($status == 1) {
            $response['status'] = 201;
            $response['data'] = array('success' => 'Datos guardados exitosamente');
        } else {
            $response['status'] = 400;
            $response['data'] = array('error' => 'Hay un error');
        }
    }
} elseif ($method == 'PUT') {
    // METHOD : PUT api/customers/:id
    if (isset($url_array[1])) {
        $id = $url_array[1];
        // check if id exist in database
        $data = $customers->getCustomers($id);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');
        } else {
            // get post from client
            $json = file_get_contents('php://input');
            $post = json_decode($json); // decode to object

            // check input completeness
            if ($post->fistname == "" || $post->lastname == "" || $post->license == "") {
                $response['status'] = 400;
                $response['data'] = array('error' => 'Datos incompletos');
            } else {
                $status = $customers->updateCustomers($id, $post->fistname, $post->lastname, $post->license);
                if ($status == 1) {
                    $response['status'] = 200;
                    $response['data'] = array('success' => 'Los datos se editaron correctamente');
                } else {
                    $response['status'] = 400;
                    $response['data'] = array('error' => 'Hay un error');
                }
            }
        }
    }
} elseif ($method == 'DELETE') {
    // METHOD : DELETE api/customers/:id
    if (isset($url_array[1])) {
        $id = $url_array[1];
        // check if id exist in database
        $data = $customers->getCustomers($id);
        if (empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');
        } else {
            $status = $customers->deleteCustomers($id);
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
