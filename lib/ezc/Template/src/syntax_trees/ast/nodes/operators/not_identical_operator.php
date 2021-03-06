<?php
/**
 * File containing the ezcTemplateNotIdenticalOperatorAstNode class
 *
 * @package Template
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP not identical operator !==
 *
 * @package Template
 * @version 1.4.1
 * @access private
 */
class ezcTemplateNotIdenticalOperatorAstNode extends ezcTemplateBinaryOperatorAstNode
{

    /**
     *  Check the typehints.
     *  
     *  It doesn't matter which types are used. And we return always a boolean; thus a value.
     */
    public function checkAndSetTypeHint()
    {
        $this->typeHint = self::TYPE_VALUE; 
    }


    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '!==';
    }
}
?>
