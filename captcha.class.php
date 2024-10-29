<?php

    include_once('config.inc.php');

/* Captcha Class */

class Captcha
{
    function randomText($length)
    {
  		//string of all possible CHARACTERS to go into the random Text
                // ( Capital '0' and Zero 0 excluded because of confusion )
 		$availableChars = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnoPpQqRrSsTtUuVvWwXxYyZz123456789";
  
  		//initialize the text
  		$text = "";
  
  		//seed the random function
  		srand();
  
  		//go through to generate a random text of the passed Length
  		for($x=0; $x < $length; $x++)
  		{
                    $text .= substr($availableChars,rand(0,60),1);
  		}
  
  		return $text;
    }
    
    function displayImage($string_length = 5)
    {
                // Session to store the value
		session_start();
		         
                // Header Information
		header("Expires: Sun, 1 Jan 2000 12:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$captcha_text = Captcha::randomText($string_length);
		$_SESSION['captcha'] = md5($captcha_text);
		
		// Creating PNG Image on Fly ! :) to fix the bug of captcha not showing up in previous release
		$captcha_image = imagecreatetruecolor(105, 25);
		imagesavealpha($captcha_image, true);
		$trans_colour = imagecolorallocatealpha($captcha_image, 0, 0, 0, 127);
		imagefill($captcha_image, 0, 0, $trans_colour);
		
                
		imagestring($captcha_image, 5, 3, 3, $captcha_text, imagecolorallocate($captcha_image, 0, 0, 0)); //Writing generated text to the Image
 
		
		header ('Content-type: image/png');
		ImagePNG($captcha_image,NULL,0);
		ImageDestroy($captcha_image);
    }
    
}

?>
