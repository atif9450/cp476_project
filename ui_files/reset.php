<?php

session_start();
unset($_SESSION["table"]);
unset($_SESSION["function"]);
header("Location: index.php");
exit;

?>