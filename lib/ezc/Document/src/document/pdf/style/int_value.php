<?php
/**
 * File containing the ezcDocumentPdfStyleIntValue class
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Pdf CSS layout directive.
 *
 * @package Document
 * @access private
 * @version 1.2.1
 */
class ezcDocumentPdfStyleIntValue extends ezcBaseStruct
{
    /**
     * Directive value
     *
     * @var int
     */
    public $value;

    /**
     * Construct value handler from string representation
     *
     * @param mixed $value
     * @return void
     */
    public function __construct( $value )
    {
        $this->value = (int) $value;
    }

    /**
     * Convert value to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $value;
    }
}
?>
