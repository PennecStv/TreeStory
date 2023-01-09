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

session_start();

require_once("./vendor/autoload.php");

require_once("./utils/paths.php");
require_once(PATH_UTILS_TEMPLATES . "module.php");
require_once(PATH_UTILS_ROUTING . "module.php");

require_once(PATH_CONTROLLERS . "HomeController.php");
require_once(PATH_CONTROLLERS . "ErrorController.php");
require_once(PATH_CONTROLLERS . "UserController.php");
require_once(PATH_CONTROLLERS . "PasswordController.php");
require_once(PATH_CONTROLLERS . "RegisterController.php");
require_once(PATH_CONTROLLERS . "ConfigProfilController.php");
require_once(PATH_CONTROLLERS . "accountController.php");
require_once(PATH_CONTROLLERS . "SearchController.php");

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

$router->route(Method::GET, "/forgotPassword", ["PasswordController", "forgotPassword"]);
$router->route(Method::POST, "/forgotPassword", ["PasswordController", "forgotPassword"]);

$router->route(Method::GET, "/resetPassword", ["PasswordController", "resetPassword"]);
$router->route(Method::POST, "/resetPassword", ["PasswordController", "resetPassword"]);

$router->route(Method::GET, "/register", ["RegisterController", "register"]);
$router->route(Method::POST, "/register", ["RegisterController", "register"]);

$router->route(Method::GET, "/configProfil", ["ConfigProfilController", "configProfil"]);
$router->route(Method::POST, "/configProfil", ["ConfigProfilController", "configProfil"]);

$router->route(Method::GET, "/user/account", ["AccountController", "account"]);
$router->route(Method::GET, "/user/:userId", ["AccountController", "displayAccount"]);
$router->route(Method::POST, "/user/:id/follow", ["AccountController", "follow"]);
$router->route(Method::POST, "/user/:id/unfollow", ["AccountController", "unfollow"]);


$router->route(Method::GET, "/search", ["SearchController", "search"]);
$router->route(Method::POST, "/search", ["SearchController", "search"]);

$router->route(Method::ERROR, "404", ["ErrorController", "notFound"]);

$router->run();

?>