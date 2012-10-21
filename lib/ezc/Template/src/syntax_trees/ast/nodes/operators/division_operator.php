<?php
/**
 * File containing the ezcTemplateDivisionOperatorAstNode class
 *
 * @package Template
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP division operator /
 *
 * @package Template
 * @version 1.4.1
 * @access private
 */
class ezcTemplateDivisionOperatorAstNode extends ezcTemplateBinaryOperatorAstNode
{
    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '/';
    }
}
?>