<?php
/**
 * File containing the options class for the ezcDocumentRst class
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Class containing the basic options for the ezcDocumentRst
 *
 * @property string $xhtmlVisitor
 *           Classname of the XHTML visitor to use.
 * @property string $xhtmlVisitorOptions
 *           Options class conatining the options of the XHtml visitor.
 *
 * @package Document
 * @version 1.2.1
 */
class ezcDocumentRstOptions extends ezcDocumentOptions
{
    /**
     * Constructs an object with the specified values.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if $options contains a property not defined
     * @throws ezcBaseValueException
     *         if $options contains a property with a value not allowed
     * @param array(string=>mixed) $options
     */
    public function __construct( array $options = array() )
    {
        $this->properties['xhtmlVisitor']        = 'ezcDocumentRstXhtmlVisitor';
        $this->properties['xhtmlVisitorOptions'] = new ezcDocumentHtmlConverterOptions();

        parent::__construct( $options );
    }

    /**
     * Sets the option $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name is not defined
     * @throws ezcBaseValueException
     *         if $value is not correct for the property $name
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'xhtmlVisitor':
                if ( !is_string( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value, 'classname' );
                }

                $this->properties[$name] = $value;
                break;

            case 'xhtmlVisitorOptions':
                if ( !is_object( $value ) &&
                     ( $value instanceof ezcDocumentHtmlConverterOptions ) )
                {
                    throw new ezcBaseValueException( $name, $value, 'instanceof ezcDocumentHtmlConverterOptions' );
                }

                $this->properties[$name] = $value;
                break;

            default:
                parent::__set( $name, $value );
        }
    }
}

?>
