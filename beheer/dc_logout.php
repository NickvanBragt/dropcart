<?php

// log out the user

session_destroy();
header('Location: /beheer/dc_login.php');

?>