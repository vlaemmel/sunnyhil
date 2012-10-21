<?php


class ezjscPackerCustomTemplateFunctions
{
    function ezjscPackerTemplateFunctions()
    {
    }

    function operatorList()
    {
        return array(  'customezcss_load'  );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        static $def = null;
        if ( $def === null )
        {
            $def = array( 'customezcss_load' => array( 'css_array' => array( 'type' => 'array',
								                                      'required' => true,
								                                      'default' => array() ),
						                                'media' => array( 'type' => 'string',
						                                      'required' => false,
						                                      'default' => 'all' ),
						                                'type' => array( 'type' => 'string',
						                                      'required' => false,
						                                      'default' => 'text/css' ),
						                                'rel' => array( 'type' => 'string',
						                                      'required' => false,
						                                      'default' => 'stylesheet' ),
						                                'charset' => array( 'type' => 'string',
						                                      'required' => false,
						                                      'default' => 'utf-8' ),
						                                'pack_level' => array( 'type' => 'integer',
						                                      'required' => false,
						                                      'default' => 3 ) ) );
        }
        return $def;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';


        if ( !isset( self::$loaded['css_files'] ) )
        {
            $depend = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, false, true );
            $ret = ezcustomjscPacker::buildStylesheetTag( $depend,
                                                 $namedParameters['media'],
                                                 $namedParameters['type'],
                                                 $namedParameters['rel'],
                                                 $namedParameters['charset'],
                                                 $namedParameters['pack_level'] );
            self::$loaded['css_files'] = true;
        }// let 'ezcss' handle loaded calls
        else
        {
            $namedParameters['css_array'] = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, true, true, true );
        }

        $operatorValue = $ret;
    }

    /**
     * Function for setting values to deal with persistent_variable either from
     * template or internally on {@link self::$persistentVariable}
     *
     * @param string $key Key to store values on
     * @param string|array $value Value(s) to store
     * @param object $tpl Template object to get values from
     * @param bool $append Append or prepend value?
     * @param bool $arrayUnique Make sure array is unique to remove duplicates
     * @param bool $returnArrayDiff Return diff against existing values instead of resulting array
     * $param bool $override Override/Wipe out values or merge?
     * @return array
     * 
     */
    static public function setPersistentArray( $key, $value, $tpl, $append = true, $arrayUnique = false, $returnArrayDiff = false, $override = false )
    {
        $isPageLayout = false;
        $persistentVariable = array();
        if ( $tpl->hasVariable('module_result') )
        {
            $isPageLayout = true;
            $moduleResult = $tpl->variable('module_result');
        }

        if ( isset( $moduleResult['content_info']['persistent_variable'] ) )
        {
            $persistentVariable = $moduleResult['content_info']['persistent_variable'];
        }
        else if ( $tpl->hasVariable('persistent_variable') )
        {
           $persistentVariable = $tpl->variable('persistent_variable');
        }
        else if ( self::$persistentVariable !== null )
        {
            $persistentVariable = self::$persistentVariable;
        }

        if ( !is_array( $persistentVariable ) )
        {
            // Give warning if value is not array as we depend on it
            if ( !$isPageLayout && $persistentVariable )
            {
                eZDebug::writeError( 'persistent_variable was not an array and where cleared, see ezjscore requriments!', __METHOD__ );
            }
            $persistentVariable = array();
        }

        // make a copy in case we need to diff value in the end
        $persistentVariableCopy = $persistentVariable;

        if ( !$override )
        {
            if ( isset( $persistentVariable[ $key ] ) && is_array( $persistentVariable[ $key ] ) )
            {
                if ( is_array( $value ) )
                    $persistentVariable[ $key ] = self::flattenArrayMerge( $persistentVariable[ $key ], $value, $append );
                else if ( $append )
                    $persistentVariable[ $key ][] = $value;
                else
                    $persistentVariable[ $key ] = array_merge( array( $value ), $persistentVariable[ $key ] );
            }
            else
            {
                if ( is_array( $value ) )
                    $persistentVariable[ $key ] = self::flattenArray( $value );
                else
                    $persistentVariable[ $key ] = array( $value );
            }
        }
        else
        {
            $persistentVariable[ $key ] = $value;
        }

        if ( $arrayUnique && isset( $persistentVariable[$key][1] ) )
        {
            $persistentVariable[$key] = array_unique( $persistentVariable[$key] );
        }

        // set the finnished array in the template
        if ( $isPageLayout )
        {
            if ( isset( $moduleResult['content_info']['persistent_variable'] ) )
            {
                $moduleResult['content_info']['persistent_variable'] = $persistentVariable;
                $tpl->setVariable('module_result', $moduleResult );
            }
        }
        else
        {
            $tpl->setVariable('persistent_variable', $persistentVariable );
        }

        // storing the value internally as well in case this is not a view that supports persistent_variable (ezpagedata will look for it)
        self::$persistentVariable = $persistentVariable;

        if ( $returnArrayDiff && isset( $persistentVariableCopy[ $key ][0] ) )
            return array_diff( $persistentVariable[ $key ], $persistentVariableCopy[ $key ] );

        return $persistentVariable[$key];
    }

    /**
     * Merge array2 with array1, but flatten array2 first
     * 
     * @param array $array1
     * @param array $array2
     * @param bool $append Append or Prepend array2 on array1
     * @return array
     */
    static protected function flattenArrayMerge( $array1, $array2, $append = true )
    {
        $array2 = self::flattenArray( $array2 );
        return $append ? array_merge( $array1, $array2 ) : array_merge( $array2, $array1 );
    }

    /**
     * Flatten array so {@link self::setPersistentArray()} is able to proporly make it unique
     * 
     * @param array $array
     * @return array
     */
    static protected function flattenArray( $array )
    {
        $arrayFlatten = array();
        while( isset( $array[0] ) )
        {
             $item = array_shift( $array );
             if ( is_array( $item ) )
                 $array = array_merge( $item, $array );
             else
                 $arrayFlatten[] = $item;
        }
        return $arrayFlatten;
    }

    // reusable function for getting internal persistent_variable
    static public function getPersistentVariable( $key = null )
    {
        if ( $key !== null )
        {
            if ( isset( self::$persistentVariable[ $key ] ) )
                return self::$persistentVariable[ $key ];
            return null;
        }
        return self::$persistentVariable;
    }

    // Internal version of the $persistent_variable used on view that don't support it
    static protected $persistentVariable = null;
    
    // Internal flag for already loaded types
    static protected $loaded = array();
}

?>