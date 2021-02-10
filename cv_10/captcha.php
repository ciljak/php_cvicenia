<!-- ******************************************************************* -->
<!-- PHP  code generating verification captcha image                     -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 8. - 9.11.2020 by CDesigner.eu             -->
<!-- ******************************************************************* -->

<?php
	//require_once('appvars.php'); // including variables for database
	
	// if included whole not necessary session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
    /*if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
		}
	 } */
	 
	 // important captcha constants
	 define('CAPTCHA_NUMCHARS', 6); // number of charakters in CAPTCHA
	 define('CAPTCHA_WIDTH', 200); // width of image
	 define('CAPTCHA_HEIGHT', 60); // height of image
	 // Set Correct Path to Font File
     $fontPath='C:\xampp_7_4_2020\htdocs\bazaar\images\courier_new_bold.ttf'; 

	 // generating passphrase by random numbers
	 $pass_phrase = "";
	 for($i = 0; $i < CAPTCHA_NUMCHARS; $i++ ) {
		$pass_phrase .= chr(rand(97, 122));
	 }

	 // store the encryption pass-phrase in a session variable
	 $_SESSION['pass_phrase'] = sha1($pass_phrase);

	 //create the image
	 $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);

	 //set a white background with black text and gray graphics
	 $bg_color = imagecolorallocate($img, 255, 255, 255); //white
	 $text_color = imagecolorallocate($img, 255, 146, 130); //pale red
	 $graphic_color = imagecolorallocate($img, 64, 64, 64); //darkgray
	 $graphic_color_noise_red = imagecolorallocate($img, 255, 128, 128); //red noise pattern
	 $graphic_color_noise_green = imagecolorallocate($img, 128, 255, 128); //green noise pattern

	 // fill the background
	 imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);

	 // image edges rectangle drawing 
	 imagerectangle ( $img , 0 , 0, CAPTCHA_WIDTH -1  , CAPTCHA_HEIGHT -1 , $graphic_color  );

	 //draw some random lines
	 for($i = 0; $i < 5; $i++) {
		 imageline($img,0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
	 }
	 
	 //sprinkle in some random green dots
	 for($i = 0; $i < 1000; $i++) {
		imagesetpixel($img, rand() % CAPTCHA_WIDTH,  rand() % CAPTCHA_HEIGHT, $graphic_color_noise_green);
	} 

	// draw the pass-phrase string
	imagettftext($img, 36, rand(0,10), rand(0, 12) , CAPTCHA_HEIGHT - rand(-5, 5), $text_color, $fontPath, $pass_phrase);

	//sprinkle over in some random dots
	for($i = 0; $i < 1000; $i++) {
		imagesetpixel($img, rand() % CAPTCHA_WIDTH,  rand() % CAPTCHA_HEIGHT, $graphic_color_noise_red);
	} 
	

	// VERY IMPORTANT: Prevent any Browser Cache!! - older approach send by header
    // header("Cache-Control: no-store, 
    //no-cache, must-revalidate");  

	// output the image as PNG using a header;
	/* ob_clean(); 
	header("Content-type: image/jpg");
	imagejpg($img);*/
	// creating filename and sending them through session and variable

	$imageCaptchafilename = IMAGE_PATH . "captcha".rand(1,1000).".png";
	// debug echo $imageCaptchafilename;
	$_SESSION['imageCaptchafilename'] = $imageCaptchafilename;


	//writting image to png
	imagepng($img, $imageCaptchafilename, 5);

	//clean up
	imagedestroy($img);

 ?>    