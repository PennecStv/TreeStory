<?php

declare(strict_types = 1);

namespace Routing;

require_once("./utils/paths.php");
require_once(PATH_UTILS_ROUTING . "/module.php");

use PHPUnit\Framework\TestCase;
use Routing\Method;
use Routing\Route;

final class RoutingTest extends TestCase {

    /**
     * @covers Route
     */
    public function testRouteMatchesWithParameters() {
        $route = new Route(Method::GET, "/greet/:name/please", []);
        $this->assertEquals("Martin", $route->match(Method::GET, "/greet/Martin/please")["name"], "Assert that '/greet/:name/please' matches '/greet/Martin/please' while parsing the parameter");
    }

}

?>