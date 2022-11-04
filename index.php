<?php

/**
 * TreeStory
 * 
 * A community-focused social network.
 * @link https://clifford.jmnlabs.fr
 * 
 * 
 * @author  Rudy Boullier       <rudy.boullier@etu.univ-lyon1.fr>
 * @author  Jonathan Montmain   <jonathan.montmain@etu.univ-lyon1.fr>
 * @author  Steve Pennec        <steve.pennec@etu.univ-lyon1.fr>
 * @author  Idrissa Sall        <idrissa.sall@etu.univ-lyon1.fr>
 */

require_once("./vendor/autoload.php");

require_once("./utils/paths.php");
require_once(PATH_UTILS_TEMPLATES . "module.php");
require_once(PATH_UTILS_ROUTING . "module.php");

require_once(PATH_CONFIG . "debug.php");

require_once(PATH_CONTROLLERS . "HomeController.php");
require_once(PATH_CONTROLLERS . "ErrorController.php");

$router = new \Routing\Router();

$router->route(\Routing\Method::GET, "/", ["HomeController", "home"]);
$router->route(\Routing\Method::GET, "/greet/:name", ["HomeController", "greet"]);

$router->route(\Routing\Method::ERROR, "404", ["ErrorController", "notFound"]);

$router->run();

?>
