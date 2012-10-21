<?php

class html_shortenOperator
{
    var $Operators;

    function html_shortenOperator( $name = "html_shorten" )
    {
	$this->Operators = array( $name );
    }

    /*! Returns the template operators.
    */
    function &operatorList()
    {
	return $this->Operators;
    }


    function namedParameterPerOperator()
    {
        return true;
    }   

    function namedParameterList()
    {
        return array( 'html_shorten' => array( 'first_param' => array( 'type' => 'numeric',
                                                                       'required' => false,
                                                                       'default' => 50 ), 
							     'second_param' => array( 'type' => 'string',
												'required' => false,
												'default' => '...') ) );
    }


    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
			$store_safe = $operatorValue;
			$store_safe = preg_replace("/<img([^>]?)*>/", '&nbsp;', $store_safe);
		    $save_temp = $store_safe;
		    $save_temp2 = $store_safe;
		    preg_match_all("/<[^>]*?>/", $save_temp, $tag_matches);

		    if ($namedParameters['first_param'] == 0) {
			if(count($tag_matches[0])) {
			    $save_temp3 = preg_replace("/<p>(&nbsp;|\s)*?<\/p>/", "", $save_temp2);
			    $out = preg_replace("/(<[^>]*?>)(\s*)?$/", $namedParameters['second_param'].'$1$2', $save_temp3);
			} else {
			    $out = preg_replace("/(\S)(\s*?)$/", '$1'.$namedParameters['second_param'].'$2', $save_temp2);
			}
			$operatorValue = $out;
			return true;
		    } else {
		    $resultstring='';
		    $opentags = array();
		    $max_length = $namedParameters['first_param'];
		    $endstring = $namedParameters['second_param'];
		    $cropped=false;
		    foreach ($tag_matches[0] as $key => $this_tag_match) {
				$pos = strpos($save_temp, $this_tag_match);
				$lbit = substr($save_temp, 0, $pos);
				$rbit = substr($save_temp, $pos + strlen($this_tag_match));
			
		       $splitarray = array($lbit, $rbit);
			$tagtype = preg_replace("/ .*\/?>/", ">", $this_tag_match);

			$tagtypeopen = preg_replace("/\//", "", $tagtype);
			if (strlen($splitarray[0])  > $max_length && !$cropped) {
			   $word_end = strpos($splitarray[0], ' ', $max_length);
			   if (!$word_end) $word_end = strlen($splitarray[0]);
			   $resultstring .= substr($splitarray[0], 0, $word_end); 
			   $cropped=true;
			   $resultstring .= $endstring;

			} else {
			   if (!$cropped) {
				$resultstring .= $splitarray[0];
				if ( strpos($tagtype, '/') === false ) {
					$directional = 1;
				} elseif (strpos($tagtype, '/') == 1) {
					$directional = -1;
				} else {
					$directional = 0;
					}
				$tagtypeopen = preg_replace("/\//", "", $tagtype);
				if (array_key_exists($tagtypeopen, $opentags) ) {
					$opentags[$tagtypeopen] = $opentags[$tagtypeopen] + $directional;
				} else {
					$opentags[$tagtypeopen] = $directional;
				}
			   }
			   $max_length = $max_length - strlen($splitarray[0]);
			}
			if (!$cropped) {
			   $resultstring .= $this_tag_match;
			} else {
			   if (strpos($tagtype, '/') == 1 && array_key_exists($tagtypeopen, $opentags) && $opentags[$tagtypeopen] > 0) {
				$resultstring .= $tagtype;
				$opentags[$tagtypeopen] = $opentags[$tagtypeopen] -1;
			   }
			}
			$save_temp = (count($splitarray)>1)?$splitarray[1]:'';
		    }

		    if (count($tag_matches[0]) == 0) {
			if (strlen($save_temp2)  > $max_length) {
			   $word_end = strpos($save_temp2, ' ', $max_length);
			   $resultstring .= substr($save_temp2, 0, $word_end); 
			   $cropped=true;
			   $resultstring .= $endstring;
		    	} else {
			   $resultstring = $save_temp2;
		       }
		    }

		    $operatorValue = $resultstring;  

		    }
    }
}

?>
