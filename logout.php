<?php
//start the sessoin
session_start();
//to completely destroy sesion
session_destroy();
// redirect user to login page
header( 'Location: index.php' );
?>