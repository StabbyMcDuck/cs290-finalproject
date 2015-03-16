<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 6:01 PM
 */


session_start();

if(!isset($_SESSION['id'])){
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

    <title>New Yarn</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/yarns/new.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

    <script>
        // wait for the DOM to be loaded
        $(document).ready(function() {
            var options = {
                error: function(xhr, statusText, errorThrown) {
                    $('#form-errors').html(xhr.responseJSON.message);
                },
                success: function(responseJSON, statusText, xhr, formElement) {
                    $(location).attr('href','index.php');
                }
            };
            $('#form').ajaxForm(options);
        });
    </script>
</head>

<body>

<div class="container">

    <form action="create.php" class="form-signin" id="form" method="post">
        <h2 class="form-signin-heading">Add Yarn</h2>
        <label for="manufacturer" class="sr-only">Manufacturer</label>
        <input type="text" id="manufacturer" class="form-control" placeholder="Manufacturer" required autofocus name="manufacturer">

        <label for="name" class="sr-only">Yarn Name</label>
        <input type="text" id="name" class="form-control" placeholder="Yarn Name" required autofocus name="name">

        <label for="colorway" class="sr-only">Colorway</label>
        <input type="text" id="colorway" class="form-control" placeholder="Yarn Colorway" required autofocus name="colorway">

        <label for="purchased" class="sr-only">Date Purchased</label>
        <input type="date" id="purchased" class="form-control" placeholder="Date Purchased" required autofocus name="purchased">

        <label for="weight" class="sr-only">Yarn Weight</label>
        <input type="text" id="weight" class="form-control" placeholder="Yarn Weight" required autofocus name="weight">

        <input type="checkbox" checked name="private" value="true">Make this yarn private<br>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Add Yarn</button>

        <p id="form-errors">

        </p>
    </form>
</div> <!-- /container -->



</body>
</html>
