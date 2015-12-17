<?php

class PhpLatex_Utils
{
    /**
     * @param  string $string
     * @return string
     */
    public static function escape($string) // {{{
    {
        $replace = array(
            '&' => '\\&',
            '{' => '\\{',
            '}' => '\\}',
            '$' => '\\$',
            '%' => '\\%',
            '#' => '\\#',
            '_' => '\\_',
            '^' => '\\^', // textmode
            '~' => '\\textasciitilde{}', // textmode
            '\\' => '\\textbackslash{}', // textmode
            // escape square brackets so that \\[length] construct does not appear
            '[' => '{[}',
            ']' => '{]}',
        );
        return strtr($string, $replace);
    } // }}}
}
