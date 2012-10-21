<?php
/**
 * File containing the ezcDocumentXhtmlBlockquoteElementFilter class
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Filter for XHtml blockquotes and blockquote attributions
 *
 * The sematic meaning of the cite XHtml element is sometimes referenced as
 * blockquote attribution, and sometimes as inline quotes. We decide its
 * meaning depending on the parent node type.
 *
 * @package Document
 * @version 1.2.1
 * @access private
 */
class ezcDocumentXhtmlBlockquoteElementFilter extends ezcDocumentXhtmlElementBaseFilter
{
    /**
     * Filter a single element
     *
     * @param DOMElement $element
     * @return void
     */
    public function filterElement( DOMElement $element )
    {
        if ( ( $element->parentNode->tagName === 'blockquote' ) ||
             // This is a special filter for the markup generated by the RST to
             // HTML conversion
             ( ( $element->parentNode->tagName === 'div' ) &&
               ( $element->parentNode->hasAttribute( 'class' ) ) &&
               ( strpos( $element->parentNode->getAttribute( 'class' ), 'attribution' ) !== false ) ) )
        {
            // The attribution is required to be the first element in a
            // blockquote element, so we move this to the front.
            $cloned = $element->parentNode->cloneNode( true );
            $element->parentNode->parentNode->insertBefore( $cloned, $element->parentNode->parentNode->firstChild->nextSibling );

            // Assume this is an attribution.
            $cloned->setProperty( 'type', 'attribution' );
            $element->parentNode->parentNode->removeChild( $element->parentNode );
        }
        elseif ( !$this->isInline( $element ) )
        {
            $element->setProperty( 'type', 'blockquote' );
        }
        else
        {
            $element->setProperty( 'type', 'quote' );
        }
    }

    /**
     * Check if filter handles the current element
     *
     * Returns a boolean value, indicating whether this filter can handle
     * the current element.
     *
     * @param DOMElement $element
     * @return void
     */
    public function handles( DOMElement $element )
    {
        return ( $element->tagName === 'cite' );
    }
}

?>
