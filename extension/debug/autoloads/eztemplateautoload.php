<?php

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'extension/debug/debugoperator.php',
                                    'class' => 'debugOperator',
                                    'operator_names' => array( 'debug', 'kill_debug', 'set_sess', 'store_sess') );

?>