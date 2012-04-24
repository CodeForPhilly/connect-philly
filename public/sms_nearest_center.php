<?php
require_once( 'inc.bootstrap.php' );

Connect_SMS_Application::run( file_get_contents('php://input') );
