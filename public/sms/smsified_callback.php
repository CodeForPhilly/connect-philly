<?php
require_once( '../inc.bootstrap.php' );

Connect_SMS_CallbackApplicaton::processCallback( $_GET["attemptNum"],
                                        file_get_contents('php://input') );
