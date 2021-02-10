<!-- ******************************************************************* -->
<!-- PHP "self" code handling login into the bazaar app                  -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 11.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
 require_once('captcha.php'); // including generator of captcha image
 session_start(); // start the session
   
 // two variables for message and styling of the mesage with bootstrap
 $msg = '';
 $msgClass = '';
 $usr_username = '';
 $usr_passwd = '';

 $verified_human_by_CAPTCHA = -1; //

//get info that user is loged in, if not try it looking at cookies
//if(!isset($_COOKIE['s'])) { old solution with cookies
  if(!isset($_SESSION['users_id'])) { //new with session variables
    if(isset($_POST['submit'])) {
        /* Attempt MySQL server connection.  */
             $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
             
                // accessing user entered login data
             $usr_username = htmlspecialchars($_POST['u_name']);    
             $usr_passwd = htmlspecialchars($_POST['u_pass']);

             //implement CAPTCHA pass-phrase verification
    
            $user_pass_phrase = sha1(htmlspecialchars($_POST['verify']));
            $pass_phrase_now = htmlspecialchars($_POST['pass_phrase_now']);
            $imageCaptchafilename_now = htmlspecialchars($_POST['imageCaptchafilename_now']); // name of current captcha photo file for deletion after usage
             
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

             if(!empty($usr_username) && !empty($usr_passwd) && $verified_human_by_CAPTCHA) {
              // try lookup user database
              $usr_passwd_SHA = sha1($usr_passwd);
              $sql = "SELECT users_id, username, user_role FROM bazaar_user WHERE username = "."'$usr_username'". " AND pass_word = "."'$usr_passwd_SHA'" ;
              // debug output echo  $usr_username; 
              // echo  $usr_passwd;
              //echo $usr_passwd_SHA;
              $data = mysqli_query($dbc, $sql);   
              
              if(mysqli_num_rows($data) == 1) {
                  // login is ok, set user  ID and username cookies and redirect to the homepage
                  $row = mysqli_fetch_array($data);
                  //setcookie('users_id', $row['users_id']); old solution with cookies
                  //setcookie('username', $row['username']);
                  $_SESSION['users_id'] = $row['users_id']; // sloution with sessions
                  $_SESSION['username'] = $row['username'];
                  $_SESSION['user_role'] = $row['user_role']; // added user_role session variable
                  // new cookies for login persistency that expires after 30 days without logout combination SESSION with COOKIES is awailable
                  setcookie('users_id', $row['users_id'], time()+(60+60*24*30));
                  setcookie('username', $row['username'], time()+(60+60*24*30));
                  setcookie('user_role', $row['user_role'], time()+(60+60*24*30)); // cookie for user_role of loged in user added

                  $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                  header('Location:'. $home_url);

                  // Free result set
				          mysqli_free_result($data);
                  // Close connection
                  mysqli_close($dbc);

              } else  {
                  // urename/ password are incorrect - error meesage is displayed
                  $msg = "Incorrect username or password. Login denied!  ";
				          $msgClass = 'alert-danger';
   
            }     

              
            } else {
                // username/ password were not entered - display error message
                $msg = "Sorry, you must eneter username and password along with correct CAPTCHA phrase to log in. ";
			        	$msgClass = 'alert-danger';
   
            }     
    }  

} 

?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar login page  </title>
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
          <a class="navbar-brand" href="index.php">Bazaar - Login page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		<?php if($msg != ''): ?>
        <br> 
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
    	
      <?php 
            //if(empty($_COOKIE['users_id'])) { solution with cookies
              if(empty($_SESSION['users_id'])) { // solution with sessions
                // only show for if session with name users_id does not exist
                //echo ' <br> ';
    		        //echo  '<p class="alert alert-danger">' . $msg . '</p>';
       ?>	
        
        <br> 
        <img id="calcimage" src="./images/login.png" alt="bazaar image" width="150" height="150">
        <br>

        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <div id="login">
                <legend> Log In <legend>
                <label>Username:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['u_name']) ? '' : ''; ?>'" name="u_name" class="form-control" value="<?php echo isset($_POST['u_name']) ? 'Please reenter' : 'Login name'; ?>">

                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass']) ? '' : ''; ?>'" name="u_pass" class="form-control" value="<?php echo isset($_POST['u_pass']) ? 'Please reenter' : 'Login name'; ?>">

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

                    <!-- redirecting to mage for reseting password -->
                    <br>
                    <p>I forgot my password - <a href="./resend.php">resend password. </a> </p>

            </div>
           <input id="loginsubmitt" type="submit" name="submit" class="btn btn-info" value="Log In"> 
           <br>

        </form>

        <?php }  else { 
                 // successfull login
                  // cookie solution echo '<p class="alert alert-success"> You are loged in as ' . $_COOKIE['username']. '</p>';
                  echo '<br>';
                  echo '<p class="alert alert-success"> You are loged in as <em>' . $_SESSION['username']. '</em></p>'; // session solution
                  echo '<p class="alert alert-success"> If you will logout or login with anither credentials, please first <a href="logout.php">logout!. </a></p>';
              } 
        ?>	


      </div>

          
		
		
      <?php  // footer include code
          require_once('footer.php'); // including footer
          generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>  
 

</body>
</html>