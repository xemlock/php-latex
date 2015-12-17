<?php

interface PhpLatex_Utils_PeekableIterator extends Iterator
{
    /**
     * Returns the next element in the iteration, without advancing
     * the iteration.
     *
     * @return mixed
     */
    public function peek();

    /**
     * Returns true if the iteration has more elements.
     *
     * @return bool
     */
    public function hasNext();
}
