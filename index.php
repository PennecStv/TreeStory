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

require_once(PATH_CONTROLLERS . "HomeController.php");
require_once(PATH_CONTROLLERS . "ErrorController.php");
require_once(PATH_CONTROLLERS . "UserController.php");
require_once(PATH_CONTROLLERS . "RegisterController.php");
require_once(PATH_CONTROLLERS . "ModifProfilController.php");

use Symfony\Component\Dotenv\Dotenv;
use Routing\Router;
use Routing\Method;

$dotenv = new Dotenv();
$dotenv->loadEnv(PATH_CONFIG . ".env");

$router = Router::getInstance();

$router->route(Method::GET, "/", ["HomeController", "home"]);

$router->route(Method::GET, "/login", ["UserController", "login"]);
$router->route(Method::POST, "/login", ["UserController", "login"]);
$router->route(Method::GET, "/logout", ["UserController", "logout"]);

$router->route(Method::GET, "/register", ["RegisterController", "register"]);
$router->route(Method::POST, "/register", ["RegisterController", "register"]);

$router->route(Method::GET, "/modificationProfil", ["ModifProfilController", "modification"]);


$router->route(Method::ERROR, "404", ["ErrorController", "notFound"]);

$router->run();

?>