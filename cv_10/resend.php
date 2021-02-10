<!-- ******************************************************************* -->
<!-- PHP "self" code handling resend new default password                -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 9.1-10.11.2021 by CDesigner.eu             -->
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

 $_resended = false; // page is on first run before resending new password to provided user e-mail

//get info that user is loged in, if not try it looking at cookies
//if(!isset($_COOKIE['s'])) { old solution with cookies
  if(!isset($_SESSION['users_id'])) { //new with session variables
    if(isset($_POST['submit'])) {
        /* Attempt MySQL server connection.  */
             $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
             
                // obtaining e-mail for recovery password
             $e_mail = htmlspecialchars($_POST['e_mail']);    
            

            
             if(!empty($e_mail)) {

                if(filter_var($e_mail, FILTER_VALIDATE_EMAIL) === false){
                    // E-mail is not walid
                    $msg = 'Please use a valid email';
                    $msgClass = 'alert-danger';
                } else {
                // e-mail is valid

                     // try lookup user database - if e-mail is in a database
                        
                        $sql = "SELECT users_id, username, user_role FROM bazaar_user WHERE email = "."'$e_mail'" ;
                        
                        
                        $data = mysqli_query($dbc, $sql);   
                        
                        if(mysqli_num_rows($data) == 1) {
                            //obtain user data form executed query
                            $row = mysqli_fetch_array($data);
                            
                            $username = $row['username']; // get uswername asociated with provided e-mail from fetched data from a users database

                            // display info about account that was gathered from database for that e-mail
                            $_resended == true; //page is reloaded and will display info about succesfull reset and resend password

                            // generate new strong password 
                             
	                            define('PASS_NUMCHARS', 10); // number of characters inrandom passphrase
	 
	                            // generating passphrase by random numbers
                                $new_pass_phrase = "";
                                for($i = 0; $i < CAPTCHA_NUMCHARS; $i++ ) {
                                    $new_pass_phrase .= chr(rand(48, 90)); //ascii from 0 to Z
                                }

	 

                                // hash passord
                                $new_pass_hash = SHA1($pass_phrase); 

                                // insert neh password hash into a database for that e-mail
                                $sql = "UPDATE bazaar_user SET
                                        
                                            
                                            pass_word = '".$new_pass_hash."'
                                   
                                            WHERE   email = "."'$e_mail'" ;   
                                    if($output = mysqli_query($dbc, $sql)){
                                        if($output) {  // if any record obtained from SELECT query
                                          //echo "Heslo bolo úspešne zmenené"; 
                                          $msg .= ' PASSWORD changed succesfuly. ';
								                          $msgClass = 'alert-success';
                                          
                                        } else{
                                            //echo "Password cannot be changed."; // if no records in table
                                            $msg .= ' PASSWORD cannot be changed. ';
								                            $msgClass = 'alert-danger';
                                        }
                                    } else {
                                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                                    }        



                            // send e-mail with new generated password to provided e-mail address

                                /****************************************************************************************
								 *   construct e-mail with new password for access into a bazaar account
								 */

                                $toEmail = $e_mail; //!!! e-mail address to send to - change for your needs!!!
                                // debug only for test becaus mercury is not in xamp configured for sending outside a local domain $toEmail = 'ciljak@localhost.org';
								$subject = 'New login on bazaar for user '.$username;
								$body = '<h2>Your new access credentials for account '.$username.' with associated e-mail : '.$e_mail.' </h2>
                                    <h4>For gaining access to your account please use these credentials:  </h4><p>username:'.$username.',</p><p> password: '.$pass_phrase.', </p>
                                    <p>We strongly encourage you to change password after succesfful login on edit user page for your own strong password!</p>
									<h4>Visit us on <a href="bazaar.com">bazaar.com</a></h4>
									';

								// Email Headers
								$headers = "MIME-Version: 1.0" ."\r\n";
								$headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

								// Additional Headers
                                //$headers .= "From: " .$first_name_buyer. "<".$email_buyer.">". "\r\n";
                                $headers .= "From: admin@bazaar.com ";

							

										
								if(mail($toEmail, $subject, $body, $headers)){
									// Email Sent
									$msg .= '<p> Your new accessing credentials for account '.$username.' was successfully sent via provided e-mail '.$e_mail.'.</p>';
									$msgClass = 'alert-success';
								} else {
									// Failed
									$msg = 'New access credentials can not be send via e-mail due to other technical problem. Please contact site admin for further help.';
									$msgClass = 'alert-danger';
								}
                             
                             
                            
                            
                            
                            // $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                            // header('Location:'. $home_url);

                            // Free result set
                            mysqli_free_result($data);
                            // Close connection
                            mysqli_close($dbc);

                        } else  {
                            // user account with requested -email does not exist - you cannot reset them - only display info about retyping e-mail address
                            $msg .= "User account with e-mail: ". $e_mail. ". does not exist. E-mail with new login credentials can not be send!  ";
                            $msgClass = 'alert-danger';
            
                        }     


                }
             

              
            } else {
                // username/ password were not entered - display error message
                $msg .= "Sorry, you must eneter e-mail address for sending new login credentials. ";
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
	<title> Bazaar resend password </title>
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
          <a class="navbar-brand" href="index.php">Bazaar - mainpage</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		<?php if($msg != ''): ?>
        <br> 
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
    	
      <?php 
            
              // if (empty($_resended)) { $_resended = false; };
              if(empty($_SESSION['users_id']) &&  ($_resended == false)) { // solution with sessions - if user is not loged in
                // only show for if session with name users_id does not exist
                //echo ' <br> ';
    		        //echo  '<p class="alert alert-danger">' . $msg . '</p>';
       ?>	
        
        <br> 
        <img id="calcimage" src="./images/resend.png" alt="resend password" width="150" >
        <br>

        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <div id="login">
                <legend> Recovering access to existing user account <legend>
                <br>
                
                <label>Your registration e-mail:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['e_mail']) ? '' : ''; ?>'" name="e_mail" class="form-control" value="<?php echo isset($_POST['e_mail']) ? 'Please reenter' : 'e-mail'; ?>">
                    <br>
                   
                    

            </div>
           <center><input id="loginsubmitt" type="submit" name="submit" class="btn btn-warning" value="Resend password"> </center>
           <br>

        </form>

        <?php }  else if ($_resended == true) { 
                 // user is not loged and e-mail was good submited and there display info about sucessfull resend e-mail
                 
                  echo '<br>';
                  echo '<p class="alert alert-success"> For your account' . $username. ' we provided new password.</em></p>'; // session solution
                  echo '<p class="alert alert-success"> Provided password has been sent in to e-mail <em>' . $e_mail. '</em> used during resistration of your account. </p>'; // session solution
                  echo '<p class="alert alert-success"> We recommend you change them after first successful login on edit profile page. </a></p>';
              } else { 
                  // user is loged in - there is no need for resend password
                 
                  echo '<br>';
                  echo '<p class="alert alert-success"> You are loged in as <em>' . $_SESSION['username']. '</em></p>'; // session solution
                  echo '<p class="alert alert-success"> There is <em>no need</em> for resend new password. </p>'; // session solution
                  echo '<p class="alert alert-success"> If you will logout or login with another credentials, please first <a href="logout.php">logout!. </a></p>';
            }  
        ?>	


      </div>

          
		
		
      <?php  // footer include code
          require_once('footer.php'); // including footer
          generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>  
 

</body>
</html>