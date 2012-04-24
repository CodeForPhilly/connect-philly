<?php
require_once( 'inc.bootstrap.php' );

$request_url = $_SERVER['REQUEST_URI'];

//forward to tphilly wordpress on an index request
if( preg_match(  '/^\/$/', $request_url ) 
        || preg_match(  '/\/index.php/', $request_url ) 
        ) {
    $application->getBootstrap()->setForwardLayout();
}

$application->run();