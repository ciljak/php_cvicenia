<!-- ******************************************************************* -->
<!-- PHP included code for cart icon with number of items displaing      -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 17. - 18.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->
<?php
   $_user_id = $_SESSION['users_id'];
   $_number_of_items_in_cart ="-";
   $_total_price ="0";

   /*********************************************************
    * Count mumber of items in cart and total item price
    */

    	/* Attempt MySQL server connection. Assuming you are running MySQL
			 */
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			// Check connection
			if($dbc === false){
				die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
			}

			
						
			// read all rows (data) from guestbook table in "test" database
				
			$sql = "SELECT * FROM bazaar_item WHERE cart_number="."'$_user_id'"." ORDER BY item_id ASC ";  // read items marked in cart_number with appropriate users_id
			/*************************************************************************/
			/*  Output in Table - solution 1 - for debuging data from database       */
			/*************************************************************************/
			// if data properly selected from guestbook database tabele
			
			

			if($output = mysqli_query($dbc, $sql)){
				if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
					// create table output
					
                    $_total_price = 0; // initialize cariable calculating total price for items in cart
                    $_number_of_items_in_cart =0;
					while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
						
                            $_total_price += $row['price_eur'];
                            $_number_of_items_in_cart += 1;

										
					}
					

					// Free result set
					mysqli_free_result($output);
				} else {
					echo ""; // if no records in table
				}
			} else {
				echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
			}


			// Close connection
			mysqli_close($dbc); 

   //debug

   echo '&nbsp;  &nbsp; &nbsp; &nbsp;  <span class="cart"> <a class="navbar-brand" href="cart.php"> <img id="cart" src="./images/small_cart.png" alt="cart small icon" width="35" height="35"><strong>(' .$_number_of_items_in_cart .')  ' .$_total_price .' €</strong></a> </span>';
   // add some space with &nbsp;
?>