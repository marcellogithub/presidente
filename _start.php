<?php	session_start();		ini_set( 'memory_limit', '100M' ); // per resize di immagini	ini_set( 'upload_max_filesize', '100M' ); // Maximum allowed size for uploaded files // per caricare media	ini_set( 'post_max_filesize', '100M' ); // Maximum size of POST data that PHP will accept // per caricare media	ini_set( 'max_execution_time', '900' ); // Maximum execution time of each script, in seconds // 15 minuti	ini_set( 'max_input_time', '900' ); // Maximum amount of time each script may spend parsing request data //15 minuti	set_time_limit(900); // 15 minuti		date_default_timezone_set('Europe/Rome');//	ini_set( 'log_errors', 'Off' ); // 	ini_set( 'log_errors', 'On' ); // 	ini_set("error_log", "php-error.log");//	error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED) );	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED) );//	error_reporting(E_ALL  );	ini_set('display_errors', '1');	// variabili in _REQUEST scritte comode	foreach( $_REQUEST as $key =>$val) { $$key=$val; }; 	function ermeg		($pattern, $subject, &$matches="") { return preg_match('/'.$pattern.'/', $subject, $matches); } 	function ermegi		($pattern, $subject, &$matches="") { return preg_match('/'.$pattern.'/i', $subject, $matches); } 	function ermeg_replace	($pattern, $replacement, $string) { return preg_replace('/'.$pattern.'/', $replacement, $string); } 	function ermegi_replace	($pattern, $replacement, $string) { return preg_replace('/'.$pattern.'/i', $replacement, $string); } 	function split_m		($pattern, $subject, $limit) { return preg_split('/'.$pattern.'/', $subject, $limit); } 	function spliti_m		($pattern, $subject, $limit) { return preg_split('/'.$pattern.'/i', $subject, $limit); } function pprint_r($cosa){	// stampa con <pre>	echo "<pre>"; print_r($cosa); echo "</pre>";}