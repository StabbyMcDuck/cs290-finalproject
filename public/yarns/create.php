<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 1:19 PM
 */

session_start();

if(!isset($_SESSION['id'])){
    //redirect them back to login page
    header("Location: ../session/new.php"); /* Redirect browser */
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
}

$manufacturer = filter_input(INPUT_POST, 'manufacturer', FILTER_DEFAULT);
if ($manufacturer === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Manufacturer not entered!'
    );
    echo json_encode($response_array);
    exit;
}

$name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
if ($name === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Name not entered!'
    );
    echo json_encode($response_array);
    exit;
}

$colorway = filter_input(INPUT_POST, 'colorway', FILTER_DEFAULT);
if ($colorway === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Colorway not entered!'
    );
    echo json_encode($response_array);
    exit;
}

$purchased = filter_input(INPUT_POST, 'purchased', FILTER_DEFAULT);
if ($purchased === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Purchase date not entered!'
    );
    echo json_encode($response_array);
    exit;
}

$purchased_parsed = date_parse($purchased);
if (!checkdate($purchased_parsed['month'], $purchased_parsed['day'], $purchased_parsed['year'])) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Date entered is invalid!'
    );
    echo json_encode($response_array);
    exit;
}

$weight = filter_input(INPUT_POST, 'weight', FILTER_DEFAULT);
if ($weight === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Weight not entered!'
    );
    echo json_encode($response_array);
    exit;
}

$private = filter_input(INPUT_POST, 'private', FILTER_VALIDATE_BOOLEAN);
// cast to int because mysql boolean is tinyint(1)
if ($private === NULL) {
    $private = 0;
} elseif ($private === false) {
    $private = 0;
} elseif ($private === true) {
    $private = 1;
}

include '../../configuration.php';

// Create connection
$connection = new mysqli(
    $database_configuration['servername'],
    $database_configuration['username'],
    $database_configuration['password'],
    $database_configuration['database']
);

if ($connection->connect_error) {
    http_response_code(500);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Try again later (1)'
    );
    echo json_encode($response_array);
    exit;
}

if (!($statement = $connection->prepare("INSERT INTO yarns(colorway, manufacturer, name, purchased, purchaser_id, private, weight) VALUES(?,?,?,?,?,?,?) "))) {
    http_response_code(500);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Try again later (2)'
    );
    echo json_encode($response_array);
    exit;
}

if (!$statement->bind_param('ssssiis', $colorway, $manufacturer, $name, $purchased, $_SESSION['id'], $private, $weight)) {
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Try again later (3)'
    );
    echo json_encode($response_array);
    exit;
}

if (!$statement->execute()) {
    error_log($statement->error);

    http_response_code(500);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Try again later (4)'
    );
    echo json_encode($response_array);
    exit;
}
$statement->close();
header('Content-type: application/json');
$response_array = array(
    'status' => 'success',
    'message' => 'Yarn created'
);
echo json_encode($response_array);
exit;
?>