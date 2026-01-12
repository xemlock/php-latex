<?php

abstract class PhpLatex_Renderer_Abstract
{
    /**
     * Creates LaTeX representation of the given document node.
     *
     * This method is useful when parts of the rendered document should be
     * presented as the LaTeX source for processing (validating and rendering)
     * by external tools, i.e. MathJaX, mathTeX or mimeTeX.
     *
     * @param PhpLatex_Node|PhpLatex_Node[] $node
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
                    $source = PhpLatex_Utils::escape($node->value);
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
                    $value = $node->value;
                    if ($node->starred) {
                        $value .= '*';
                    }
                    if ($node->value === '\\string') {
                        foreach ($node->getChildren() as $child) {
                            $value .= self::toLatex($child);
                        }
                        return $value;
                    }

                    if ($node->value === '\\href' || $node->value === '\\url') {
                        foreach ($node->getChildren() as $index => $child) {
                            if ($index === 0) {
                                $url = $child->getChild(0);
                                if ($url) {
                                    // The special characters # and % do not need to be escaped in any way (unless
                                    // the command is used in the argument of another command).
                                    // https://texdoc.org/serve/hyperref/0

                                    // Only control words (i.e. starting with a backslash) cause miscellaneous errors, among others:
                                    // - You've closed more groups than you opened.
                                    // - TeX capacity exceeded, sorry
                                    // - Paragraph ended before \hyper@n@rmalise was complete.
                                    // all other characters, normally treated as special, lose their meanings inside \href and \url
                                    $value .= '{' . str_replace('\\', '\\\\', (string) $url->value) . '}';
                                } else {
                                    $value .= '{}';
                                }
                            } else {
                                $value .= self::toLatex($child);
                            }
                        }
                        return $value;
                    }

                    if ($node->symbol || $node->hasChildren()) {
                        return $value . self::toLatex($node->getChildren());
                    }

                    // some control words, e.g. \left[, doesn't need space after
                    if ($node->noSpaceAfter) {
                        return $value;
                    }
                    // control word, add space that was removed after
                    return $value . ' ';

                case PhpLatex_Parser::TYPE_ENVIRON:
                    $children = $node->getChildren();
                    $argsEnd = 0;

                    foreach ($children as $child) {
                        if ($child->arg || $child->optional) {
                            ++$argsEnd;
                        } else {
                            break;
                        }
                    }

                    $args = array_slice($children, 0, $argsEnd);
                    $children = array_slice($children, $argsEnd);

                    return "\\begin{" . $node->value . "}" . self::toLatex($args) . "\n"
                         . rtrim(self::toLatex($children)) . "\n"
                         . "\\end{" . $node->value . "}";

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
     * @param PhpLatex_Node|string $node
     * @return string
     */
    abstract public function render($node);

    protected $_commandRenderers = array();

    public function addCommandRenderer($command, $renderer)
    {
        if (!is_callable($renderer) && !$renderer instanceof PhpLatex_Renderer_NodeRenderer) {
            throw new InvalidArgumentException(sprintf(
                'Renderer must be an instance of PhpLatex_Renderer_NodeRenderer or a callable, %s given',
                is_object($renderer) ? get_class($renderer) : gettype($renderer)
            ));
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
