<?php

namespace Routing;

require_once(PATH_UTILS_ROUTING . "Method.php");
require_once(PATH_UTILS_ROUTING . "Route.php");

use \Monolog;

/**
 * Router represents a routing entity that stores Route instances and calls the ones that matches the current request path.
 * 
 * @see \Routing\Route
 * 
 * Example usage:
 * ```php
 * $router = \Routing\Router::getInstance();
 * $router->route(\Routing\Method::GET, "/greet/:name", ["HomeController", "greet"]);
 * $router->run();
 * ```
 * 
 * @author  Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class Router {

    /**
     * Represents all the registered Routes.
     * 
     * @see \Routing\Route
     */
    private array $routes = [];

    /**
     * Represents the logger instance for the routing module.
     */
    private Monolog\Logger $logger;

    /**
     * Current instance of Router being used.
     */
    private static ?Router $instance = null;

    /**
     * Creates a Router.
     */
    private function __construct() {
        self::$instance = $this;
        $logger = new Monolog\Logger("routing", [
            new Monolog\Handler\StreamHandler(PATH_LOGS, Monolog\Logger::WARNING),
        ]);
        $this->logger = $logger;
    }

    /**
     * Gets current instance of Router.
     * 
     * @return  Router              Current instance of Router.
     */
    public static function getInstance() {
        if (self::$instance == null) new self();
        return self::$instance;
    }

    /**
     * Creates a new Route and registers it.
     * 
     * @see \Routing\Route
     * 
     * @param   string  $method     HTTP Method that the request should match.
     * @param   string  $path       String matcher that the request should match.
     * @param   array   $callable   Callable that will be executed if request matches.
     * 
     * @see \Routing\Method
     */
    public function route(string $method, string $path, array $callable) {
        $route = new Route($method, $path, $callable);
        array_push($this->routes, $route);

        return $route;
    }

    /**
     * Runs the Router and tries to match a registered Route.
     * If it finds a matching Route, then it will runs it and stops.
     * 
     * @see \Routing\Route
     */
    public function run() {
        $method = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS));
        $path = rtrim($_GET["path"], "/");
        if (strlen($path) == 0) $path = "/";

        foreach ($this->routes as $route) {
            if ($params = $route->match($method, $path)) {
                $route->run($params);
                return;
            }
        }

        foreach ($this->routes as $route) {
            if ($route->matchError("404")) {
                $route->run([]);
                return;
            }
        }
    }

    /**
     * Tries to find an error handler for an error and runs it.
     * 
     * @param   string  $error      HTTP Error Code.
     * @param   array   $context    Specific context that can be used by the error handler.
     */
    public function throwError(string $error, string $message, array $context = []) {
        if (preg_match("/^5/", $error)) {
            $this->logger->error("Can't handle '" . $_GET['path'] . "': $message");
        } else {
            $this->logger->warning("Can't handle '" . $_GET['path'] . "': $message");
        }
        foreach ($this->routes as $route) {
            if ($route->matchError($error)) {
                $route->run($context);
                return;
            }
        }
    }

}

?>
