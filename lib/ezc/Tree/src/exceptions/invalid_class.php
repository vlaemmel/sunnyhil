<?php
/**
 * File containing the ezcTreeInvalidClassException class.
 *
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.3
 * @filesource
 * @package Tree
 */

/**
 * Exception that is thrown when a wrong class is used.
 *
 * @package Tree
 * @version 1.1.3
 */
class ezcTreeInvalidClassException extends ezcTreeException
{
    /**
     * Constructs a new ezcTreeInvalidClassException.
     *
     * @param string $expected
     * @param string $actual
     */
    public function __construct( $expected, $actual )
    {
        parent::__construct( "An object of class '$expected' is used, but an object of class '$actual' is expected." );
    }
}
?>
