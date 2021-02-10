<!-- ******************************************************************* -->
<!-- PHP "self" code handling unsubscribing from mailinglist  for users  -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 26.9.2020 by CDesigner.eu                  -->
<!-- ******************************************************************* -->



<?php
    // two variables for message and styling of the mesage with bootstrap
    require_once('appvars.php'); // including variables for database

	$msg = '';
    $msgClass = '';
    $msg_about_contains_email = '';
    $msgClass_email = '';

	// default values of auxiliary variables
    $email ="";
  

    $is_removed = false; //before hitting submit button no result is available
    $is_present = false; // email is not in the table - default before slecting against user submitted email for deletion
    
    if(filter_has_var(INPUT_POST, 'submit')){
		// Data obtained from $_postmessage are assigned to local variables
		$email = htmlspecialchars($_POST['email']);
       
		 
		
		

		// Controll if all required fields was written
		if(!empty($email) ) {
            // If check passed - all needed fields are written
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
				// E-mail is not walid
				$msg = 'Please use a valid email';
				$msgClass = 'alert-danger';
			} else {
                // E-mail is walid - now delete row with matching e-mail

                        // make database connection
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

                    // Check connection
                        if($dbc === false){
                            die("ERROR: Could not connect to database. " . mysqli_connect_error());
                        }
                    
                    // get info if appropriate e-mail is in mailinglist
                       // create DELETE query
                        $sql = "SELECT email FROM mailinglist WHERE email = "."'$email'";



                        if(($row['email'] = mysqli_fetch_array($result = mysqli_query($dbc, $sql))) != ''){
                            
                            $msg_about_contains_email = 'Subscriber with e-mail: '.$email. ' was found in database for deletion.';
                            $msgClass_email = 'alert-success';
                            $is_present = true;

                            // create DELETE query
                            $sql = "DELETE FROM mailinglist WHERE email = "."'$email'"." LIMIT 1";

                            if(mysqli_query($dbc, $sql)){
                            
                                $msg = 'Subscriber with e-mail: '.$email. ' has been succesfully removed from mailinglist.';
                                $msgClass = 'alert-success';
                                $is_removed = true;

                            // clear entry fields after sucessfull deleting from database
                            
                            
                        
                                };
                            
                            
                        
                        } else{
                            $msg_about_contains_email = 'Subscriber with e-mail: '.$email. ' was not found in database for deletion. Probably was not subscribed for mailing.';
                            $msgClass_email = 'alert-warning';
                            $msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
                            $msgClass = 'alert-danger';
                            $is_present = false;
                        };


                   

                    // end connection
                        mysqli_close($dbc);

                    };           
				
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		}; 

	};	
	


	
  
	
	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
		$subject ='';
        $email ='';
        $msg_about_contains_email = '';
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
    <title> mailinglist - user unsubscribe </title>
    
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Mailinglist app v 1.0 - unsubscribing for user</a>
        </div>
      </div>
    </nav>
    <div class="container">	
    <?php
     echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'index.php\'" type="button">  Return to home -> </button>';
     ?>
      <br><br>
    	
	  <?php if($msg_about_contains_email != ''): ?>
    		<div class="alert <?php echo $msgClass_email; ?>"><?php echo $msg_about_contains_email; ?></div>
      <?php endif; ?>	

      <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>

		<img id="calcimage" src="./images/unsubscribe.png" alt="Calc image" width="200" height="200">

      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <div class="form-group">
		      <label>e-mail to unsubscribe:</label>
		      <input type="text" onfocus="this.value='@'"  name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $email : 'Write e-mail address to unsubscribe here'; ?>">

			  
	      </div>
	      

		 
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Unsubscribe </button>
		  
		  

          <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
          <br>

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_removed ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
							   <td><h5> <em> Your e-mail: </em> $email </h5> <h5> has been succesfully removed from mailinglist. </h5> ";
						
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