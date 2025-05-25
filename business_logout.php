<?php

@include 'components/connect.php';

session_start();
session_unset();
session_destroy();

header('location:business_login.php');

?> 