<!-- ***************************************************************************** -->
<!-- PHP "self" code showing content of items added into a cart                    -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 1.11.2020 by CDesigner.eu                            -->
<!-- ***************************************************************************** -->

<!-- ****************** MEMO - base is from index.php - show all items with cart_number = session(users_id) + calc total
                              summ, create remove from cart link with removefromcart.php 
                              show address for delivery and button submitt to buy ************************************ -->

<?php
	require_once('appvars.php'); // including variables for database
	session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
    if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
		}
	 }
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$name_of_item = "";
	$price_eur = "";
	$subcategory_id = "";
	$users_id = "";
	$item_add_date = "";
	$subcategory_id = "";
	$published = false;
	$screenshot1 = "";
	$screenshot2 = "";
	$screenshot3 = "";
    $item_description = '';
	$is_result = false; //before hitting submit button no result is available
	$cart_was_submitted = false;
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')) {
		// Data obtained from $_postmessage are assigned to local variables
		if($_POST['confirm'] == 'Yes' ){ // if yuser selected YES and hit Buy button on below of the page
			// cart wass submitted to buy, then message user about buy in item part of cart page
			$cart_was_submitted = true;
			//read all data from $_POST array
			$users_id = htmlspecialchars($_POST['users_id']);
            /***********************************************************
			 *   obtain data about buyer
			 */
			// read data about buying user with users id from database
			// make database connection
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
			// Check connection
				if($dbc === false){
					die("ERROR: Could not connect to database. " . mysqli_connect_error());
				}
			
			//-- only one needed -- geting users data for purchase e-mail
			$sql = "SELECT * FROM bazaar_user WHERE users_id = "."'$users_id'"."LIMIT 1"  ;
			if($output = mysqli_query($dbc, $sql)){
				if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
					
					while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
							$first_name_buyer = $row['first_name'];
							$lastname_name_buyer = $row['lastname_name'];
							$addresss_buyer = $row['addresss'];
							$city_buyer = $row['city'];
							$ZIPcode_buyer = $row['ZIPcode'];
							$email_buyer = $row['email'];
 
													
					}
					
					// Free result set
					mysqli_free_result($output);
				} else {
					echo "No info about buyer obtained."; // if no records in table
					$cart_was_submitted = false; // items cann not be bought by technical issue
				}
			} else {
				echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
				$cart_was_submitted = false; // items cann not be bought by technical issue

			};
			
			 /****************************************************************************************
			 *   obtain data buyed items from this buyer with users_id defined by current SESSION
			 */
			//get info about sold items - we must go through all buyed items and send emaily one by one for all diferent selers of item (first approach for all item one)
            $sql = "SELECT * FROM bazaar_item WHERE cart_number = "."'$users_id'"  ;
			if($output = mysqli_query($dbc, $sql)){
				if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
					
					while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
							$item_id = $row['item_id'];
							$name_of_item = $row['name_of_item'];
							$price_eur = $row['price_eur'];
							$users_id_of_seller = $row['users_id'];
							 /****************************************************************************************
							 *   if item with item_ide was bought, tgen set cart_number to -1 and published to -1 - mean sold
							 */
							 // update cart_number and published to -1 sold

							$sql_update = "UPDATE bazaar_item SET cart_number = '-1', published = '-1' WHERE item_id = $item_id LIMIT 1";
							// execute SQL
							mysqli_query($dbc, $sql_update);


							
                             /****************************************************************************************
							 *   sent info to seler item by item in the buyer cart
							 */
							// send appropriate e-mails about buy items by items
                             // validate e-mail
							if(filter_var($email_buyer, FILTER_VALIDATE_EMAIL) === false){
								// E-mail is not walid
								$msg = 'Wrong e-mail format of buyer, purchase can not be created. Please contact page admin.';
								$msgClass = 'alert-danger';
							} else {
								// E-mail is ok
								$is_result = true;
								/* request e-mail of seller */
								 /****************************************************************************************
								 *   obtain e-mail of appropriate seller - this is done for all buying items one by one
								 */
								$sql2 = "SELECT email FROM bazaar_user WHERE users_id = "."'$users_id_of_seller'"  ;
								if($output2 = mysqli_query($dbc, $sql2)){
									if(mysqli_num_rows($output2) > 0){  // if any record obtained from SELECT query
										
										while($row = mysqli_fetch_array($output2)){ //next rows outputed in while loop
											$email_of_seller = $row['email'];
												
					 
																		
										}
										
										// Free result set
										mysqli_free_result($output2);
									} else{
										echo "No email about seller can be obtained."; // if no records in table
									}
								} else{
									echo "ERROR: Could not able to execute $sql2. " . mysqli_error($dbc); // if database query problem
								};

                                 /****************************************************************************************
								 *   construct information e-mails about item buy one by one for all items in cart
								 */

								$toEmail = $email_of_seller; //!!! e-mail address to send to - change for your needs!!!
								$subject = 'Item '.$name_of_item.' purchased on Bazaar by '.$first_name_buyer.' '.$lastname_name_buyer;
								$body = '<h2>Item '.$name_of_item.' was succesfully purchased by : '.$first_name_buyer.' '.$lastname_name_buyer.'</h2>
									<h4>Delivery adress for this purchase is: </h4><p>'.$addresss_buyer.',</p><p> '.$city_buyer.', </p><p>'.$ZIPcode_buyer.'</p>
									<h4>Email</h4><p>E-mail of buyer is'.$email_buyer.' this e-mail can be used for further communication.</p>
									<h4>Selling price was:</h4><p'.$price_eur.' €.</p>
									';

								// Email Headers
								$headers = "MIME-Version: 1.0" ."\r\n";
								$headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

								// Additional Headers
								$headers .= "From: " .$first_name_buyer. "<".$email_buyer.">". "\r\n";

							

										
								if(mail($toEmail, $subject, $body, $headers)){
									// Email Sent
									$msg .= '<p> Your seller of '.$name_of_item.' was successfully contacted via e-mail.</p>';
									$msgClass = 'alert-success';
								} else {
									// Failed
									$msg = 'Information about your buy cannot be delivered to seller via e-mail. Please contact site admin for further help.';
									$msgClass = 'alert-danger';
								}
							}
											
																	
									}
									
									// Free result set
									mysqli_free_result($output);
								} else {
									echo "No info about buyer obtained."; // if no records in table
								}
							} else {
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
							};



	

			// close database connection
			mysqli_close($dbc);

		 
		  } else {
			  echo  '<p class="alert alert-danger" > The selected operation cannot be performed. Please select YES for further buy confirmation. </p>'; 
		  }
	
		

		

	};	
  
	

	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar Cart  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div id="header_container_1060">
        <div class="navbar-header">   
		<?php 
		   require_once('headermenu.php'); // including menu items
		?>	 
         
        </div>
      </div>
    </nav>
    <div class="container" id="container_1060">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/cart.png" alt="cart image" width="150" height="150">
        <br>


	  <h4> Cart item of user 
		<?php    echo $_SESSION['username'];  // creating title of cart for users
		         echo " with id -  {$_SESSION['users_id']} are:"; 
		?>
		<br>
	  </h4>
      <?php
	  //messaging about succesfully commited item for buy, in this case no item is deisplayed
	  if ($cart_was_submitted) {
		  echo '<h5 class="alert alert-success"> Content of your cart has been scuccesfully submited to buy. For return on main bazaar page
		        clik <a href="index.php">here<a>.</h5>';
	  }

	  ?>

	  <!-- Showing content of the cart of appropriate user with items marked with users_id in filed cart_number -->
	  <?php 
				/* Attempt MySQL server connection. Assuming you are running MySQL
			server with default setting (user 'root' with no password) */
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			// Check connection
			if($dbc === false){
				die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
			}

						
								
				
						
			// read all rows (data) from guestbook table in "test" database
			$_usr_id = $_SESSION['users_id'];	
			$sql = "SELECT * FROM bazaar_item WHERE cart_number="."'$_usr_id'"." ORDER BY item_id ASC ";  // read items marked in cart_number with appropriate users_id
			/*************************************************************************/
			/*  Output in Table - solution 1 - for debuging data from database       */
			/*************************************************************************/
			// if data properly selected from guestbook database tabele
			
			echo "<br>";
			//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';

			echo "<br>"; echo "<br>";

			if($output = mysqli_query($dbc, $sql)){
				if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
					// create table output
					echo "<table>"; //head of table
						echo "<tr>";
							//echo "<th>id</th>";
							echo "<th>Name</th>";
							echo "<th>Price</th>";
							echo "<th>Category</th>";
							echo "<th>Screenshot1</th>";
							echo "<th>Remove from cart</th>";
							
							
						echo "</tr>";
				    $cart_total_eur = 0; // initialize cariable calculating total price for items in cart
					while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
						echo " <div class=\"mailinglist\"> " ;
						echo "<tr>";
							//echo "<td>" . $row['item_id'] . "</td>";
							echo "<td class=\"item_name\">" . $row['name_of_item'] . "</td>";
							echo "<td class=\"price\">" . $row['price_eur'] . " € </td>";
							$cart_total_eur += $row['price_eur'];

										/* convert category_id in to category and subcategory */
										$subcategory_id = $row['subcategory_id'];
										$category_idsupl	= "" ;
										$subcategory_idsupl	= "" ;
										// (*) -- conversion of category and subcategory into category%id
											
											// create SELECT query for category and subcategory names from database
											$sql_supl = "SELECT category, subcategory FROM bazaar_category WHERE subcategory_id = "."'$subcategory_id'" ;
											/*$output_supl = mysqli_query($dbc, $sql_supl);
											$row_supl = mysqli_fetch_array($output_supl);
											$category_id	= $row_supl['category'] ;
											$subcategory_id	= $row_supl['subcategory'] ;
											echo "<td>" . $category_id."/".$subcategory_id."</td>";*/
											// execute sql and populate data list with existing category in database
											if($output_supl = mysqli_query($dbc, $sql_supl)){
												if(mysqli_num_rows($output_supl) > 0){  // if any record obtained from SELECT query
													while($row_supl = mysqli_fetch_array($output_supl)){ //next rows outputed in while loop
														
														$category_idsupl	= $row_supl['category'] ;
														$subcategory_idsupl	= $row_supl['subcategory'] ;
														
															
													}
													
													
													// Free result set
													mysqli_free_result($output_supl);
												} else {
													echo "There is no souch category-subcategory in category table. Please correct your error."; // if no records in table
												}
											} else{
												echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
											}

							echo "<td>" . $category_idsupl."/".$subcategory_idsupl."</td>";
							
								$image_location = IMAGE_PATH.$row['screenshot1'];
							echo "<td id=\"gray_under_picture\"> <img  src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"250\"> </td>"; 
							echo '<td colspan="1"><a id="DEL" href="removefromcart.php?cart_number='.$row['cart_number']. '&amp;item_id='. $row['item_id'] . '&amp;name_of_item='. $row['name_of_item'] .'"> <img id="cartadd" src="./images/cartdel.png"> </a></td></tr>'; //construction of GETable link
						echo "</tr>";
						echo " </div> " ;
					}
					echo "</table>";
					echo "<br><br>";
					echo "<p><center><h5>Total price for items in cart: <strong> $cart_total_eur </strong>€ </h5></center></p>";


					// Free result set
					mysqli_free_result($output);
				} else{
					if ($cart_was_submitted) {
						echo '<h5>For further buy please return on main page <a href="index.php">here</a>.</h5>';
					} else {
					    echo '<h5 class="alert alert-warning"> There is no item to buy. For further shopping please return on main bazaar page
						clik <a href="index.php">here<a>.</h5>'; // if no records in table
				    }; 
				}
			} else{
				echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
			}


			// Close connection
			mysqli_close($dbc); 
	  
	  ?>

     <!-- Recapitulation of user delivery adress - important beacause is sent to seller with e-mail about succesfull buy of listened item -->
     <h4> Your delivery adress is: </h4>
		<?php 
		    // connect to a database
		   	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			   // Check connection
			   if($dbc === false){
				   die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
			   }
   
		   // get info about user from database  
		   $users_id = $_SESSION['users_id']; 
		   $sql = "SELECT * FROM bazaar_user WHERE users_id = "."'$users_id'"."LIMIT 1"  ;
		   if($output = mysqli_query($dbc, $sql)){
			   if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
				   
				   while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
				  		   $first_name = $row['first_name'];
						   $lastname_name = $row['lastname_name'];
						   $addresss = $row['addresss'];
						   $city = $row['city'];
						   $ZIPcode = $row['ZIPcode'];
						   $email = $row['email'];

						   ?>
								<div id="frame_green">
								
								<br>
								<h5> Please check your contact and delivery info, these information are important for
											seller of the items for correct contact and delivery! </h5>
										<br>
								<table>
								    <tr>
									    
										
										<td>		 
										<label>e-mail:</label>
										<input type="text"  name="nickname" class="form-control" value="<?php echo $email;  ?>" disabled>
										<br>
										</td>
										<td>
										<label>First name:</label>
										<input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" disabled>
										<br>
										</td>
										<td>
										<label>Last name:</label>
										<input type="text" name="lastname_name" class="form-control" value="<?php  echo $lastname_name;  ?>" disabled>
										<br>
										</td>
									<tr>	
									</tr>	
										<td colspan="3">
										<label>Adress in form - Street Nr.:</label>
										<input type="text"  name="addresss" class="form-control" value="<?php  echo $addresss; ?>" disabled>
										<br>
										</td>
									<tr>	
									</tr>	
										<td colspan="3">
										<label>City:</label>
										<input type="text" name="city" class="form-control" value="<?php echo $city; ?>" disabled>
										<br>
										</td>
									<tr>	
									</tr>	
										<td colspan="3">
										<label>ZIP code in form XXXXX:</label>
										<input type="text" name="ZIPcode" class="form-control" value="<?php  echo $ZIPcode; ?>" disabled>
										</td>
										
									</tr>	
								</table>
								<br>
									    <h5> If any of displayed info need correction, please visit your profile page <a href="editprofile.php"><u>here</u>. </a></h5>
								</div> 

						   
						  <?php									
				   }
				   
				   // Free result set
				   mysqli_free_result($output);
			   } else{
				   echo "Error while reading data."; // if no records in table
			   }
		   } else{
			   echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
		   };
		   // Close connection
			mysqli_close($dbc);        
		?>
		<br>
	  </h4>

       <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <input type="hidden" name="users_id" value="<?php echo $_SESSION['users_id'] ?>" />
		  <h5> For confirmation of buy select YES and click on red button bellow:</h5>
		  <center><input type="radio" name="confirm" value="Yes" /> Yes   <br>
          <input type="radio" name="confirm" value="No" checked="checked" /> No </center><br><br>  
		  <center><button type="submit" name="submit" class="btn btn-danger btn-bg"> I confirm the purchase with the obligation to pay </button> </center>
  
          <br><br>
  	  </form>
	 
	  

	  
	 
	</div>
	

          
		
		
	<?php  // footer include code
			require_once('footer.php'); // including footer
			generate_footer(1060); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>
