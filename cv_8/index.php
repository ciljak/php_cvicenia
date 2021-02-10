<!-- ******************************************************************* -->
<!-- PHP "self" code handling subscribing into mailinglist               -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 8-20.9.2020 by CDesigner.eu                -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$email = "";
	$firstname = "";
	$lastname = "";
	$gdpr = '0';
	$newsletter = '0';
	$is_result = false; //before hitting submit button no result is available
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')){
		// Data obtained from $_postmessage are assigned to local variables
		$firstname = htmlspecialchars($_POST['firstname']);
		$lastname = htmlspecialchars($_POST['lastname']);
		$email = htmlspecialchars($_POST['email']);
		$gdpr = isset($_POST['gdpr']); // checkbox doesnot send post data, they must be checked for its set state !!!
		$newsletter = isset($_POST['newsletter']); 
		
		

		// Controll if all required fields was written
		if(!empty($email) && !empty($firstname) && !empty($lastname)){
			// If check passed - all needed fields are written
			// Check if E-mail is valid
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
				// E-mail is not walid
				$msg = 'Please use a valid email';
				$msgClass = 'alert-danger';
			} else {
				// E-mail is ok
				$is_result = true;
				$toEmail = 'ciljak@localhost.org'; //!!! e-mail address to send to - change for your needs!!!
				$subject = 'Guestbook entry from '.$firstname.' '.$lastname;
				$body = '<h2>To your Guestbook submitted:</h2>
					<h4>Name</h4><p>'.$firstname.'</p>
					<h4>Email</h4><p>'.$email.'</p>
					';

				// Email Headers
				$headers = "MIME-Version: 1.0" ."\r\n";
				$headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

				// Additional Headers
				$headers .= "From: " .$lastname. "<".$email.">". "\r\n";

				// !!! Add entry to the database and redraw all postmessages into guestbook list with newest postmessage as first

				   // insert into databse 

						// make database connection
						$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
                        // Check connection
							if($dbc === false){
								die("ERROR: Could not connect to database. " . mysqli_connect_error());
							}
						
						// INSERT new entry
					    $date = date('Y-m-d H:i:s'); // get current date to log into databse along postmessage written
						$sql = "INSERT INTO mailinglist (firstname_of_subscriber, secondname_of_subscriber, write_date, email, GDPR_accept, news_accept) 
						VALUES ('$firstname', '$lastname', now() , '$email' , '$gdpr' , '$newsletter')";



						if(mysqli_query($dbc, $sql)){
							
							$msg = 'new subscriber'.$email.' succesfully added';
					        $msgClass = 'alert-success';
						} else{
							
							$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					        $msgClass = 'alert-danger';
						}

						// end connection
						    mysqli_close($dbc);
				if(mail($toEmail, $subject, $body, $headers)){
					// Email Sent
					$msg .= 'Your postmessage was sucessfully send via e-mail';
					$msgClass = 'alert-success';
				} else {
					// Failed
					$msg = 'Your postmessage was not sucessfully send via e-mail';
					$msgClass = 'alert-danger';
				}
			}
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		}

	};	
  
	// if delete button clicked
	if(filter_has_var(INPUT_POST, 'delete')){
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			// E-mail is not walid
			$msg = 'Please use a valid email';
			$msgClass = 'alert-danger';
		} else {

		    $msg = 'Delete last mesage hit';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
        
            // delete from database

			// make database connection
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			// Check connection
				if($dbc === false){
					die("ERROR: Could not connect to database. " . mysqli_connect_error());
				}
			
			// DELETE last input by matching your written message
			   // obtain message string for comparison

			   $email = htmlspecialchars($_POST['email']); 
			   $postmessage = trim($postmessage);

			   // create DELETE query
			   $sql = "DELETE FROM mailinglist WHERE email = "."'$email'" ;



				if(mysqli_query($dbc, $sql)){
					
					$msg = 'Last subscriber sucessfully removed from database.';
					$msgClass = 'alert-success';

					// clear entry fileds after sucessfull deleting from database
					$firstname ='';
					$lastname ='';
					$email ='';
					$gdpr = false; 
					$newsletter = false; 
				} else{
					
					$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					$msgClass = 'alert-danger';
				}

			// end connection
				mysqli_close($dbc);

			}
			

	};

	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
		$firstname ='';
		$lastname ='';
		$email ='';
		$gdpr = false; 
		$newsletter = false; 
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> mailinglist - subscription  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Mailinglist app v 1.0 - subscribing part</a>
        </div>
      </div>
    </nav>
    <div class="container">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	

		<img id="calcimage" src="./images/addmail.png" alt="Calc image" width="200" height="200">

      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <div class="form-group">
		      <label>Please provide Your first name:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['firstname']) ? $firstname : ''; ?>'" name="firstname" class="form-control" value="<?php echo isset($_POST['firstname']) ? $firstname : 'Your Firstname'; ?>">

			  <label>Please provide Your last name:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['firstname']) ? $lastname : ''; ?>'" name="lastname" class="form-control" value="<?php echo isset($_POST['lastname']) ? $lastname : 'Your Lastname'; ?>">
	      </div>
	      <div class="form-group">
	      	<label>E-mail:</label>
	      	<input type="text" onfocus="this.value='<?php echo isset($_POST['email']) ? $email : '@'; ?>'"name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $email : '@'; ?>">
	      </div>

		  <div class="form-group">
	      	
	      	<input type="checkbox" name="gdpr" class="form-control" value="<?php echo isset($_POST['gdpr']) ? $gdpr : 'gdpr'; ?>">
			<label>I agree with GDPR regulations</label>

			  
	      	<input type="checkbox" name="newsletter" class="form-control" value="<?php echo isset($_POST['newsletter']) ? $newsletter : 'newsletter'; ?>"> 
			<label>I subscribe to Newsletter:</label>
	      </div>

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Subscribe to mailinglist </button>
		  
		  <button type="submit" name="delete" class="btn btn-danger"> Unsubscribe now </button>

		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
          <br><br>
		  <?php
		  echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'userunsub.php\'" type="button">  Unsubscribe by e-mail -> </button>';
		  ?>
		  <br>

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
							   <td><h5> <em> E-mail: </em> $email </h5> <h5> succesfully added to mailinglist and granted these privileges </h5> ";
						if ($gdpr == true ) { echo "<h5> GDPR accepted </h5>";	} ; //if GDPR rights granted
						if ($newsletter == true ) { echo "<h5> Newsletter subscribed </h5>";	} ; //if subscribed to a newsletter	   
						echo "	   <td>   </tr> "; 
						echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				} ; 
				 ?>
                 <br>
		
	  </form>

	  

	  
		
		</div>
		
		
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
		
      
</body>
</html>