<?php
/**
 * File containing the literal layout handler
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visit literallayout elements
 *
 * Literallayout elements are used for code blocks in docbook, where
 * normally some fixed width font is used, but also for poems or simliarly
 * formatted texts. In HTML those are represented by entirely different
 * structures. Code blocks will be transformed into <pre> elements, while
 * poem like texts will be handled by a <p> element, in which each line is
 * seperated by <br> elements.
 *
 * @package Document
 * @version 1.2.1
 */
class ezcDocumentDocbookToRstLiteralLayoutHandler extends ezcDocumentDocbookToRstBaseHandler
{
    /**
     * Handle a node
     *
     * Handle / transform a given node, and return the result of the
     * conversion.
     *
     * @param ezcDocumentElementVisitorConverter $converter
     * @param DOMElement $node
     * @param mixed $root
     * @return mixed
     */
    public function handle( ezcDocumentElementVisitorConverter $converter, DOMElement $node, $root )
    {
        if ( !$node->hasAttribute( 'class' ) ||
             ( $node->getAttribute( 'class' ) !== 'normal' ) )
        {
            $root .= "\n::\n\n    " . preg_replace( '(\r\n|\r|\n)', "\n    ", $node->textContent ) . "\n";
        }
        else
        {
            $root .= "\n| " . preg_replace( '(\r\n|\r|\n)', "\n| ", $node->textContent ) . "\n";
        }

        return $root;
    }
}

?>
