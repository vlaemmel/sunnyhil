<?php

	class PageDataOperator
{
	var $Operators;
	
	// Internal version of the $persistent_variable used on view that don't support it
	static protected $persistentVariable=null;

	function PageDataOperator(){
		$this->Operators = array('pagedata','pagedata_set','pagedata_merge');
	}

	function &operatorList(){
		return $this->Operators;
	}

	function namedParameterPerOperator(){
		return true;
	}

	function namedParameterList(){
		return array(
			'pagedata' => array(
				'params' => array('type'=>'array', 'required'=>false, 'default'=>array())
				),
			'pagedata_set' => array(
				'key' => array('type'=>'string', 'required'=>true, 'default'=>false),
				'value' => array('type'=>'mixed', 'required'=>true, 'default'=>false)
				),
			'pagedata_merge' => array(
				'hash' => array('type'=>'array', 'required'=>true, 'default'=>false)
				)
		);
	}

	function modify(&$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters){
		switch($operatorName){
			case 'pagedata_set':{
				self::setPersistentVariable($namedParameters['key'], $namedParameters['value'], $tpl);
				break;
			}
			case 'pagedata_merge':{
				if($namedParameters['hash']){
					foreach($namedParameters['hash'] as $key=>$value){
						self::setPersistentVariable($key, $value, $tpl);
					}
				}
				break;
			}
			default:{
				self::pagedata($tpl, $operatorValue, $namedParameters['params']);
			}
		}
		return true;
	}

	private function pagedata(&$tpl, &$operatorValue, $parameters){
		$pagedata=array();
//		$parameters=$namedParameters['params'];

		$ini=array(
			'content'=>eZINI::instance('content.ini'),
			'error'=>eZINI::instance('error.ini'),
			'layout'=>eZINI::instance('layout.ini'),
			'menu'=>eZINI::instance('menu.ini'),
			'site'=>eZINI::instance('site.ini')
		);

		$CurrentNodeID=0;
		$ModuleParameters=$pagedata['module_parameters']=$GLOBALS['eZRequestedModuleParams'];
		$ModuleResult=$tpl->hasVariable('module_result')?$tpl->variable('module_result'):false;
		$Site=$tpl->hasVariable('site')?$tpl->variable('site'):false;

		// Get persistent_variable
		if(isset($ModuleResult['content_info']['persistent_variable']) && is_array($ModuleResult['content_info']['persistent_variable'])){
			$pagedata['persistent_variable']=$ModuleResult['content_info']['persistent_variable'];
		}else if(self::$persistentVariable!==null){
			$pagedata['persistent_variable']=self::$persistentVariable;
		}else{
			$pagedata['persistent_variable']=array();
		}
		$parameters=array_merge($parameters, $pagedata['persistent_variable']);
		if(empty($pagedata['persistent_variable'])){$pagedata['persistent_variable']=false;}

		// Figure out current node id
		if(isset($parameters['current_node_id'])){
			$CurrentNodeID=(int)$parameters['current_node_id'];
		}else if($tpl->hasVariable('current_node_id')){
			$CurrentNodeID=(int)$tpl->variable('current_node_id');
		}else if(isset($ModuleResult['node_id'])){
			$CurrentNodeID=(int)$ModuleResult['node_id'];
		}else if(isset($ModuleResult['path'][count($ModuleResult['path'])-1]['node_id'])){
			$CurrentNodeID=(int)$ModuleResult['path'][count($ModuleResult['path'])-1]['node_id'];
		}
		// Set current_node_id and current_node variables in the template
		if($CurrentNodeID){
			$CurrentNode=eZContentObjectTreeNode::fetch($CurrentNodeID);
			$tpl->setVariable('current_node_id',$CurrentNodeID);
			$tpl->setVariable('current_node',$CurrentNode);
		}
		$pagedata['class_identifier']=$CurrentNodeID?$CurrentNode->ClassIdentifier:false;

		$UIContext=$tpl->variable('ui_context');
		$URIString=$tpl->variable('uri_string');

		$pagedata['view_mode']=$ViewMode=isset($ModuleParameters['parameters']['ViewMode'])?$ModuleParameters['parameters']['ViewMode']:false;
		$pagedata['is_error']=$ModuleParameters['module_name']=='error';
		$pagedata['is_content']=($ModuleParameters['module_name']=='content' && $ModuleParameters['function_name']!='search' && $ViewMode!='sitemap' && $ViewMode!='tagcloud');
		$pagedata['is_edit']=($UIContext==='edit' && strpos($URIString,'user/edit')===false && (!isset($ModuleResult['content_info']) || strpos($URIString,'content/action')===false));

		$pagedata['node_id']=$CurrentNodeID;
		$pagedata['root_node_id']=(int)$ini['content']->variable('NodeSettings','RootNode');
		$pagedata['homepage']=($pagedata['node_id']==$pagedata['root_node_id'] && $ViewMode!='sitemap' && $ViewMode!='tagcloud');
		$pagedata['show_path']=($pagedata['is_content'] && !$pagedata['homepage']);
		$pagedata['root_node_depth']=0;		// was page_root_depth
		$pagedata['page_depth']=count($ModuleResult['path']);


		// Get custom template_look object. false|eZContentObject (set as parameter from caller)
		if(isset($parameters['template_look'])){
			$pagedata['template_look'] = $parameters['template_look'];
		}else{
			// Get template_look eZContentObject
			if(!isset($pagedata['template_look_class'])){
				$pagedata['template_look_class'] = 'template_look';
			}

			$TemplateLookClassID=eZContentObjectTreeNode::classIDByIdentifier($pagedata['template_look_class']);
			$TemplateLookObjectList=eZContentObject::fetchFilteredList(array('contentclass_id'=>$TemplateLookClassID), 0, 1);

			if($TemplateLookObjectList){
			$pagedata['template_look'] = $TemplateLookObjectList[0];
			}else{
				$pagedata['template_look'] = false;
			}
		}

		if($CurrentNodeID && isset($moduleResult['path'][0]['node_id']) && $moduleResult['path'][0]['node_id']=='2' && $ini['site']->hasVariable('SiteSettings','RootNodeDepth') && $ini['site']->variable('SiteSettings','RootNodeDepth')!=='0'){
			$pagedata['root_node_depth']=$ini['site']->variable('SiteSettings','RootNodeDepth')-1;
			if(isset( $moduleResult['path'][$pagedata['root_node_depth']]['node_id'])){
				$pagedata['root_node']=$moduleResult['path'][$pagedata['root_node_depth']]['node_id'];		
			}
		}

		// See if we should show website toolbar. bool (default: false)
		// Based on both permissions and logged in
		$pagedata['website_toolbar']=false;
		if(isset($parameters['website_toolbar'])){
			$pagedata['website_toolbar'] = $parameters['website_toolbar'];
		}else{
			$CurrentUser=$tpl->hasVariable('current_user')?$tpl->variable('current_user'):eZUser::currentUser();
			if($pagedata['is_content'] && $pagedata['website_toolbar']=$CurrentUser->isLoggedIn() && $CurrentUser->hasAccessTo('websitetoolbar','use')){
				$pagedata['website_toolbar']=!($ViewMode==='sitemap' || $ViewMode==='tagcloud' || $ModuleParameters['function_name']=='versionview' || $pagedata['is_edit']);
			}
		}

		// Default Menu Settings
		$pagedata['sidemenu_position']=self::configSetting($ini['menu'],'MenuSettings','SideMenuPosition','left');
		$HideSideMenuClasses=self::configSetting($ini['menu'],'MenuSettings','HideSideMenuClasses',array());
		$ExtraInfoClasses=self::configSetting($ini['menu'],'MenuContentSettings','ExtraIdentifierList',array());

		// Default Layout Settings
		$FullWidthClassList=self::configSetting($ini['layout'],'FullWidthSettings','ClassList',array());
		$FullWidthModuleList=self::configSetting($ini['layout'],'FullWidthSettings','ModuleList');
		$FullWidthNodeIDList=self::configSetting($ini['layout'],'FullWidthSettings','NodeIDList',array());

		$pagedata['full_width']=(in_array($pagedata['class_identifier'],$FullWidthClassList) || in_array($pagedata['node_id'],$FullWidthNodeIDList) || $pagedata['is_error']);
		if($FullWidthModuleList){
			foreach($FullWidthModuleList as $PathString){
				if(strpos($URIString,$PathString)!==false){
					$pagedata['full_width']=true;
					break;
				}
			}
		}

		// Set Page Title
		$pagedata['title']=ucwords(implode(' / ',array_reverse(explode('/',$URIString))));
		if($pagedata['is_content'] && $CurrentNodeID){
			$CurrentDataMap=$CurrentNode->dataMap();
			if(array_key_exists('meta_title',$CurrentDataMap) && $CurrentDataMap['meta_title']->hasContent()){
				$pagedata['title']=$CurrentDataMap['meta_title']->content();
			}else{
				$tpl->setVariable('text_only',true);
				$tpl->setVariable('reverse',true);
				$pagedata['title']=preg_replace('/\n|\t/s','',$tpl->fetch('design:page_toppath.tpl'));
				$tpl->unsetVariable('text_only');
				$tpl->unsetVariable('reverse');
			}
		}else if($pagedata['is_error']){
			$ErrorCode=self::configSetting($ini['error'],'ErrorSettings-kernel','HTTPError');
			if($ErrorCode){
				$pagedata['title']=self::configSetting($ini['error'],'HTTPError-'.$ErrorCode[$ModuleParameters['parameters']['Number']],'HTTPName');
			}
		}else if($pagedata['view_mode']=='sitemap'){
			$pagedata['title']='Site Map';
		}

		$pagedata['has_sidemenu']=!(in_array($pagedata['class_identifier'],$HideSideMenuClasses) || $pagedata['full_width']);
		$pagedata['has_sidebar']=$pagedata['full_width']?false:(($pagedata['has_sidemenu']&&$pagedata['sidemenu_position']=='left') || !empty($pagedata['persistent_variable']['sidebar']));
		$pagedata['has_extrainfo']=$pagedata['full_width']?false:(($pagedata['has_sidemenu']&&$pagedata['sidemenu_position']=='right') || !empty($pagedata['persistent_variable']['extrainfo']));

		if(!$pagedata['full_width'] && $pagedata['is_content'] && $CurrentNodeID){
			$ExtrainfoItems=eZContentObjectTreeNode::subTreeByNodeID(array('Depth'=>1,'SortBy'=>array('priority',true),'ClassFilterType'=>'include','ClassFilterArray'=>array('infobox')),$CurrentNodeID);
			$CurrentPath=$CurrentNode->pathArray();array_pop($CurrentPath);
			foreach(array_reverse($CurrentPath) as $PathNodeID){
				if($PersistentItems=eZContentObjectTreeNode::subTreeByNodeID(array('Depth'=>1,'SortBy'=>array('priority',true),'ClassFilterType'=>'include','ClassFilterArray'=>array('infobox'),'AttributeFilter'=>array(array('infobox/persistent','=',1))),$PathNodeID)){
					$ExtrainfoItems=array_merge($ExtrainfoItems,$PersistentItems);
				}
			}
			$pagedata['has_extrainfo']=(count($ExtrainfoItems) || $pagedata['has_extrainfo']);
			$tpl->setVariable('infoboxes',$ExtrainfoItems);
		}

		$pagedata['site_classes']=array(
				'pagetype'=>$pagedata['homepage']?'homepage':'subpage',
				'sidebar'=>$pagedata['has_sidebar']?'sidebar':'nosidebar',
				'extrainfo'=>$pagedata['has_extrainfo']?'extrainfo':'noextrainfo',
				'section'=>isset($ModuleResult['section_id'])?'section_id_'.$ModuleResult['section_id']:'no_section',
				'node'=>$CurrentNodeID?'current_node_id_'.$CurrentNodeID:'no_node'
			);
		$pagedata['content_classes']=array_filter(array(
				'module'=>!$pagedata['homepage']?'module':null,
				'content_view'=>$pagedata['view_mode']?'content-view-'.$pagedata['view_mode']:null,
				'class_type'=>$pagedata['is_content']?'class-'.str_replace('_','-',$pagedata['class_identifier']):null
			),'strlen');

		$operatorValue=$pagedata;
	}

	// reusable function for setting persistent_variable
	static public function setPersistentVariable($key, $value, $tpl, $append=false){
		$persistentVariable = array();
		if($tpl->hasVariable('persistent_variable') && is_array($tpl->variable('persistent_variable'))){
			$persistentVariable=$tpl->variable('persistent_variable');
		}else if(self::$persistentVariable!==null && is_array(self::$persistentVariable)){
			$persistentVariable=self::$persistentVariable;
		}
		if($append){
			if(isset($persistentVariable[$key]) && is_array($persistentVariable[$key])){
				$persistentVariable[$key][]=$value;
			}else{
				$persistentVariable[$key]=array($value);
			}
		}else{
			$persistentVariable[$key]=$value;
		}
		// set the finnished array in the template
		$tpl->setVariable('persistent_variable', $persistentVariable);
		// storing the value internally as well in case this is not a view that supports persistent_variable(pagedata will look for it)
		self::$persistentVariable=$persistentVariable;
	}

	// reusable function for getting persistent_variable
	static public function getPersistentVariable($key=null){
		return ($key!==null)?(isset(self::$persistentVariable[$key])?self::$persistentVariable[$key]:null):self::$persistentVariable;
	}

	static private function configSetting($ini,$block,$variable,$default=false){
		return $ini->hasVariable($block,$variable)?$ini->variable($block,$variable):$default;
	}

}

?>
