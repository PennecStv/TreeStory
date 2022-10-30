<?php

declare(strict_types = 1);

namespace Templates;

require_once("./utils/paths.php");
require_once(PATH_UTILS_TEMPLATES . "/module.php");

use PHPUnit\Framework\TestCase;
use Templates\View;

const DEBUG = true;

final class TemplatesTest extends TestCase {

    /**
     * @covers View
     */
    public function testViewRenderWithContext() {
        $view = new View("tests.twig");
        $this->expectOutputString("Hello Martin!");
        $view->render([
            "name" => "Martin"
        ]);
    }

}

?>