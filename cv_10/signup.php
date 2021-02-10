<!-- ******************************************************************* -->
<!-- PHP "self" code handling sign up for membership on the bazaar app   -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 24.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
 require_once('captcha.php'); // including generator of captcha image
 

 
   
 // two variables for message and styling of the mesage with bootstrap
 $msg = '';
 $msgClass = '';
 $u_name = '';
 $usr_passwd = '';
 $verified_human_by_CAPTCHA = -1;
 $pass_phrase_now = '';
 
/* Attempt MySQL server connection.  */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
// debug echo "CAPTCHA zo seesion ". $_SESSION['pass_phrase'];
// debug echo "CAPTCHA v premennej ". $pass_phrase;

if(isset($_POST['submit'])) { 
    // obtaining submitted data from POST
    $u_name = htmlspecialchars($_POST['u_name']);
    $u_pass_1 = htmlspecialchars($_POST['u_pass_1']);
    $u_pass_2 = htmlspecialchars($_POST['u_pass_2']);
    $email = htmlspecialchars($_POST['email']);
    
    //implement CAPTCHA pass-phrase verification
    
    $user_pass_phrase = sha1(htmlspecialchars($_POST['verify']));
    $pass_phrase_now = htmlspecialchars($_POST['pass_phrase_now']);
    $imageCaptchafilename_now = htmlspecialchars($_POST['imageCaptchafilename_now']); // name of current captcha photo file for deletion after usage
    
    //debug echo "image name for deletion ".$imageCaptchafilename_now;

    // debug echo "CAPTCHA od usra ". $user_pass_phrase. "uzivatel napisal " . $_POST['verify'];
    //debug echo "CAPTCHA z generatora ". $pass_phrase_now;
    // echo "CAPTCHA ". $_SESSION['pass_phrase'];
    if($pass_phrase_now == $user_pass_phrase) {
      $verified_human_by_CAPTCHA = 1;
      @unlink($imageCaptchafilename_now); // delete captcha file
        //debug echo "captcha ok";

    } else {
      $verified_human_by_CAPTCHA = 0;
      @unlink($imageCaptchafilename_now); // also delete captcha file because new one was created
      $msgClass = 'alert-danger';
      $msgCAPTCHA = "Your CAPTCHA was written wrong, please correct it and resend.";
    };

    if(!empty($u_name) && !empty($email) && !empty($u_pass_1) && !empty($u_pass_2) && ($u_pass_1 = $u_pass_2) && $verified_human_by_CAPTCHA) {
     // make sure that username is available and is not registered for someone else
     //debug echo "veriefied human";
     $sql = "SELECT * FROM bazaar_user WHERE username = "."'$u_name'" ;
     $data = mysqli_query($dbc, $sql);   
 
       if(mysqli_num_rows($data) == 0) {
           // username is unique and have not been used by any previous user
           $usr_passwd_sha1 =  sha1($u_pass_2);
           $sql = "INSERT INTO bazaar_user (username, pass_word, write_date, email, nickname) 
                   VALUES ('$u_name', '$usr_passwd_sha1' , now(), '$email','$u_name')"; // by default nickname and username are the same, next user can change

           if(mysqli_query($dbc, $sql)){
            $msg = ' Your new account has been created successfully. 
            You are now ready to <a href="login.php">log in</a>';
            $msgClass = 'alert-success';
           } else{
               echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
           }
           //success confirmation for registered user
         
           

            // Free result set
			mysqli_free_result($data);
            // Close connection
            
            //exit(); if used blank page will be displayed without any other redirecting

       } else { // an account already exists for this username, so display an error message
           
            $msg = ' An account for submitted username already exsts. Please use different username ...';
            $msgClass = 'alert-danger';
       } 
    } else {
     
            $msg = ' Your must enter all of the required data, including contact e-mail address.';
            $msgClass = 'alert-danger';
    }
      
}   
    // Close connection 
    mysqli_close($dbc);    


?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar signup page  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div class="container" id="header_container_580">
        <div class="navbar-header"> 
          <?php
             require_once('headerlogo.php');
          ?>   
          <a class="navbar-brand" href="index.php">Bazaar - Signup for submitting/ buying your items</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
        
    <?php if($msg != ''): ?>
        <br> 
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
         
    	
     	
        
        <br> 
        <img id="calcimage" src="./images/login.png" alt="bazaar image" width="150" height="150">
        <br>

        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <div id="login">
                <legend> Please register for Bazaar membership <legend>
                    <label>Username:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['u_name']) ? $u_name : ''; ?>'" name="u_name" class="form-control" value="<?php echo isset($_POST['u_name']) ? $u_name : 'Login name'; ?>">
                    <br>
                    <label>e-mail:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['email']) ? $email : '@'; ?>'" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $email : '@'; ?>">

                    <br>
                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass_1']) ? '' : ''; ?>'" name="u_pass_1" class="form-control" value="<?php echo isset($_POST['u_pass_1']) ? '' : ''; ?>">
                    <br>
                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass_2']) ? '' : ''; ?>'" name="u_pass_2" class="form-control" value="<?php echo isset($_POST['u_pass_2']) ? '' : ''; ?>">
                    <br>
                    <label for="verify">Verification - enter text from image below:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['verify']) ? '' : ''; ?>'" name="verify" class="form-control" value="<?php echo isset($_POST['verify']) ? '' : 'Enter the CAPTCHA verify code'; ?>">
                    <br>

                    <?php if(($verified_human_by_CAPTCHA == 0) ): //error messaging if wrong CAPTCHA?>
                    <br> 
                    <div class="alert <?php echo $msgClass; ?>"><?php echo $msgCAPTCHA; ?></div>
                    <?php endif; ?>	

                    <center> <img src="<?php echo $imageCaptchafilename ; ?>" alt="Verification pass-phrase" > </center> 
                    <!-- ass a hidden is sent sha actualy generated captcha pass-phrase only this way it is producet in same run -->
                    <input type="hidden" name="pass_phrase_now" value="<?php echo sha1($pass_phrase); ?>" />
                    <!-- as a hidden is sentname of captcha file for deletion after use -->
                    <input type="hidden" name="imageCaptchafilename_now" value="<?php echo $imageCaptchafilename; ?>" />

            </div>
           <input id="loginsubmitt" type="submit" name="submit" class="btn btn-info" value="Sign In"> 
           <br>

        </form>

       


      </div>

          
		
		
      <?php  // footer include code
         require_once('footer.php'); // including footer
         generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
      ?>  
 

</body>
</html>