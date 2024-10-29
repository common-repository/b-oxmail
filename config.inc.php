<?php
				
							// Configuration Values. You can edit it manually or through admin.php file 
							global $CONFIG_BOXMAIL;
							$CONFIG_BOXMAIL = array();							
							
							// Mail Settings 
							$CONFIG_BOXMAIL['admin_email'] = "ankit.cs@gmail.com" ;
							$CONFIG_BOXMAIL['email_subject'] = "[Mail from http://ankitaggarwal.me]" ;
							$CONFIG_BOXMAIL['thank_you'] = "Thank You. Your mail has been submitted" ;
							$CONFIG_BOXMAIL['mail_back'] = "TRUE" ;
							$CONFIG_BOXMAIL['mail_back_message'] = "Your mail has been recieved. Thank you." ;
							
							// Frontend Settings
							$CONFIG_BOXMAIL['captcha_length'] = 7 ;
							$CONFIG_BOXMAIL['initial_message_value'] = "Enter the message and shoot me a mail....." ;
							$CONFIG_BOXMAIL['button_name'] = "Shoot" ;
							$CONFIG_BOXMAIL['captcha_message'] = "Prove that humans are smarter than a Computer" ;
							
							
							?>