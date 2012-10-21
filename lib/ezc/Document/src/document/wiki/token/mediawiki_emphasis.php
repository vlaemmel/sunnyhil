<?php
/**
 * File containing the ezcDocumentWikiMediawikiEmphasisToken struct
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Struct for Wiki document emphasis markup, especially for mediawiki, because
 * here it can only be decided during the actual parsing, if this is a strong
 * or normal emphasis.
 *
 * @package Document
 * @version 1.2.1
 * @access private
 */
class ezcDocumentWikiMediawikiEmphasisToken extends ezcDocumentWikiInlineMarkupToken
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
        $tokenClass = __CLASS__;
        $token = new $tokenClass(
            $properties['content'],
            $properties['line'],
            $properties['position']
        );

        // Set additional token values
        // $token->value = $properties['value'];

        return $token;
    }
}

?>
