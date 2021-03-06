<?php
/**
 * File containing the ezcTemplateDefaultAstNode class
 *
 * @package Template
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents a default case control structure.
 *
 * @package Template
 * @version 1.4.1
 * @access private
 */
class ezcTemplateDefaultAstNode extends ezcTemplateCaseAstNode
{
    /**
     * The body element for the default case.
     * @var ezcTemplateBodyAstNode
     */
    public $body;

    /**
     * Initialize with function name code and optional arguments
     *
     * @param ezcTemplateBodyAstNode $body
     */
    public function __construct( ezcTemplateBodyAstNode $body = null )
    {
        ezcTemplateStatementAstNode::__construct();
        $this->body = $body;
    }
}
?>
