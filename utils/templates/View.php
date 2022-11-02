<?php

namespace Templates;

/**
 * View represents a single View and allows rendering of it.
 * 
 * Example usage:
 * ```php
 * $view = new View("home.twig");
 * $view->render([
 *     "name" => "Martin"
 * ]);
 * ```
 * 
 * @author  Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class View {

    /**
     * Represents the current Twig Environment.
     * 
     * @see \Twig\Environment
     */
    private \Twig\Environment $environment;

    /**
     * Represents the name of the Twig template file to render.
     */
    private string $name;

    /**
     * Creates a new View with a given template name.
     * 
     * @param   string  $name   Template name in views directory
     */
    public function __construct(string $name) {
        $loader = new \Twig\Loader\FilesystemLoader(PATH_VIEWS);
        $this->environment = new \Twig\Environment($loader, [
            "debug" => DEBUG,
        ]);
        $this->name = $name;
    }

    /**
     * Renders the View with a given context
     * 
     * @param   array   $context    Context of the template
     */
    public function render(array $context) {
        echo $this->environment->render($this->name, $context);
    }

}

?>
