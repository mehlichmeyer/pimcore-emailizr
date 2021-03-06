<?php

namespace EmailizrBundle\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;

class InlineStyleNode extends Node
{
    /**
     * InlineCssNode constructor.
     *
     * @param array  $html
     * @param int    $line
     * @param string $tag
     */
    public function __construct($html, $line = 0, $tag = 'inlinesytle')
    {
        parent::__construct(['html' => $html], [], $line, $tag);
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->write("ob_start();\n")
            ->subcompile($this->getNode('html'))
            ->write('$zurbCss = "";')
            ->write('foreach($context["emailizr_style_collector"] as $cssFile){')
            ->write('$path = $context["emailizr_locator"]->locate($cssFile);')
            ->write('if($path){$zurbCss .= "\n".file_get_contents($path);}')
            ->write('}')
            ->write('echo $context["emailizr_inline_style_parser"]->parseInlineHtml(ob_get_clean(), $zurbCss);')
            ->write('$context["emailizr_style_collector"]->removeAll();');
    }
}
