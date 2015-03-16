<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 6:01 PM
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

    <title>Edit Yarn</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/yarns/new.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

    <script>
        // wait for the DOM to be loaded
        $(document).ready(function () {
            var options = {
                error: function (xhr, statusText, errorThrown) {
                    $('#form-errors').html(xhr.responseJSON.message);
                },
                success: function (responseJSON, statusText, xhr, formElement) {
                    $(location).attr('href', 'index.php');
                }
            };
            $('#form').ajaxForm(options);
        });
    </script>
</head>

<body>
<?php
$yarn_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($yarn_id === null) {
    ?>
    <p>No yarn id given to edit!</p>
    <?php
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

if (!($statement = $connection->prepare(
    "SELECT yarns.id, " .
    "yarns.manufacturer, " .
    "yarns.name, " .
    "yarns.colorway, " .
    "yarns.purchased, " .
    "yarns.weight, " .
    "yarns.private " .
    "FROM yarns " .
    "INNER JOIN users AS purchasers " .
    "ON purchasers.id = yarns.purchaser_id " .
    "WHERE purchasers.id = ? AND yarns.id = ?"
))
) {
    error_log($connection->error);
    ?>
    <p>Try again later (1)</p>
    <?php
    exit;
}

if (!$statement->bind_param('ii', $_SESSION['id'], $yarn_id)) {
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

$out_id = null;
$out_manufacturer = null;
$out_name = null;
$out_colorway = null;
$out_purchased = null;
$out_weight = null;
$out_private = null;

if (!$statement->bind_result($out_id, $out_manufacturer, $out_name, $out_colorway, $out_purchased, $out_weight, $out_private)) {
    error_log($statement->error);
    ?>
    <p>Try again later (4)</p>
    <?php
    exit;
}
while ($statement->fetch()) {
    ?>
    <div class="container">

        <form action="update.php" class="form-signin" id="form" method="post">
            <h2 class="form-signin-heading">Edit Yarn</h2>
            <input type="hidden" name="id" value="<?php echo $out_id ?>">

            <label for="manufacturer" class="sr-only">Manufacturer</label>
            <input type="text" id="manufacturer" class="form-control" placeholder="Manufacturer" required autofocus
                   name="manufacturer" value="<?php echo $out_manufacturer ?>">

            <label for="name" class="sr-only">Yarn Name</label>
            <input type="text" id="name" class="form-control" placeholder="Yarn Name" required autofocus name="name"
                   value="<?php echo $out_name ?>">

            <label for="colorway" class="sr-only">Colorway</label>
            <input type="text" id="colorway" class="form-control" placeholder="Yarn Colorway" required autofocus
                   name="colorway" value="<?php echo $out_colorway ?>">

            <label for="purchased" class="sr-only">Date Purchased</label>
            <input type="date" id="purchased" class="form-control" placeholder="Date Purchased" required autofocus
                   name="purchased" value="<?php echo $out_purchased ?>">

            <label for="weight" class="sr-only">Yarn Weight</label>
            <input type="text" id="weight" class="form-control" placeholder="Yarn Weight" required autofocus
                   name="weight" value="<?php echo $out_weight ?>">

            <input type="checkbox" <?php
            if ($out_private == 1) {
                echo "checked";
            }
            ?> name="private" value="true">Make this yarn private<br>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Update Yarn</button>

            <p id="form-errors">

            </p>
        </form>
    </div> <!-- /container -->

<?php
}
?>
</body>
</html>
