<?php

class PhpLatex_Utils
{
    /**
     * @param  string $string
     * @return string
     */
    public static function escape($string)
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
    }

    /**
     * Converts UTF-8 characters to their LaTeX text mode equivalents.
     * Unrecognized characters are removed from output.
     *
     * @param string $string
     * @return string
     */
    public static function escapeUtf8($string)
    {
        static $map;
        if (null === $map) {
            $map = array_flip(require dirname(__FILE__) . '/../latex_utf8.php');
        }
        $string = strtr($string, $map);
        $string = preg_replace('/[^\t\n\r\x20-\x7E]/', '', $string);
        return $string;
    }
}
