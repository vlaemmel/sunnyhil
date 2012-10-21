<?php

$Module = array( 'name' => 'Publish' );

$ViewList = array();

$ViewList['newfollower'] = array(
    'functions' => array(),
    'script' => 'newfollower.php',
    'params' => array()
    );

$ViewList['removefollower'] = array(
    'functions' => array(),
    'script' => 'removefollower.php',
    'params' => array('password_hash')
    );

?>