<?php
/**
 * File containing the ezcTemplateLogicalNegationOperatorAstNode class
 *
 * @package Template
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP logical negation operator !
 *
 * @package Template
 * @version 1.4.1
 * @access private
 */
class ezcTemplateLogicalNegationOperatorAstNode extends ezcTemplateOperatorAstNode
{
    /**
     * Initialize operator code constructor with 1 parameter (unary) and flag as pre-operator.
     *
     * @param ezcTemplateAstNode $parameter The code element to use as first parameter.
     */
    public function __construct( ezcTemplateAstNode $parameter = null )
    {
        parent::__construct( self::OPERATOR_TYPE_UNARY, true );
        if ( $parameter )
        {
            $this->appendParameter( $parameter );
        }
    }

    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '!';
    }
}
?>