<!-- ******************************************************************* -->
<!-- PHP "self" code handling unsubscribing from mailinglist             -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 8-X.9.2020 by CDesigner.eu                 -->



<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
    $email ="";
  

    $is_removed = false; //before hitting submit button no result is available
    
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
                    
                  

                    // create DELETE query
                    $sql = "DELETE FROM mailinglist WHERE email = "."'$email'" ;



                        if(mysqli_query($dbc, $sql)){
                            
                            $msg = 'Subscriber with e-mail: '.$email. ' has been succesfully removed from mailinglist.';
                            $msgClass = 'alert-success';
                            $is_removed = true;

                            // clear entry fields after sucessfull deleting from database
                            
                            
                           
                        } else{
                            
                            $msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
                            $msgClass = 'alert-danger';
                            $is_removed = false;
                        }

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
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
    <title> mailinglist - unsubscribe from mailinglist </title>
    
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Mailinglist app v 1.0 - unsubscribe from mailinglist</a>
        </div>
      </div>
    </nav>
    <div class="container">	
    	
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
			
			echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'mailer.php\'" type="button">  Return to mailer -> </button>';

			echo "<br>";
			echo "<br>";
            
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