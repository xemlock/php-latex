<?php

abstract class PhpLatex_Renderer_Abstract
    implements PhpLatex_Renderer_NodeRenderer
{
    /**
     * Creates LaTeX representation of the given document node.
     *
     * This method is useful when parts of the rendered document should be
     * presented as the LaTeX source for processing (validating and rendering)
     * by external tools, i.e. MathJaX, mathTeX or mimeTeX.
     *
     * @param PhpLatex_Node|array $node
     * @return string
     */
    public static function toLatex($node) // {{{
    {
        if ($node instanceof PhpLatex_Node) {
            switch ($node->getType()) {
                case PhpLatex_Parser::TYPE_SPECIAL:
                    if ($node->value === '_' || $node->value === '^') {
                        return $node->value . self::toLatex($node->getChildren());
                    }
                    return $node->value;

                case PhpLatex_Parser::TYPE_TEXT:
                    // make sure text is properly escaped
                    $source = Latex_Utils::escape($node->value);
                    return $source;

                case PhpLatex_Parser::TYPE_GROUP:
                    $source = $node->optional ? '[{' : '{';
                    $source .= self::toLatex($node->getChildren());
                    $source .= $node->optional ? '}]' : '}';
                    return $source;

                case PhpLatex_Parser::TYPE_VERBATIM:
                    return $node->value;

                case PhpLatex_Parser::TYPE_MATH:
                    $source = self::toLatex($node->getChildren());
                    if ($node->inline) {
                        return '\\(' . $source . '\\)';
                    } else {
                        return '\\[' . $source . '\\]';
                    }

                case PhpLatex_Parser::TYPE_COMMAND:
                    if ($node->value === '\\string') {
                        $value = $node->value;
                        foreach ($node->getChildren() as $child) {
                            $value .= self::toLatex($child);
                        }
                        return $value;
                    }
                    if ($node->symbol || $node->hasChildren()) {
                        return $node->value . self::toLatex($node->getChildren());
                    }
                    // control word, add space that was removed after
                    return $node->value . ' ';

                case PhpLatex_Parser::TYPE_ENVIRON:
                    return "\\begin{" . $node->value . "}\n"
                         . self::toLatex($node->getChildren())
                         . "\\end{" . $node->value . "}\n";

                case PhpLatex_Parser::TYPE_DOCUMENT:
                    return self::toLatex($node->getChildren());
            }
        } elseif (is_array($node)) {
            // render node list and concatenate results
            $latex = '';
            foreach ($node as $child) {
                $latex .= self::toLatex($child);
            }
            return $latex;
        }
    } // }}}

    /**
     * @param PhpLatex_Node $node
     * @return string
     */
    abstract public function render(PhpLatex_Node $node);

    protected $_commandRenderers = array();

    public function addCommandRenderer($command, $renderer)
    {
        if (!is_callable($renderer) && !$renderer instanceof PhpLatex_Renderer_NodeRenderer) {
            throw new InvalidArgumentException('Renderer is not a callable');
        }
        $this->_commandRenderers[$command] = $renderer;
        return $this;
    }

    public function hasCommandRenderer($command)
    {
        return isset($this->_commandRenderers[$command]);
    }

    public function executeCommandRenderer($command, PhpLatex_Node $node)
    {
        if (!$this->hasCommandRenderer($command)) {
            throw new InvalidArgumentException('Renderer for command ' . $command . ' not available');
        }
        $renderer = $this->_commandRenderers[$command];
        if ($renderer instanceof PhpLatex_Renderer_NodeRenderer) {
            return $renderer->render($node);
        }
        return call_user_func($renderer, $node);
    }
}
