<?php

/* Form Mail Function */

	include_once('captcha.class.php') ; 
	include_once('config.inc.php') ;

	session_start();

	if($_SESSION["captcha"] == md5($_POST["captcha"]))
	{
		 mail($CONFIG_BOXMAIL['admin_email'], $CONFIG_BOXMAIL['email_subject'],
         "Name : ".$_POST['fullname']."\n".
         "Email : ".$_POST['email']."\n".
         "Message : ".$_POST['message'] ) or die('There was an error sending the mail');
		  
    	echo $CONFIG_BOXMAIL['thank_you'] ;
		
		if($CONFIG_BOXMAIL['mail_back'] == "TRUE")
		{
			mail($_POST['email'], "Thank you ".$_POST['fullname']." for mail",$CONFIG_BOXMAIL['mail_back_message']);
		}
	}
	else
	{
		echo "Sorry the Captcha is not Valid !";
		exit;
	}
	

	session_destroy();
?>