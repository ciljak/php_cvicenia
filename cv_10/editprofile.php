<!-- ******************************************************************* -->
<!-- PHP "self" code handling user profile editing                       -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 25.10-30.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
    if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
      $_SESSION['username'] = $_COOKIE['username'];
      $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
		}
	 }
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$users_id = "";
	$username = "";
	$pass_word = "";
	$nickname= "";
	$first_name = "";
	$lastname_name = "";
	$address = false;
	$city = "";
	$ZIPcode = "";
	$email = "";
    $GDPR_accept = false;
    $rules_accept = false;
    $avatar = ""; // photo location of avatar
    $profile_text = "";


	$is_result = false; //before hitting submit button no result is available
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')) {
        // Data obtained from $_postmessage are assigned to local variables
        
        $users_id = $_SESSION['users_id']; // obtained from login user
        $username = $_SESSION['username'];
        
        $pass_word1 = sha1(htmlspecialchars($_POST['pass_word1']));
        $pass_word2 = sha1(htmlspecialchars($_POST['pass_word2']));
        $pass_word_old = sha1(htmlspecialchars($_POST['pass_word_old']));

	    $nickname= htmlspecialchars($_POST['nickname']);
	    $first_name = htmlspecialchars($_POST['first_name']);
	    $lastname_name = htmlspecialchars($_POST['lastname_name']);
	    $addresss = htmlspecialchars($_POST['addresss']);
	    $city = htmlspecialchars($_POST['city']);
	    $ZIPcode = htmlspecialchars($_POST['ZIPcode']);
	    
       // $GDPR_accept = isset($_POST['GDPR_accept']); // checkbox doesnot send post data, they must be checked for its set state !!!
        isset($_POST['rules_accept']) ? $rules_accept ="1": $rules_accept ="0"; // checkbox doesnot send post data, they must be checked for its set state !!!
        isset($_POST['GDPR_accept']) ? $GDPR_accept ="1": $GDPR_accept ="0";
    
        $avatar = htmlspecialchars($_FILES['avatar']['name']);           // photo location of avatar
        $profile_text = htmlspecialchars($_POST['profile_text']);
		
        //echo 'users_id'; echo $users_id;
        //echo $rules_accept;
        //echo $GDPR_accept;
        //echo $nickname;
		
	

		

		// Controll if all required fields was written
		if( !empty($nickname) && $rules_accept && $GDPR_accept) { // these item identifiers are mandatory and can not be empty
			// If check passed - all needed fields are written
			// Check if E-mail is valid
			//echo $rules_accept;
      //  echo $GDPR_accept;

                
                // move image to /images final folder from temporary download location
				$avatar_target1 = IMAGE_PATH . $avatar;
				

				// !!! Add entry to the database and redraw all score in chart list descending from highest score

				   // insert into databse 
                      if (1) {
                         move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_target1);
							
							// make database connection
							$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
							// Check connection
								if($dbc === false){
									die("ERROR: Could not connect to database. " . mysqli_connect_error());
								}
							
							// INSERT new entry
					  	// need systematic debug!!!  - now it is ok, it can be used as further example  
					
              // example working and tested syntax for UPPDATE query $sql = "UPDATE bazaar_user SET nickname = '".$nickname."',  first_name = '".$first_name."'
              //               WHERE   users_id = '".$users_id. "' AND username = '".$username."'" ; 

              $sql = "UPDATE bazaar_user SET
                                            nickname = '".$nickname."',
                                            first_name = '".$first_name."',
                                            lastname_name = '".$lastname_name."',
                                            addresss = '".$addresss."',
                                            city = '".$city."',
                                            ZIPcode = '".$ZIPcode."',
                                            write_date = now(),
                                            
                                            GDPR_accept = '".$GDPR_accept."',
                                            rules_accept = '".$rules_accept."',
                                            avatar  = '".$avatar."',
                                            profile_text = '".$profile_text."'
                                            

                                            WHERE   users_id = '".$users_id. "' AND username = '".$username."'"; 

              // . $_POST['userid'] . "', first_name='" . $_POST['first_name'] . "', last_name='" . $_POST['last_name'] . "',
              // city_name='" . $_POST['city_name'] . "' ,email='" . $_POST['email'] . "' WHERE userid='" . $_POST['userid'] . "'");
                               
							//show updated user data true
							$is_result = true; 


							if(mysqli_query($dbc, $sql)){
								
								$msg = 'Profile updated succesfuly. ';
								$msgClass = 'alert-success';
							} else {
								
								$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
								$msgClass = 'alert-danger';
                            }
                            // echo "DEBUG - idem k casti s heslom";            
                            // update password only if both passwords are not emty and are equal and old password match 
                            //pass_word = $pass_word, and only add hash to filed not plane password
                           // $pass_word1 = htmlspecialchars($_POST['pass_word1']);
                           // $pass_word2 = htmlspecialchars($_POST['pass_word2']);
                           // $pass_word_old = htmlspecialchars($_POST['pass_word_old']);
                           //DEBUG - echo $pass_word1;
                           //DEBUG - echo $pass_word2;
                           //DEBUG - echo $pass_word_old;
                           if(isset($pass_word1) && isset($pass_word2) && isset($pass_word_old )){ // old and two input for new password are provided
                            if($pass_word1 == $pass_word2){ // new passwords is ok typed 2x the same
                                // echo "DEBUG - hesla sa rovnaju"; 
                                // obtain old password sha1 for reference
                                $_username = $_SESSION['username'];
                                // echo "DEBUG -username $_username";
                                $_users_id = $_SESSION['users_id'];
                                //echo " DEBUG -users_id $_users_id  ";
                                //$sql = "SELECT * FROM bazaar_user WHERE username = "."'$_username'". " AND users_id = "."'$_users_id'" ;
                                $sql = "SELECT * FROM bazaar_user WHERE username = "."'$_username'"."LIMIT 1"  ;
                                if($output = mysqli_query($dbc, $sql)){
                                    if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
                                        
                                        while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                                       
                                                $pass_word_old_stored = $row['pass_word'];
                                                // echo "DEBUG - 0. vo while hash stareho hesla je $pass_word_old_stored  ";
                                                                                     
                                        }
                                        
                                        // Free result set
                                        mysqli_free_result($output);
                                    } else{
                                        echo "Old password can not be obtained."; // if no records in table
                                    }
                                } else{
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                                }
                                //echo "DEBUG - 1. hash stareho hesla je $pass_word_old_stored ";
                                //echo "DEBUG - 2. hash stareho zadaneho hesla uzivatelom $pass_word_old) ";
                                if($pass_word_old_stored == $pass_word_old){ // if old pasword provided by user is the same as in database, passwords can be changed
                                //  echo "DEBUG - 3. stare heslo bolo zadane spravne"; 
                                   
                                //  echo "DEBUG - pasword je zmienany na $pass_word1";
                                    $sql = "UPDATE bazaar_user SET
                                        
                                            
                                            pass_word = '".$pass_word1."'
                                   
                                            WHERE   users_id = '".$users_id. "' AND username = '".$username."'" ;   
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
                                    } else{
                                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                                    }        

                                }
                            } 
                           
                           }

							// end connection
								mysqli_close($dbc);
								
			
			
						} else {
							// Failed - if not all fields are fullfiled
							$msg = 'Please fill in all * marked contactform fields - nickname, GDPR and portal rules are mandatory!';
							$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
                        };
                        
                   
        } else {
          // Failed - if not all fields are fullfiled
          $msg = 'Please fill in all * marked contactform fields - nickname, GDPR and portal rules are mandatory!';
          $msgClass = 'alert-danger'; // bootstrap format for allert message with red color
                    };
        
    

	};	
  
	

	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar - item for sell  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div id="header_container_580">
        <div class="navbar-header">  
        <?php  
            require_once('headermenu.php'); // including menu items
        ?>	  
         
          <?php /*-- older solution only for this page menu if(isset($_SESSION['users_id'])) {  // display different page header along way why is user loged in or not - users_id is set when user is loged in
                  echo  '<a class="navbar-brand" href="editprofile.php">Bazaar - editing personal profile</a>';
                } else { 
                  echo  '<a class="navbar-brand" href="login.php">Unauthorized - please Log In </a>'; 
            }; */
            ?>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	

<!-- ***************************************** -->
<!-- HTML par available after succesfull login -->
<!-- ***************************************** -->		
<?php if(isset($_SESSION['users_id'])) { //if user is loged with users_id then editprofile form is available?> 

	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/logout.png" alt="Edit profile main page icon" width="150" height="150">
        <br>

      <?php   //part displaying user_role of loged user
				 
					

						echo " <br> <br>";
            echo " <table class=\"table table-success\"> ";
            $user_role = $_SESSION['user_role'];
            $username = $_SESSION['username'];
						echo " <tr>
							   <td><h5>  User_role of succesfully loged user with name <strong> $username </strong> is <strong>$user_role</strong> . ";    
						
						  
						echo "	   <td>   </tr> "; 
						echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				
				 ?>  

      <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
	      <div class="form-group">

          <?php // here read data from bazar_user table and prefill input fileds with previeously obtained data from user
					 	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

						    // Check connection
							 if($dbc === false){
								 die("ERROR: Could not connect to database. " . mysqli_connect_error());
							 };
						 
						 
							
                            $_username = $_SESSION['username'];
                            $_users_id = $_SESSION['users_id'];
							// create SELECT query for category names from database
							$sql = "SELECT * FROM bazaar_user WHERE username = "."'$_username'"." AND users_id="."'$_users_id'" ;

							// execute sql and populate data list with existing category in database
							if($output = mysqli_query($dbc, $sql)){
								if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
									
									while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
									
                                            
                                            $pass_word = $row['pass_word'];
                                            $nickname= $row['nickname'];
                                            $first_name = $row['first_name'];
                                            $lastname_name = $row['lastname_name'];
                                            $addresss = $row['addresss'];
                                            $city = $row['city'];
                                            $ZIPcode = $row['ZIPcode'];
                                            $email = $row['email'];
                                            $gdpr = $row['GDPR_accept']; // checkbox doesnot send post data, they must be checked for its set state !!!
                                            $rules_accept = $row['rules_accept'];
                                        
                                            $avatar = $row['avatar'];           // photo location of avatar
                                            $profile_text = $row['profile_text'];
											
											
									
									}
									
									// Free result set
									mysqli_free_result($output);
								} else{
									echo "There is no category in category table. Please wirite one."; // if no records in table
								}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
							}

			 
			                // Close connection
                            mysqli_close($dbc);
                    ?>
            <!-- these data are only displayed but cannot be changed -->

            <div id="frame_gray">
              <label> Your registered with these credentials. They cannot be changed, only way how to obtain new is deleting account asking page admin and create new one:</label>
              <label>User ID:</label>
		      <input type="text"  name="users_id" class="form-control" value="<?php echo $_SESSION['users_id']?>" disabled>
              <br>
              <label>User name:</label>
		      <input type="text"  name="username" class="form-control" value="<?php echo $_SESSION['username']?>" disabled>
              <br>
              <label>E-mail:</label>
              <input type="text"  name="email" class="form-control" value="<?php echo $email?>" disabled>
              <br>
            </div>  
              <br>
              <br>
              
            <div id="frame_green">
              <label>Further user data data:</label>
              <br>

              <label>*Nickname:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['nickname']) ? $nickname : ''; ?>'" name="nickname" class="form-control" value="<?php echo isset($_POST['nickname']) ? $nickname : $nickname; ?>">
              <br>
              <label>First name:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['first_name']) ? $first_name: ''; ?>'" name="first_name" class="form-control" value="<?php echo isset($_POST['first_name']) ? $first_name : $first_name; ?>">
              <br>
              <label>Last name:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['lastname_name']) ? $lastname_name : ''; ?>'" name="lastname_name" class="form-control" value="<?php echo isset($_POST['lastname_name']) ? $lastname_name : $lastname_name; ?>">
              <br>
              <label>Adress in form - Street Nr.:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['addresss']) ? $addresss : ''; ?>'" name="addresss" class="form-control" value="<?php echo isset($_POST['addresss']) ? $addresss : $addresss; ?>">
              <br>
              <label>City:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['city']) ? $city : ''; ?>'" name="city" class="form-control" value="<?php echo isset($_POST['city']) ? $city : $city; ?>">
              <br>
              <label>ZIP code in form XXXXX:</label>
              <input type="text" onfocus="this.value='<?php echo isset($_POST['ZIPcode']) ? $ZIPcode : ''; ?>'" name="ZIPcode" class="form-control" value="<?php echo isset($_POST['ZIPcode']) ? $ZIPcode : $ZIPcode; ?>">
              <br>
            </div> 

            <br> 
              <!-- GDPR and rule of the portal acceptance -->
            <div id="frame_red">
              <div class="form-group">
              <label>Acceptation of portal rules and GDPR regulations - IMPORTANT PART:</label>
              <br>
              <br>
	      	  <input type="checkbox" name="GDPR_accept" class="form-control" <?php if($gdpr) { echo "checked"; } ?> >
              <label>* I agree with GDPR regulations</label>
              <br>
              <input type="checkbox" name="rules_accept" class="form-control" <?php if($rules_accept) { echo "checked"; } ?> >
              <label>* I agree with rules of the portal</label>
              <br>


              </div>
            </div>  

        
			  
	      </div>
	      <div id="frame_green">
                 <?php
                        // From database obtain avatar image file name and next recreate their location
                       
						          	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

                        // Check connection
                        if($dbc === false){
                          die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
                        }


								
										
                       // read $avatar value from databaze of user
                        $_username = $_SESSION['username']; // get info about currently loged user
                        $_users_id = $_SESSION['users_id'];
                        $sql = "SELECT * FROM bazaar_user WHERE username = "."'$_username'"." AND users_id="."'$_users_id'"; // query avatar
                        
                        if($output = mysqli_query($dbc, $sql)){
                          if(mysqli_num_rows($output) > 0){
                           $row = mysqli_fetch_array($output);
                           $write_date_obtained = $row['write_date']; // get latest profile update date for output located at the botoom part of page
                           if (!empty($row['avatar'])) {
                            $image_location = IMAGE_PATH.$row['avatar'];
                            echo "<center> <td id=\"gray_under_picture\">  <br> <img  align=\"middle\" src=\"$image_location\" alt=\" profile avatar picture \"  height=\"250\"> <br> <br> <br> </td> </center>";
                           } else {
                            echo "<center> <td id=\"gray_under_picture\"> <br> <img align=\"middle\" src=\"./images/default_avatar.png\" alt=\" profile avatar picture \"  height=\"250\"> <br> <br> </td> </center>";
                           }
                           
                           mysqli_free_result($output);
                          }
                        }    
                        

                        // Close connection
							          mysqli_close($dbc);
                 ?>        
                <p> In this part you can select your profile avatar! </p>
                <label>* Please select location of your avatar from drive - max 5MB!</label>
                <div class="custom-file">
                <br>
                <input type="file" name="avatar" class="custom-file-input" id="avatar" lang="en" onchange="getFilename(this)">
                    <label class="custom-file-label1 custom-file-label"  for="customFile">Screenshot1 - required:</label>
                <br>
                </div>
          </div>     	 

          

			  <script type="application/javascript"> // javascript handling chaging filename of selected file
               $(document).ready(function(){
				$("#avatar").change(function(){
					//alert("A file 1 has been selected.");
                    var thefile1 = document.getElementById('avatar');
                    
					var fileName1 = thefile1.value;
                    //var fileName1 = "A file 1 has been selected.";
                    $('.custom-file-label1').html(fileName1);
				    
				});
				$("#screenshot2").change(function(){
					//alert("A file 2 has been selected.");
					var thefile2 = document.getElementById('screenshot2');
                    
                    var fileName2 = thefile2.value;
					//var fileName2 = "A file 2 has been selected.";
                    $('.custom-file-label2').html(fileName2);
				});
				$("#screenshot3").change(function(){
					//alert("A file 3 has been selected.");
					var thefile3 = document.getElementById('screenshot3');
                    
                    var fileName3 = thefile3.value;
					//var fileName3 = "A file 3 has been selected.";
                    $('.custom-file-label3').html(fileName3);
				});
              });
            
			  
			   
             </script>
       

          <br><br>
		 
          <div id="frame_green"> 
            <div class="form-group">
                <label>Profile text - plese provide some description for your profile if will:</label>  <!-- textera for input large text -->
                <textarea id="profile_text" onfocus="this.value='<?php echo isset($_POST['profile_text']) ? $profile_text : 'Please provide description for your profile if will ...'; ?>'" name="profile_text" class="form-control" rows="3" cols="50"><?php echo isset($_POST['profile_text']) ? $profile_text : $profile_text; ?></textarea>
                <br>
            </div>
         </div>
         <br>
         <div id="frame_red">
              <label> If you will change password. Write old password and then for verification two times new one:</label>
              <label>Old password:</label>
		      <input type="password"  name="pass_word_old" class="form-control" >
              <br>
              <label>New password:</label>
		      <input type="password"  name="pass_word1" class="form-control" >
              <br>
              <label>New password once again for verification:</label>
              <input type="password"  name="pass_word2" class="form-control" >
              <br>
            </div>  
              <br>
    
		  
         <br><br>
		 
	 
		  <center> <button type="submit" name="submit" class="btn btn-warning btn-lg"> Update profile information </button> </center>
		  
		
		 
          <br><br>
		  

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo " <br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
							   <td><h5>  Personal info  for user  <strong> $username </strong> was last modified at $write_date_obtained. ";    
						
						  
						echo "	   <td>   </tr> "; 
						echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				} ; 
				 ?>
                 <br>
		
	  </form>
     
<!-- ***************************************** -->
<!-- HTML part displayed for unloged user      -->
<!-- ***************************************** --> 
    <?php } else { // else if user is not loged then form will noot be diplayed?>  
      <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>For further profile editing please log in<a class="navbar-brand" href="login.php"><h4><u>here.</u> </h4></a></h4>
        <br>
      <?php } ?>  
	  
		
		</div>

          
		
		
    <?php  // footer include code
      require_once('footer.php'); // including footer
      generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>