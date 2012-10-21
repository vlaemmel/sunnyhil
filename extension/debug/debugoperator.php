<?php

class debugOperator
{
    var $Operators;

    function debugOperator()
    {
	$this->Operators = array( "debug", "kill_debug", "set_sess", "store_sess" );
    }


    function operatorList()
    {
	return $this->Operators;
    }


    function namedParameterPerOperator()
    {
        return true;
    }   

    function namedParameterList()
    {
        return array( 'debug' => array(), 'kill_debug' => array(), 'set_sess' => array(), 'store_sess' => array() );
    }


    function modify( $tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {

        switch ( $operatorName )
        {
            case 'kill_debug':
            {
		$GLOBALS['eZDebugEnabled'] =0;
		$operatorValue = '';
		return true;
            } 
            break;

            case 'store_sess':
            {
		$_SESSION[$operatorValue[0]] = $operatorValue[1];
		$operatorValue = '';
		return true;
            } 
            break;

            case 'set_sess':
            {
		$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$code = '';
		for ($i = 1; $i < 7; $i++)
	    {
	        $r = $chars{rand(0, (strlen($chars) - 1))};
	        $code .=  $r;
	    }
		$operatorValue = $code;
		return true;
            } 
            break;

            case 'debug':
            {
		eZDebug::writeDebug( $operatorValue );
		$operatorValue = '';
		return true;
            } 
            break;

	}

    }
}

?>
