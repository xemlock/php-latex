<?php

class PhpLatex_Renderer_Typestyle
{
    const FAMILY_UNKNOWN = 0;

    const FAMILY_SERIF = 1;

    const FAMILY_SANS = 2;

    const FAMILY_MONO = 3;

    const STYLE_NORMAL = 0;

    const STYLE_ITALIC = 1;

    const STYLE_SLANTED = 2;

    protected $_parent;

    public $style = self::STYLE_NORMAL;

    public $bold = false;

    public $underline = false;

    public $emphasis = false;

    public $smallcaps = false;

    public $family = self::FAMILY_UNKNOWN;

    public function push()
    {
        $child = clone $this;
        $child->_parent = $this;
        return $child;
    }

    public function pop()
    {
        $parent = $this->_parent;
        $this->_parent = null;
        return $parent;
    }

    public function diff()
    {
        $props = array(
            'style'     => 'int',
            'bold'      => 'bool',
            'underline' => 'bool',
            'emphasis'  => 'bool',
            'smallcaps' => 'bool',
            'family'    => 'int',
        );
        $diff = array();

        if ($this->_parent === null) {
            foreach ($props as $name => $type) {
                $value = $this->$name;
                settype($value, $type);
                $diff[$name] = $value;
            }
        } else {
            foreach ($props as $name => $type) {
                $value = $this->$name;
                settype($value, $type);
                $value2 = $this->_parent->$name;
                settype($value2, $type);
                if ($value !== $value2) {
                    $diff[$name] = $value;
                }
            }
        }
        return $diff;
    }
}
