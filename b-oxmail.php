<?php 
/*
Plugin Name: B-oxmail
Plugin URI: http://boxmail.ankitaggarwal.me
Description: Adds an AJAX based E-mail form in the sidebar. <a href="options-general.php?page=b-oxmail">Settings Page</a>
Version: 0.2.2
Author: Ankit Aggarwal
Author URI: http://ankitaggarwal.me
*/

add_action( 'widgets_init', 'load_widgets' );

function load_widgets() {
	register_widget( 'Boxmail_Widget' );
}

/**
 * Boxmail Widget class.
 */
class Boxmail_Widget extends WP_Widget {
	
	// Widget Settings
	function Boxmail_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'boxmail', 'description' => __('Adds an AJAX based E-mail form in the sidebar', 'boxmail') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 160, 'height' => 200, 'id_base' => 'boxmail-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'boxmail-widget', __('Boxmail Widget', 'boxmail'), $widget_ops, $control_ops );
	}

	// Displaying Widget on Screen
	function widget( $args, $instance ) {
		extract( $args );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
                        
                /* Actual widget starts from here */
				/**********************************/
                ?>
                
                <?php include_once(WP_PLUGIN_DIR.'/b-oxmail/config.inc.php'); ?>
				
				<script language="JavaScript">
				var currentLayer = 'page1';
				function showLayer(lyr){
					hideLayer(currentLayer);
					document.getElementById(lyr).style.visibility = 'visible';
					currentLayer = lyr;
				}

				function hideLayer(lyr){
				document.getElementById(lyr).style.visibility = 'hidden';
				}

				function ajaxform(thisform,formhandler)
				{

    				// Set up data variable
    				var formdata = "";

    				// Set up Ajax request variable
    				try {xmlhttp = window.XMLHttpRequest?new XMLHttpRequest(): new ActiveXObject("Microsoft.XMLHTTP");}  catch (e) { alert("Error: Could not load page.");}

    				// Loop through form fields
    				for (i=0; i < thisform.length; i++)
    				{
         				//Build Send String
         				if(thisform.elements[i].type == "text"){ //Handle Textbox's
                  			formdata = formdata + thisform.elements[i].name + "=" + escape(thisform.elements[i].value) + "&";
         				}else if(thisform.elements[i].type == "textarea"){ //Handle textareas
                  			formdata = formdata + thisform.elements[i].name + "=" + escape(thisform.elements[i].value) + "&";
         				}else if(thisform.elements[i].type == "checkbox"){ //Handle checkbox's
                 			formdata = formdata + thisform.elements[i].name + "=" + thisform.elements[i].checked + "&";
         				}else if(thisform.elements[i].type == "radio"){ //Handle Radio buttons
                  			if(thisform.elements[i].checked==true){
                     				formdata = formdata + thisform.elements[i].name + "=" + thisform.elements[i].value + "&";
                  			}
         				}else{
                  			//finally, this should theoretically this is a select box.
                  			formdata = formdata + thisform.elements[i].name + "=" + escape(thisform.elements[i].value) + "&";
         				}
    				}

    				//Send Ajax Request
    				xmlhttp.onreadystatechange = function(){
               
			   		//Check page is completed and there were no problems.
               		if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)) {
                      //What to do once the form is submitted - to inform the user.
                      document.getElementById("boxmail").innerHTML = xmlhttp.responseText;
               			}
    				}
    
	
					//Make connection
    				xmlhttp.open("POST", formhandler);

					//Set Headers
    				xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
					//Send data
    				xmlhttp.send(formdata);
    
					//stops form from submitting normally
    				return false;
			}


			</script>
			<style>
			.page{
			position: absolute;
			visibility: hidden;
			}

			</style>
			<link href="<?php echo WP_PLUGIN_URL.'/b-oxmail/'; ?>style.css" rel="stylesheet" type="text/css">
			<div id="boxmail" style="width:160px;height:200px;">
			<form id="BoxForm" method="post" action="<?php echo WP_PLUGIN_URL.'/b-oxmail/'; ?>mail.php" onSubmit="return ajaxform(this,'<?php echo WP_PLUGIN_URL.'/b-oxmail/'; ?>mail.php')">

			<div id="page1" class="page" style="visibility:visible;">
  			<label>
    			<input name="fullname" type="text" id="BoxFormField" value="Name..." size="18" onClick="this.value=''">
 			</label>
  			<br>
  			<label>
    			<input name="email" type="text" id="BoxFormField" value="E-mail id..." size="18" onClick="this.value=''">
  			</label>
  			<br>
  			<label>
    			<textarea name="message" id="BoxFormField" cols="16" rows="5" onFocus="this.value=''; return false;" ><?php echo $CONFIG_BOXMAIL['initial_message_value']; ?>
    			</textarea>
  			</label>
  			<br>
  			<label>
    			<input type="button" id="C1" class="BoxMailButton" value="Continue" onClick="showLayer('page2')">
  			</label>
			</div>

			<div id="page2" class="page" style="width:160px;">
			<?php echo $CONFIG_BOXMAIL['captcha_message']."<br />"; ?><br />

			<img src="<?php echo WP_PLUGIN_URL.'/b-oxmail/'; ?>image.php" alt="Captcha Image" id="captcha-img" /><br>

  			<label>
    			<input type="text" size="18" name="captcha" id="BoxFormField" />
  			</label>

  			<p><input type="button" id="B1" class="BoxMailButton"  value="Go Back" onClick="showLayer('page1')"><input type="submit" id="submit" class="BoxMailButton" value="<?php echo $CONFIG_BOXMAIL['button_name']; ?>"></p>
			</div>

			</form>
			</div>

<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	// Update Widget Settings
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	// Inputting Title value from User
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Boxmail', 'boxmail') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>		

  <?php
	}
}

add_action('admin_menu', 'boxmail_menu');

function boxmail_menu() {
  add_options_page('Boxmail Options', 'Boxmail Settings', 8, 'b-oxmail', 'boxmail_options');
}

function boxmail_options() {
    
	// Admin Options Menu Starts from Here
	?>
          
  	<?php

	
	if(@$_REQUEST['update'] == 1 )  // check if the form has been updated or not
	{

				$file_to_write = WP_PLUGIN_DIR.'/b-oxmail/config.inc.php';   // Configuration File to be written
				$css_file_to_write = WP_PLUGIN_DIR.'/b-oxmail/style.css' ;   // CSS File to be written
				
				$admin_email = $_POST['admin_email'];
				$email_subject = $_POST['email_subject'];
				$thank_you = $_POST['thank_you'];
				$mail_back = isset($_POST['mail_back']) ? "TRUE" : "FALSE";
				$mail_back_message = $_POST['mail_back_message'];
				$captcha_length = $_POST['captcha_length'];
				$initial_message_value = $_POST['initial_message_value'];
				$button_name = $_POST['button_name'];
				$captcha_message = $_POST['captcha_message'];
				
				$css_style = $_POST['css_style'];

				$content ="<?php
				
							// Configuration Values. You can edit it manually or through admin.php file 
							global \$CONFIG_BOXMAIL;
							\$CONFIG_BOXMAIL = array();							
							
							// Mail Settings 
							\$CONFIG_BOXMAIL['admin_email'] = \"$admin_email\" ;
							\$CONFIG_BOXMAIL['email_subject'] = \"$email_subject\" ;
							\$CONFIG_BOXMAIL['thank_you'] = \"$thank_you\" ;
							\$CONFIG_BOXMAIL['mail_back'] = \"$mail_back\" ;
							\$CONFIG_BOXMAIL['mail_back_message'] = \"$mail_back_message\" ;
							
							// Frontend Settings
							\$CONFIG_BOXMAIL['captcha_length'] = $captcha_length ;
							\$CONFIG_BOXMAIL['initial_message_value'] = \"$initial_message_value\" ;
							\$CONFIG_BOXMAIL['button_name'] = \"$button_name\" ;
							\$CONFIG_BOXMAIL['captcha_message'] = \"$captcha_message\" ;
							
							
							?>" ;
							

				$fp = fopen($file_to_write, 'w');  //open config.inc.php to write the new variables
				fwrite($fp, $content); // write the contents on config.inc.php
				fclose($fp);  //close config.inc.php
				
				$fp_css = fopen($css_file_to_write, 'w');
				fwrite($fp_css, $css_style);
				fclose($fp_css);
				
				
				echo "Success. ";
				echo "$file_to_write ";
				echo "has been written";
				echo "<br>";
				echo "$css_file_to_write ";
				echo "has been written";


	}

	include_once('config.inc.php'); // Configuration File

	$fr_css = fopen(WP_PLUGIN_DIR.'/b-oxmail/style.css', 'r'); // CSS Style
	$current_css_style = fread($fr_css,9999);
	fclose($fr_css);

?>
</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=b-oxmail&update=1' ; ?>">
  <table width="100%" border="0" align="left">
    <tr>
      <th scope="row"><div align="left">
      <div align="left"><font size="+1">Mail Settings</font></div></th>
      <td></th>
        <div align="left"></div>
    </tr>
    <tr>
      <th valign="top" scope="row"><div align="left">Admin Email</div></th>
      <td valign="top"><div align="left">
        <label><input type="text" name="admin_email" id="admin_email" value="<?php echo $CONFIG_BOXMAIL['admin_email']; ?>" />
        </label>
      <font size="1">(where the mail will be sent)</font> &nbsp;</div></th>
    </tr>
    <tr>
      <th valign="top" scope="row"><div align="left">E-mail Subject</div></th>
      <td valign="top"><label><input type="text" name="email_subject" id="email_subject" value="<?php echo $CONFIG_BOXMAIL['email_subject']; ?>"/>
      </label>
      <font size="1">(e-mail subject)</font></th>
    </tr>
    <tr>
      <th valign="top" scope="row"><div align="left">Thank You</div></th>
      <td valign="top"><label><input type="text" name="thank_you" id="thank_you" value="<?php echo $CONFIG_BOXMAIL['thank_you']; ?>" />
        </label>
      <font size="1">(message to be displayed on successful submission)</font></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Mail Back</div></th>
      <td><label>
          <input <?php if ( $CONFIG_BOXMAIL['mail_back'] === "TRUE" ) {echo "checked=\"checked\"";} ?> name="mail_back" type="checkbox" id="mail_back" />
        
      </label>
      <font size="1">(Send back e-mail to sender or not)</font></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Mail Back Message</div></th>
      <td><label>

          <textarea name="mail_back_message" cols="45" rows="5" id="mail_back_message"><?php echo $CONFIG_BOXMAIL['mail_back_message']; ?></textarea>

      </label>
        <label> </label>
      <font size="1">(message to send back)</font></th>
    </tr>
    <tr>
      <th scope="col"><div align="left"></div></th>
      <th scope="col"><div align="left"></div></th>
    </tr>
    <tr>
      <th scope="col"><div align="left">
      <div align="left"><font size="+1">Front-End Settings</font></div></th>
      <th scope="col"><div align="left"></div></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Captcha Length</div></th>
      <td><label>

          <input type="text" name="captcha_length" id="captcha_length" value="<?php echo $CONFIG_BOXMAIL['captcha_length']; ?>" />

      </label>
        <label> </label>
      <font size="1">(length of captcha code. upto 9 characters.)</font></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Initial Message</div></th>
      <td><label>

          <input type="text" name="initial_message_value" id="initial_message_value" value="<?php echo $CONFIG_BOXMAIL['initial_message_value']; ?>" />

      </label>
        <label> </label>
        <label> </label>
      <font size="1">(text in message textarea)</font></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Button Name</div></th>
      <td><label>

          <input type="text" name="button_name" id="button_name" value="<?php echo $CONFIG_BOXMAIL['button_name']; ?>" />

      </label>
        <label> </label>
        <label> </label>
      <font size="1">(name of submit button)</font></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">Captcha Message</div></th>
      <td><label>

          <input type="text" name="captcha_message" id="captcha_message" value="<?php echo $CONFIG_BOXMAIL['captcha_message']; ?>" />

      </label>
        <label> </label>
        <label> </label>
      <font size="1">(message to be displayed for captcha verification code)</font></th>
    </tr>
    <tr>
      <th scope="col"><div align="left"></div></th>
      <th scope="col"><div align="left"></div></th>
    </tr>
    <tr>
      <th scope="row"><div align="left">CSS-Styling :</div></th>
      <td><label>
          <textarea name="css_style" id="css_style" cols="45" rows="5"><?php echo $current_css_style ; ?></textarea>
      </label>
        <label> </label>
        <label> </label>
      <font size="1">(CSS styling. Please do not change the class names.)</font></th>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td>     <label>
      <input type="submit" name="Submit" id="Submit" value="Submit" />
    </label></td>   
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><a href="http://boxmail.ankitaggarwal.me">B-oxmail</a> Widget designed by <a href="http://ankitaggarwal.me">Ankit Aggarwal</a>. You are using Version 0.2.<br />
        I would appreciate any kind of feedback/suggestions from your side. Mail in at : ankit.cs [-at-] gmail.com</td>
    </tr>
  </table>
  <p>

  </p>
</form>

<?php
}
?>