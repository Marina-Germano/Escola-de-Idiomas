<?php

   include 'connect.php';

   setcookie('idaluno', '', time() - 1, '/');

   header('location:../login.php');

?>