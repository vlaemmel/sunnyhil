<?php

$message = '';

if (!array_key_exists($password_hash, $Params)) {
	
	$message = "<h1>Sorry!</h1><p>We could not identify you from the url provided. Please try again!</p><p><a href='/'>back</a></p>";
		
}

$db = eZDB::instance();

$password_hash = $Params['password_hash'];

$query = "select contentobject_id from ezuser where password_hash = '$password_hash'";

$results = $db->arrayQuery($query);

if (count($results) > 0) {
	
	$message = "<h1>Thanks!</h1><p>You've been removed from our notification list!</p><p><a href='/'>back</a></p>";
	
	$user_id = $results[0]['contentobject_id'];
	
	$object = eZContentObject::fetch($user_id);
	ob_start();
	$purge_result = $object->purge();
	ob_end_clean();
		
} else {
	
	$message = "<h1>Sorry!</h1><p>We could not identify you from the url provided. Please try again!</p><p><a href='/'>back</a></p>";
	
}


require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'message', $message );

$Result = array();
$Result['path'] = array( array( 'url' => false,
                                'text' => '/publish/removefollower' ) );
$Result['content'] ='';

$Result['content'] = $tpl->fetch( 'design:newfollower.tpl' );
return $Result;


?>