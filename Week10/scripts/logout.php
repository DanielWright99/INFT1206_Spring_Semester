<?php

//start session
session_start();

//clear and destroy existing session
$_SESSION = [];
session_destroy();

//redirect to home (index) page
header("Location: ../views/index.php");
exit;
