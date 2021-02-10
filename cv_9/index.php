<!-- ******************************************************************* -->
<!-- PHP "self" code handling submitting score into a chart              -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 27-XX.X.2020 by CDesigner.eu               -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$email = "";
	$nickname = "";
	$screenshot = "";
	$gdpr = false;
    $score = '0';
    $message_from_submitter = '';
	$is_result = false; //before hitting submit button no result is available
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')){
		// Data obtained from $_postmessage are assigned to local variables
		$nickname = htmlspecialchars($_POST['nickname']);
		$screenshot = htmlspecialchars($_FILES['screenshot']['name']);
		$email = htmlspecialchars($_POST['email']);
		$gdpr = isset($_POST['gdpr']); // checkbox doesnot send post data, they must be checked for its set state !!!
        $score = htmlspecialchars($_POST['score']); 
        $message_from_submitter = htmlspecialchars($_POST['message_from_submitter']);
		
		

		// Controll if all required fields was written
		if(!empty($email) && !empty($nickname) && !empty($score) && !empty($screenshot) && $gdpr ){
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
				$subject = 'New submitted score '.$nickname.' '.$score;
				$body = '<h2>To your becnhmark chart was added new score from:</h2>
					<h4>Name</h4><p>'.$nickname.'</p>
					<h4>Email</h4><p>'.$email.'</p>
					';

				// Email Headers
				$headers = "MIME-Version: 1.0" ."\r\n";
				$headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

				// Additional Headers
                $headers .= "From: " .$nickname. "<".$email.">". "\r\n";
                
                // move image to /images final folder from demporary download location
                $target = IMAGE_PATH . $screenshot;

				// !!! Add entry to the database and redraw all score in chart list descending from highest score

				   // insert into databse 
                      if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
						// make database connection
						$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
                        // Check connection
							if($dbc === false){
								die("ERROR: Could not connect to database. " . mysqli_connect_error());
							}
						
						// INSERT new entry
					    $date = date('Y-m-d H:i:s'); // get current date to log into databse along postmessage written
						$sql = "INSERT INTO benchmark_chart (nickname, write_date, email, GDPR_accept, screenshot, message_from_submitter, score) 
						VALUES ('$nickname', now() , '$email' , '$gdpr' , '$screenshot', '$message_from_submitter', '$score')";



						if(mysqli_query($dbc, $sql)){
							
							$msg = 'New score '.$score. ' from '. $nickname. ' succesfully added to chart.';
					        $msgClass = 'alert-success';
						} else {
							
							$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					        $msgClass = 'alert-danger';
						}

						// end connection
                            mysqli_close($dbc);
                      };       
				if(mail($toEmail, $subject, $body, $headers)){
					// Email Sent
					$msg .= ' Your benchmark score was sucessfully send via e-mail to page admin.';
					$msgClass = 'alert-success';
				} else {
					// Failed
					$msg = ' Your benchmark was not sucessfully send via e-mail to page admin.';
					$msgClass = 'alert-danger';
				}
			}
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all * marked contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		}

	};	
  
	// if delete button clicked - not well imlepented yet
	/* if(filter_has_var(INPUT_POST, 'delete')){
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
               $nickname = htmlspecialchars($_POST['nickname']); 
			   

			   // create DELETE query
			   $sql = "DELETE FROM benchmark_chart WHERE email = "."'$email'". " AND nickname = "."'$nickname'" ;



				if(mysqli_query($dbc, $sql)){
					
					$msg = 'Latest score scuccessfully removed from the chart.';
					$msgClass = 'alert-success';

					// clear entry fileds after sucessfull deleting from database
					$email = "";
                    $nickname = "";
                    $screenshot = "";
                    $gdpr = false;
                    $score = '0';
                    $message_from_submitter = '';
                    $is_result = false; //before hitting submit button no result is available
				} else{
					
					$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					$msgClass = 'alert-danger';
				}

			// end connection
				mysqli_close($dbc);

			}
			

	};
	*/

	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
		$nickname ='';
		$score ='';
        $email ='';
        $message_from_submitter ='';
		$gdpr = false; 
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Benchmark results chart  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">3dmark results chart v 1.0 - results & submit your score</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/benchmark.jpg" alt="Calc image" width="150" height="150">
        <br>

      <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
	      <div class="form-group">
		      <label>* Please provide Your score:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['score']) ? $score : ''; ?>'" name="score" class="form-control" value="<?php echo isset($_POST['score']) ? $score : 'Your becnhmark score'; ?>">
              

			  <label>* Please provide Your nickname:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['nickname']) ? $nickname : ''; ?>'" name="nickname" class="form-control" value="<?php echo isset($_POST['nickname']) ? $nickname : 'Your nickname'; ?>">
	      </div>
	      <div class="form-group">
	      	<label>* E-mail:</label>
	      	<input type="text" onfocus="this.value='<?php echo isset($_POST['email']) ? $email : '@'; ?>'"name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $email : '@'; ?>">
	      </div>
          
          <label>* Please select location of your score screenshot from drive - max 5MB!</label>
          <div class="custom-file">
          
	      <input type="file" name="screenshot" class="custom-file-input" id="screenshot" lang="en">
              <label class="custom-file-label" for="customFile">Screenshot:</label>
	      </div>
            
             <script type="application/javascript"> // javascript handling chaging filename of selected file
              $('input[type="file"]').change(function(e){
              var fileName = e.target.files[0].name;
              $('.custom-file-label').html(fileName);
              });
             </script>

          <br><br>

          
		  <div class="form-group">
	      	<label>Optionally - Your score comment:</label>  <!-- textera for input large text -->
	      	<textarea id="message_from_submitter" onfocus="this.value='<?php echo isset($_POST['message_from_submitter']) ? $message_from_submitter : 'Your score escribing text goes here ...'; ?>'" name="message_from_submitter" class="form-control" rows="3" cols="50"><?php echo isset($_POST['message_from_submitter']) ? $message_from_submitter : 'Your score escribing text goes here ...'; ?></textarea>
	      </div>

		  <div class="form-group">
	      	<input type="checkbox" name="gdpr" class="form-control" value="<?php echo isset($_POST['gdpr']) ? $gdpr : 'gdpr'; ?>">
			<label>* I agree with GDPR regulations</label>

			
	      </div>

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Submitt score </button>
		  
		  <!-- remove comment after implementation
		  <button type="submit" name="delete" class="btn btn-danger"> Delete recently posted score </button>
          -->
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
          <br><br>
		  <?php
		  echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'chart.php\'" type="button">  Take a look at actual chart -> </button>';
		  ?>
		  <br>

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
                               <td><h5> <em> E-mail: </em> $score from $nickname  </h5> <h5> has been succesfully added to becnhmark chart </h5> ";
                               $image_location = IMAGE_PATH.$screenshot;
                        echo " <img src=\"$image_location\" alt=\" score image \"  height=\"150\"> ";       
						if ($gdpr == true ) { echo "<h5> GDPR accepted </h5>";	} ; //if GDPR rights granted
						  
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
$sql = "SELECT * FROM benchmark_chart ORDER BY score DESC";  // read in reverse order of score - highest score first
/*************************************************************************/
/*  Output in Table - solution 1 - for debuging data from database       */
/*************************************************************************/
// if data properly selected from guestbook database tabele

echo "<h4>Chart of benchmark results</h4>";
echo "<br>";
//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo "<th>id</th>";
                    echo "<th>score</th>";
                    echo "<th>nickname</th>";
                    echo "<th>date of post</th>";
                    echo "<th>screenshot</th>";
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['score'] . "</td>";
                    echo "<td>" . $row['nickname'] . "</td>";
                    echo "<td>" . $row['write_date'] . "</td>";
                    $image_location = IMAGE_PATH.$row['screenshot'];
                        echo "<td> <img src=\"$image_location\" alt=\" score image \"  height=\"95\"> </td>"; 
                echo "</tr>";
                echo " </div> " ;
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($output);
        } else{
            echo "There is no benchmark result in chart. Please wirite one."; // if no records in table
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