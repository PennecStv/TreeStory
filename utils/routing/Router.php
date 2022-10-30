<?php

namespace Routing;

require_once(PATH_UTILS_ROUTING . "Method.php");
require_once(PATH_UTILS_ROUTING . "Route.php");

/**
 * Router represents a routing entity that stores Route instances and calls the ones that matches the current request path.
 * 
 * @see \Routing\Route
 * 
 * Example usage:
 * ```php
 * $router = new \Routing\Router();
 * $router->route(Method::GET, "/greet/:name", ["HomeController", "greet"])
 * $router->run();
 * ```
 * 
 * @author  Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class Router {

    /**
     * Represents all the registered Routes.
     * 
     * @see \Routing\Routing
     */
    private array $routes = [];

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

}

?>
