<?php

interface PhpLatex_Renderer_NodeRenderer
{
    /**
     * @param PhpLatex_Node $node
     * @return string
     */
    public function render(PhpLatex_Node $node);
}
