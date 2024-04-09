<?php
session_start(); 

unset($_SESSION['studentID']);
unset($_SESSION['instructorID']);

session_destroy(); 

header("Location: index.php");
exit();
?>
