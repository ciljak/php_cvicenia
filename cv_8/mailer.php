<!-- ******************************************************************* -->
<!-- PHP "self" code handling mailing message to subscreiber             -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 19.9.2020 by CDesigner.eu                   -->

<!-- ******************************************************************* -->
<!-- PHP "self" code handling subscribing into mailinglist               -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 8-X.9.2020 by CDesigner.eu                 -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
    $message ="";
    $subject = "";
    $gdpr = "";
    $newsletter = "";

    $is_result = false; //before hitting submit button no result is available
    
    if(filter_has_var(INPUT_POST, 'submit')){
		// Data obtained from $_postmessage are assigned to local variables
		$subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);
        $is_result = true;
		 
		
		

		// Controll if all required fields was written
		if(!empty($subject) && !empty($message)) {
            // If check passed - all needed fields are written
            
                        
				
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		}; 

	};	
	


	
  
	
	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
		$subject ='';
		$message ='';
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
    <title> mailinglist - mailer to subscriber  </title>
    
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Mailinglist app v 1.0 - mailing part</a>
        </div>
      </div>
    </nav>
    <div class="container">	
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	

		<img id="calcimage" src="./images/sendemail.jpg" alt="Calc image" width="200" height="200">

      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <div class="form-group">
		      <label>Subject of send message:</label>
		      <input type="text" onfocus="this.value=''"  name="subject" class="form-control" value="<?php echo isset($_POST['firstname']) ? $subject : 'Subject of message:'; ?>">

			  <label>Message to send:</label>
		      <textarea onfocus="this.value=''" id="message" name="message" class="form-control" rows="10" cols="50"><?php echo isset($_POST['message']) ? $message : 'Your text goes here ...'; ?></textarea>
	      </div>
	      

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Send to subscribers </button>
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
          <br>

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
							   <td><h5> <em> Message: </em> $message </h5> <h5> succesfully sent to all subscribers on list bellow </h5> ";
						
						echo "	   <td>   </tr> "; 
						echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				} ; 
				 ?>
                 <br>
		
      </form>
      
      <?php // if message to send was submitted then emails are sent mail by mail

      // Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')){
        // $subject and $message was aded to variables in scrit on upper part of page, because we expect outpu about sending email
        // in body of page thic code is inserted in html body part of code
		

		// Controll if all required fields was written
		if(!empty($subject) && !empty($message)) {
            // If check passed - all needed fields are written
            $is_result = true;
            // send e-mail to all subscribers

                // connect to database
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
 
                // Check connection
                    if($dbc === false){
                        die("ERROR: Could not connect to database. " . mysqli_connect_error());
                    }
            
                // read all e-mails from database - create query and pass it to database server

                $sql = "SELECT DISTINCT email FROM mailinglist";

                if($output = mysqli_query($dbc, $sql)){
                    if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
                        
                        // create  and send email one by one
                        
                        echo "<h4>Sending e-mails</h4>";
                        echo "<br>";
    
                        while($row = mysqli_fetch_array($output)){ //send email by email and output message
                            // create email structure
                            // E-mail is ok
                                $fromEmail = 'ciljak@localhost.org'; //!!! e-mail address from message is send - change for your needs!!!
                                $toEmail = $row['email'];
                                $body = $message;

                                // Email Headers
                                $headers = "MIME-Version: 1.0" ."\r\n";
                                $headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

                                // Additional Headers
                                $headers .= "From: CDesigner.eu  <".$fromEmail.">". "\r\n";

                                if(mail($toEmail, $subject, $body, $headers)){
                                    // Email Sent
                                    echo "<p> Email to: ";
                                    echo " " . $row['email'] . " ";
                                    echo "  has been sent ... </p>";
                                   
                                } else {
                                    // Failed
                                    echo "<p> Email to: ";
                                    echo " " . $row['email'] . " ";
                                    echo "  cannot be send, please examine your email server connection! </p>";
                                }

                            
                                
                        }
                        echo "<br>";
                        // Free result set - free the memory associated with the result
                        mysqli_free_result($output);
                    } else{
                        echo "There is no subscriber in mailinglist. Please add them."; // if no records in table
                    }
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                }
    
                // Close connection
                mysqli_close($dbc);
               
				
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		}; 

	};	

      ?>


	  

	  <?php // code showing all subscribers in form of a table at end of the page

			/* Attempt MySQL server connection. Assuming you are running MySQL
			server with default setting (user 'root' with no password) */
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
			
			// Check connection
			if($dbc === false){
				die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
			}
			
			
				
						
			// read all rows (data) from guestbook table in "test" database
			$sql = "SELECT * FROM mailinglist ORDER BY id DESC";  // read in reverse order - newest article first
			/*************************************************************************/
			/*  Output in Table - solution 1 - for debuging data from database       */
			/*************************************************************************/
            // if data properly selected from guestbook database tabele
            
            echo "<h4>Our subscribers mailinglist</h4>";
            echo "<br>";
            echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';
            
            echo "<br>"; echo "<br>";
            
				if($output = mysqli_query($dbc, $sql)){
					if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
						// create table output
						echo "<table>"; //head of table
							echo "<tr>";
								echo "<th>id</th>";
								echo "<th>firstname</th>";
                                echo "<th>lastname</th>";
                                echo "<th>date</th>";
								echo "<th>email</th>";
								
							echo "</tr>";
                        while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                            echo " <div class=\"mailinglist\"> " ;
							echo "<tr>";
								echo "<td>" . $row['id'] . "</td>";
								echo "<td>" . $row['firstname_of_subscriber'] . "</td>";
								echo "<td>" . $row['secondname_of_subscriber'] . "</td>";
								echo "<td>" . $row['write_date'] . "</td>";
								echo "<td>" . $row['email'] . "</td>";
                            echo "</tr>";
                            echo " </div> " ;
						}
						echo "</table>";
						// Free result set
						mysqli_free_result($output);
					} else{
						echo "There is no postmessage in Guestbook. Please wirite one."; // if no records in table
					}
				} else{
					echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
				}
            
			

			// Close connection
			mysqli_close($dbc);
			?>
		
		</div>
		
		
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
		
      
</body>
</html>