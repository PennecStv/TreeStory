<?php

/**
 * This file allows disconnection from the site.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */

$_SESSION = array();
session_destroy();
header('Location: '.PATH_CONTROLLERS.'ConnectionController.php');

?>