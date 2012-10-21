<?php
/**
 * File containing a dummy link converter
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Class for conversions of links, given as natural URLs into the eZXml storage
 * format, which may result in urlIds, nodeIds, or similar.
 *
 * @package Document
 * @version 1.2.1
 */
class ezcDocumentEzXmlDummyLinkConverter extends ezcDocumentEzXmlLinkConverter
{
    /**
     * Get URL properties
     *
     * Return an array of the attributes, which should be set for the link.
     *
     * @param string $url
     * @return array
     */
    public function getUrlProperties( $url )
    {
        return array(
            'url_id' => 23,
        );
    }
}

?>
