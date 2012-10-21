<?php
/**
 * File containing the ezcDocumentWikiFootnoteEndNode struct
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Struct for Wiki document inline footnote end syntax tree nodes
 *
 * @package Document
 * @version 1.2.1
 */
class ezcDocumentWikiFootnoteEndNode extends ezcDocumentWikiInlineNode
{
    /**
     * Set state after var_export
     *
     * @param array $properties
     * @return void
     * @ignore
     */
    public static function __set_state( $properties )
    {
        $nodeClass = __CLASS__;
        $node = new $nodeClass( $properties['token'] );
        return $node;
    }
}

?>
