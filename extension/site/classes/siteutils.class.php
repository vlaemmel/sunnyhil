<?php


class SiteUtils
{

	function SiteUtils()
	{
	}

	static function templateOperators($AutoloadsExt){
		$TemplateOperators = array();
		foreach(eZDir::findSubitems($OperatorsPath = "$AutoloadsExt/operators") as $OperatorFile){
			$ClassList = SiteUtils::phpFileClassList("$OperatorsPath/$OperatorFile");
			$OperatorArray = array(
				'script'=>"$OperatorsPath/$OperatorFile",
				'class'=>$ClassList[0]
			);
			$OperatorList = array();
			foreach(array($OperatorArray['class']) as $ClassName){
				$ClassObject = new $ClassName();
				$OperatorList = array_merge($OperatorList,$ClassObject->operatorList());
			}
			$OperatorArray = array_merge($OperatorArray, array('operator_names'=>$OperatorList));
			array_push($TemplateOperators,$OperatorArray);
		}
		return $TemplateOperators;
	}

	static function phpFileClassList($filename){
		$classes = array();
		$tokens = token_get_all(file_get_contents($filename));
		$count = count($tokens);
		for($i = 2; $i < $count; $i++){
			if($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING){
				$class_name = $tokens[$i][1];
				$classes[] = $class_name;
			}
		}
		return $classes;
	}

	static function configSetting($blockName, $varName, $fileName='sitelink.ini', $rootDir='settings', $directAccess=false){
		$ini = eZINI::instance($fileName, $rootDir, null, null, null, $directAccess);
		return ($ini->hasSection($blockName)&&$ini->hasVariable($blockName, $varName))?$ini->variable($blockName,$varName):false;
	}
	
	static function configSettingBlock($blockName, $fileName='sitelink.ini', $rootDir='settings', $directAccess=false){
		$ini = eZINI::instance($fileName, $rootDir, null, null, null, $directAccess);
		return $ini->hasGroup($blockName)?$ini->group($blockName):false;
	}

	static function hasConfigSetting($blockName, $varName, $fileName, $rootDir='settings', $directAccess=false){
		$ini = eZINI::instance($fileName, $rootDir, null, null, null, $directAccess);
		if($ini->hasSection($blockName) && $ini->hasVariable($blockName, $varName)){
			return self::configSetting($blockName, $varName, $fileName, $rootDir, $directAccess);
		}
		return false;
	}

}


function array_update(&$array, $value, $key=false){
	if(!$array && !is_array($array)){$array=array();}
	if($key){
		if(array_key_exists($key, $array)){
			array_push($array[$key],$value);
		} else{
			$array = array_merge($array, array($key=>array($value)));
		}
	} else{array_push($array,$value);}
}

function array_assoc($array, $delimiter, $key=0, $value=1){
	$MainArray = array();
	if(is_array($array)){
		foreach($array as $element){
			$elementArray = explode($delimiter,$element);
			if(!array_key_exists($elementArray[$key],$MainArray)){$MainArray[$elementArray[$key]] = $elementArray[$value];}
		}
	}
	return $MainArray;
}


?>