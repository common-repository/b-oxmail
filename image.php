<?php
require_once('captcha.class.php');
include_once('config.inc.php');
Captcha::displayImage($CONFIG_BOXMAIL['captcha_length']);
?>