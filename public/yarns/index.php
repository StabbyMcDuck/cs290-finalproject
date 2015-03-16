<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 5:01 PM
 */


session_start();

if (!isset($_SESSION['id'])) {
    //redirect them back to login page
    header("Location: ../session/new.php"); /* Redirect browser */
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Yarns</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<a href="new.php">Add Yarn</a>
|
<a href="../session/destroy.php">Logout</a>

<?php
include '../../configuration.php';

// Create connection
$connection = new mysqli(
    $database_configuration['servername'],
    $database_configuration['username'],
    $database_configuration['password'],
    $database_configuration['database']
);

if (!($statement = $connection->prepare(
    "SELECT purchasers.email, " .
    "yarns.manufacturer, " .
    "yarns.name, " .
    "yarns.colorway, " .
    "yarns.purchased, " .
    "yarns.weight, " .
    "yarns.private " .
    "FROM yarns " .
    "INNER JOIN users AS purchasers " .
    "ON purchasers.id = yarns.purchaser_id " .
    "WHERE purchasers.id = ? OR " .
    "yarns.private = 0"
))
) {
    error_log($connection->error);
    ?>
    <p>Try again later (1)</p>
    <?php
    exit;
}

if (!$statement->bind_param('i', $_SESSION['id'])) {
    error_log($statement->error);
    ?>
    <p>Try again later (2)</p>
    <?php
    exit;
}


if (!$statement->execute()) {
    error_log($statement->error);
    ?>
    <p>Try again later (3)</p>
    <?php
    exit;
}

$out_email = null;
$out_manufacturer = null;
$out_name = null;
$out_colorway = null;
$out_purchased = null;
$out_weight = null;
$out_private = null;

if (!$statement->bind_result($out_email, $out_manufacturer, $out_name, $out_colorway, $out_purchased, $out_weight, $out_private)) {
    error_log($statement->error);
    ?>
    <p>Try again later (4)</p>
    <?php
    exit;
}
?>

<table class="table-bordered table-hover">
    <thead>
    <tr>
        <th>
            Purchaser
        </th>
        <th colspan="6">
            Yarn
        </th>
        <th colspan="2">
            Actions
        </th>
    </tr>
    <tr>
        <th>Email</th>
        <th>Manufacturer</th>
        <th>Name</th>
        <th>Colorway</th>
        <th>Purchased Date</th>
        <th>Weight</th>
        <th>Private</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    while ($statement->fetch()) {
?>
        <tr>
            <td>
            <?php echo $out_email ?>
            </td>
            <td>
                <?php echo $out_manufacturer ?>
            </td>
            <td>
                <?php echo $out_name ?>
            </td>
            <td>
                <?php echo $out_colorway ?>
            </td>
            <td>
                <?php echo $out_purchased ?>
            </td>
            <td>
                <?php echo $out_weight ?>
            </td>
            <td>
                <?php echo $out_private ?>
            </td>
            <td>
            </td>
            <td>
            </td>
        </tr>

    <?php
    }
    ?>
    </tbody>
</table>

</body>
</html>
