<?php

namespace Routing;

use Exception;

/**
 * Route represents a single Route witch a fixed method, path and callable.
 * 
 * Example usage:
 * ```php
 * new Route(\Routing\Method::GET, "/", ["HomeController", "home"]);
 * ```
 * 
 * @author  Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class Route {

    private string $method;
    private string $path;
    private array $callable;

    /**
     * Creates a new Route with a given method, path, and callable.
     * 
     * @param   string  $method     HTTP Method that the request should match.
     * @param   string  $path       String matcher that the request should match.
     * @param   array   $callable   Callable that will be executed if request matches.
     * 
     * @see \Routing\Method
     */
    public function __construct(string $method, string $path, array $callable) {
        $this->method = $method;
        $this->path = $path;
        $this->callable = $callable;
    }

    /**
     * Checks if a given request matches with the Route.
     * 
     * @param   string  $method     Request HTTP Method.
     * @param   string  $path       Request path.
     * 
     * @see \Routing\Method
     * 
     * @return  array|false         Parameters that fits the matcher if the request matches with the Route, false otherwise.
     */
    public function match(string $method, string $path) {
	    if ($this->method == $method && preg_match($this->buildRegexFromMatcher($this->path), $path, $params)) return $params;
        return false;
    }

    /**
     * Checks if a given error matches with the Route.
     * 
     * @param   string  $error      Request HTTP Error.
     * 
     * @return  bool                Whether or not the error matches with the Route.
     */
    public function matchError(string $error) {
        if ($this->method == Method::ERROR && $this->path == $error) {
            return true;
        }
        return false;
    }

    /**
     * Runs the route with given parameters.
     * 
     * @param   array   $params     Specific params specified by the Route string matcher.
     */
    public function run(array $params) {
	    call_user_func($this->callable, $params);
    }

    /**
     * Transforms the String matcher into a regex that cans be used to compare paths.
     * 
     * @param   string  $path       String matcher that the request should match.
     * 
     * @return  string              Regular expression that the request should match.
     */
    private function buildRegexFromMatcher(string $path) {
        if (!preg_match("/[-:\/_{}()a-zA-Z\d]/", $path)) return "/^never$./";

        $regex = preg_replace("/\//", "\/", $path);
        $regex = preg_replace("/:([a-zA-Z0-9\_\-]+)/", "(?<$1>[a-zA-Z0-9\_\-]+)", $regex);
        $regex = "/^" . $regex . "$/";

        return $regex;
    }

}

?>
