<?php
/**
 * File containing the ezcTemplateSubtractionAssignmentOperatorAstNode class
 *
 * @package Template
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP subtraction assignment operator -=
 *
 * @package Template
 * @version 1.4.1
 * @access private
 */
class ezcTemplateSubtractionAssignmentOperatorAstNode extends ezcTemplateAssignmentOperatorAstNode
{
    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '-=';
    }
}
?>
