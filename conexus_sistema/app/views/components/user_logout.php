<?php

   include 'connect.php';

   setcookie('idusuario', '', time() - 1, '/');

   header('location:../login.php');

?>