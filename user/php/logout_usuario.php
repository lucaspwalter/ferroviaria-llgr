<?php
session_start();
session_destroy();
header("Location: ../html/telainicialU.html");
exit();
?>