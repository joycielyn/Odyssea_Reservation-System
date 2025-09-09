<?php
session_start();
include_once "connectdb.php";

// Burahin lahat ng session data
session_unset();
session_destroy();

// Redirect sa login page
header("Location: ../login.php");
exit();