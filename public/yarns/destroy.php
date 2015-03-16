<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 1:19 PM
 */

error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id'])){
    //redirect them back to login page
    header("Location: ../session/new.php"); /* Redirect browser */
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
}

$yarn_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($yarn_id === null) {
    http_response_code(400);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Yarn ID not given!'
    );
    echo json_encode($response_array);
    exit;
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

if (!($statement = $connection->prepare("DELETE FROM yarns WHERE yarns.id = ? AND yarns.purchaser_id = ?"))) {
    error_log($connection->error);
    http_response_code(500);
    header('Content-type: application/json');
    $response_array = array(
        'status' => 'error',
        'message' => 'Try again later (2)'
    );
    echo json_encode($response_array);
    exit;
}

if (!$statement->bind_param('ii', $yarn_id, $_SESSION['id'])) {
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
    'id' => $yarn_id,
    'message' => 'Yarn removed'
);
echo json_encode($response_array);
exit;
?>