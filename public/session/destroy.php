<?php
/**
 * Created by PhpStorm.
 * User: reginaimhoff
 * Date: 3/15/15
 * Time: 6:12 PM
 */
session_start();

session_unset();

session_destroy();

header('Location: new.php');

?>