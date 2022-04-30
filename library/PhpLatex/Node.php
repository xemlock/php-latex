<?php

/**
 * Class representing AST node of a parsed document.
 */
class PhpLatex_Node
{
    protected $_type;
    protected $_props;
    protected $_children = array();

    /**
     * @param mixed $type
     * @param array $props
     */
    public function __construct($type, array $props = null)
    {
        $this->_type = $type;

        // _props and _children properties are lazily-initialized
        // on first write

        if (null !== $props) {
            $this->setProps($props);
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return PhpLatex_Node
     */
    public function addChild(PhpLatex_Node $node)
    {
        return $this->appendChild($node);
    }

    public function appendChild(PhpLatex_Node $child)
    {
        $this->_children[] = $child;
        return $this;
    }

    public function appendTo(PhpLatex_Node $parent)
    {
        $parent->appendChild($this);
        return $this;
    }

    /**
     * Retrieves the child node corresponding to the specified index.
     *
     * @param  int $index   The zero-based index of the child
     * @return PhpLatex_Node
     */
    public function getChild($index)
    {
        return isset($this->_children[$index]) ? $this->_children[$index] : null;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (bool) count($this->_children);
    }

    /**
     * @return PhpLatex_Node
     */
    public function setProps(array $props)
    {
        foreach ($props as $key => $value) {
            $this->setProp($key, $value);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getProps()
    {
        return (array) $this->_props;
    }

    /**
     * @param  string $key
     * @param  mixed $value
     * @return PhpLatex_Node
     */
    public function setProp($key, $value)
    {
        if (null === $value) {
            // unsetting an unexistant element from an array does not trigger
            // "Undefined variable" notice, see:
            // http://us.php.net/manual/en/function.unset.php#77310
            unset($this->_props[$key]);
        } else {
            $this->_props[$key] = $value;
        }
        return $this;
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function getProp($key)
    {
        return isset($this->_props[$key]) ? $this->_props[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->setProp($key, $value);
    }

    public function __get($key)
    {
        return $this->getProp($key);
    }

    public function __isset($key)
    {
        return $this->getProp($key) !== null;
    }
}
