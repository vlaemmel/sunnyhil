<?php


$message = '';

if (!array_key_exists('email', $_POST)) {
	
	$message = "<h1>Sorry!</h1><p>You need to enter a valid email address. Please try again!</p><p><a href='/'>back</a></p>";
}

$db = eZDB::instance();

$email = $_POST['email'];

$query = "select count(email) as e_c from ezuser where email = '$email'";

$results = $db->arrayQuery($query);

if ($results[0]['e_c'] > 0) {
	
	$message = "<h1>Sorry!</h1><p>That email address is already in our system! Please try again!</p><p><a href='/'>back</a></p>";
	
}

$email_r = explode('@', preg_replace("/[^a-zA-Z@]/", "", $email));

$fname = $email_r[0];
$lname = $email_r[1];

$new_daata = array('first_name' => $fname, 'last_name' => $lname, 'email' => $email);

if ($message == '') {

	$message = "<h1>Thanks!</h1><p>You have been added to the site's notification list!</p><p><a href='/'>back</a></p>";
	make_node($new_daata, $db, 2533, 4, $email);

} 

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'message', $message );

$Result = array();
$Result['path'] = array( array( 'url' => false,
                                'text' => '/publish/newfollower' ) );
$Result['content'] ='';

$Result['content'] = $tpl->fetch( 'design:newfollower.tpl' );
return $Result;


function make_node($new_daata, $db, $parent_id, $class_id, $page_name) {

	//EZ SETTINGS

	$user_id = 14;

	// CREATE EZ OBJECT

	$parent = eZContentObjectTreeNode::fetch($parent_id);

	$object = $parent->object();

	$remoteID = "staticpagesearchobject_".$page_name;

	$class = eZContentClass::fetch( $class_id );

	// Create object by user id in the section of the parent object
	$contentObject = $class->instantiate( $user_id, $object->attribute( 'section_id' ) );
	$contentObject->setAttribute('remote_id', $remoteID );
	$contentObject->setAttribute( 'name', $page_name );

	$nodeAssignment = eZNodeAssignment::create( array(
	                                                 'contentobject_id' => $contentObject->attribute( 'id' ),
	                                                 'contentobject_version' => $contentObject->attribute( 'current_version' ),
	                                                 'parent_node' => $parent->attribute( 'node_id' ),
	                                                 'sort_field' => 2,
	                                                 'sort_order' => 0,
	                                                 'is_main' => 1
	                                                 )
	                                             );
	$nodeAssignment->store();

	$version = $contentObject->version( 1 );

	$contentObjectID = $contentObject->attribute( 'id' );

	$current_version = 1;

	$dataMap = $version->dataMap();

	foreach ($dataMap as $setme => $objectAttribute) {
		
		if (!array_key_exists($setme, $new_daata)) continue;
		
		$value = $new_daata[$setme];
		
		if ($value) {

			$dataType = $objectAttribute->attribute( 'data_type_string' );

			switch( $dataType )
			{
				
		  	 case 'ezboolean':
			  {
			      $objectAttribute->setAttribute( 'data_int', $value ? 1 : 0 );
			  } break;
			
			  case 'ezxmltext':
			  {
			      setEZXMLAttribute( $objectAttribute, $value );
			  } break;

			  case 'ezurl':
			  {
			      $objectAttribute->setContent( $value );
			  } break;

			  case 'ezkeyword':
			  {
			      $keyword = new eZKeyword();
			      $keyword->initializeKeyword( $value );
			      $objectAttribute->setContent( $keyword );
			  } break;

			  case 'ezdate':
			  {
			      $timestamp = strtotime( $value );
			      if ( $timestamp )
			          $objectAttribute->setAttribute( 'data_int', $timestamp );
			  } break;

			  case 'ezdatetime':
			  {
			      $objectAttribute->setAttribute( 'data_int', strtotime($value) );
			  } break;
			
			  case 'ezobjectrelation':
			  {
			      $objectAttribute->setAttribute( 'data_int', strtotime($value) );
			  } break;
			
			  case 'ezbinaryfile':
			  {

			      $status = $objectAttribute->insertRegularFile( $contentObject, $contentObject->attribute( 'current_version' ), eZContentObject::defaultLanguage(), $value, $storeResult );
					
				  if ( $storeResult['require_storage'] ) $dataMap[$fileAttribute]->store();
			  } break;

			  default:
			  {
			      $objectAttribute->setAttribute( 'data_text', $value );
			  } break;
			}

			$objectAttribute->store();
		
		}

	}
	
	$email = $new_daata['email'];
	
	$email_r = explode('@', preg_replace("/[^a-zA-Z@]/", "", $email));

	$username = $email_r[0].$email_r[1];
	$password = 'follower';
	
	$user = new eZUser( $contentObjectID );

	$user->setAttribute('login', $username );
	$user->setAttribute('email', $email);

	$hashType = eZUser::hashType() . "";
	$newHash = $user->createHash( $username, $password, eZUser::site(), $hashType );

	$user->setAttribute( "password_hash_type", $hashType );
	$user->setAttribute( "password_hash", $newHash );

	$user->store();

	$operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
	                                                                             'version' => $current_version ) );

	// END EZ OBJECT CREATION
	
	
	$nodeIDList = eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );
	if ( !in_array( 2, $nodeIDList ) )
	{
	    $rule = eZSubtreeNotificationRule::create( 2, $user->attribute( 'contentobject_id' ) );
	    $rule->store();
	    $alreadyExists = false;
	}
	
	return true;
	
}

function setEZXMLAttribute( $attribute, $attributeValue, $link = false )
{
    $contentObjectID = $attribute->attribute( "contentobject_id" );
    $parser = new eZSimplifiedXMLInputParser( $contentObjectID, false, 0, false );

    $attributeValue = str_replace( "\r", '', $attributeValue );
    $attributeValue = str_replace( "\n", '', $attributeValue );
    $attributeValue = str_replace( "\t", ' ', $attributeValue );

    $document = $parser->process( $attributeValue );
    if ( !is_object( $document ) )
    {
        $cli = eZCLI::instance();
        $cli->output( 'Error in xml parsing' );
        return;
    }
    $domString = eZXMLTextType::domString( $document );

    $attribute->setAttribute( 'data_text', $domString );
    $attribute->store();
}

?>