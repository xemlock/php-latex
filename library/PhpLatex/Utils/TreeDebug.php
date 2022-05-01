<?php

/**
 * @internal
 */
class PhpLatex_Utils_TreeDebug
{
    public static function debug(PhpLatex_Node $node, $echo = true)
    {
        $result = self::_debug($node);
        if ($echo) {
            echo $result;
            return true;
        }
        return $result;
    }

    protected static function _debug(PhpLatex_Node $node, $indent = '') {
        $str = "type: {$node->getType()}\n";

        if (count($node->getProps())) {
            $str .= $indent . "props:\n";
            foreach ($node->getProps() as $key => $value) {
                if ($key === 'mode') {
                    switch ($value) {
                        case PhpLatex_Parser::MODE_MATH:
                            $value = "$value (math)";
                            break;
                        case PhpLatex_Parser::MODE_TEXT:
                            $value = "$value (text)";
                            break;
                        case PhpLatex_Parser::MODE_BOTH:
                            $value = "$value (both)";
                            break;
                    }
                }
                if ($key === 'value') {
                    $value = '"' . strtr($value, array(
                            "\n" => '\n',
                            "\t" => '\t',
                            "\r" => '\r',
                        )) . '"';
                }
                if (is_bool($value)) {
                    $value = var_export($value, true);
                }
                $str .= $indent . "  {$key}: $value\n";
            }
        } else {
            $str .= $indent . "props: (empty)\n";
        }

        if (count($node->getChildren())) {
            $str .= $indent . "children:\n";
            foreach ($node->getChildren() as $child) {
                $str .= $indent . '  - ' . self::_debug($child, $indent . '    ');
            }
        } else {
            $str .= $indent . "children: (empty)\n";
        }

        return $str;
    }
}
