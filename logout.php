<?php 
echo "welcome to logout page";

session_start();
session_unset();
session_destroy();

header("Location: index.php");


?>