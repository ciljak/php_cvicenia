<!-- ******************************************************************* -->
<!-- PHP "self" code handling homepage of bazaar                         -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 17. - 18.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
	require_once('appvars.php'); // including variables for database
	require_once('functions.php'); // include external functions - generating links for pagination
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

	if(isset($_SESSION['results_per_page']) ) {

		$results_per_page = $_SESSION['results_per_page'];
	} else {
		$results_per_page = 5;
	}
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')) {
		// Data obtained from $_postmessage are assigned to local variables
	
		//echo 'users_id'; echo $users_id;
		
		$category_subcategory = htmlspecialchars($_POST['category_subcategory']); // must be converted to subcategory_id (*)
			// separate category and subcategory with strtok() function 
			$words = explode('-', $category_subcategory);
			$category=$words[0];
			//echo $category;
			//echo '<br>';
			$subcategory=$words[1];
			//echo $subcategory;
		
			$_SESSION['interest_category'] = $category;
			$_SESSION['interest_subcategory'] = $subcategory;
		

		// (*) -- conversion of category and subcategory into category%id
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

					// Check connection
					if($dbc === false){
						die("ERROR: Could not connect to database. " . mysqli_connect_error());
					};
				
				    
					

					// create SELECT query for category names from database
					$sql = "SELECT subcategory_id FROM bazaar_category WHERE category = "."'$category'". " AND subcategory = "."'$subcategory'" ;

					// execute sql and populate data list with existing category in database
					if($output = mysqli_query($dbc, $sql)){
						if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
							while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
								
								$subcategory_id	= $row['subcategory_id'] ;
								$_SESSION['subcategory_id'] = $subcategory_id	;
								$is_result = true; // result can be shown
									
							}
							
							
							// Free result set
							mysqli_free_result($output);
						} else {
							echo "There is no souch category-subcategory in category table. Please correct your error."; // if no records in table
						}
					} else{
						echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
					}


					// Close connection
					mysqli_close($dbc);


		

		

	};	
  
	

	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
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
		$is_result = false;
		
	};

	// nr of pages in results changed nr_of_pages
	if(filter_has_var(INPUT_POST, 'nr_of_pages')){
		  
		$results_per_page  = htmlspecialchars($_POST['number_per_page']);
		$_SESSION['results_per_page'] = $results_per_page;
		
		
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar app by CDesigner.eu  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div  id="header_container_1060">
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
        <img id="calcimage" src="./images/bazaar.png" alt="bazaar image" width="150" height="150">
        <br>

      <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
	      <div class="form-group">
		  <label>* Select category-subcategory of product for salle:</label>
		  <input list="category_subcategory" name="category_subcategory" placeholder="please select">
                <datalist id="category_subcategory"> <!-- must be converted in subcategory_id in script - marked with (*) -->
					<?php // here read data from mysql bazaar_category and display existing category whre subcategory will be nested
					 	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

						    // Check connection
							 if($dbc === false){
								 die("ERROR: Could not connect to database. " . mysqli_connect_error());
							 };
						 
						 
							
			 
							// create SELECT query for category names from database
							$sql = "SELECT DISTINCT category, subcategory FROM bazaar_category ORDER BY category ASC, subcategory ASC";

							// execute sql and populate data list with existing category in database
							if($output = mysqli_query($dbc, $sql)){
								if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
									
									while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
									
											echo "<option value=" . $row['category'] ."-".$row['subcategory'] . ">";
											
											
									
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
                                     
                </datalist>
				<p> If no proper category-subcategory exist, please contact admin of the pages for creation them for you. </p>
              
			  

				<!-- users_id from session obtaining - for debuging and testing is set as hidden -->
				<input type="hidden" name="users_id" value="1">





			  
	      </div>
	      
         

			  
            
           

          <br><br>
		 
          
		  

		  

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Show me interesting things! </button>
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
		  <?php
		    if(isset($_SESSION['users_id']) && ($_SESSION['user_role'] == 'user' || $_SESSION['user_role'] == 'admin') ) {
		      echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'sellitem.php\'" type="button">  Make your sell listening -> </button>';
		    }
		  ?>
		   
          <br><br>
		  	 
		
	  </form>
	  <?php 
	  
	  // show only if result is awaylable after category and subcategory of interesting items selected

			// Controll if all required fields was written
            if (isset($_SESSION['interest_category'])) { // if in session variable is set category and subcategroy, then recreate them
				$category = $_SESSION['interest_category'];
				$subcategory = $_SESSION['interest_subcategory'];
				$subcategory_id =  $_SESSION['subcategory_id'];
				$is_result = true;
			}

			if($is_result ) { 
				$is_result = false;
				if(!empty($category) && !empty($subcategory) ) { // these item identifiers are mandatory and can not be empty
					// If check passed - show only interesting items from sell listening
					/* Attempt MySQL server connection. Assuming you are running MySQL
							server with default setting (user 'root' with no password) */
							$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

					
					/***
					 *  Display pagination on the page - part included to listening in this area
					*/ 
					
					//calculate pagination information
					$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
					// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
					$skip = (($cur_page -1) * $results_per_page);		

							// Check connection
							if($dbc === false){
								die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
							}

					// first  question to database table for obtaining number of published items in a database - obtain value for $total
					$sql ="SELECT * FROM bazaar_item WHERE published="."'1'"." AND subcategory_id = "."'$subcategory_id'"." AND cart_number="."'0'"." ORDER BY item_id DESC";  // read in reverse order of score - highest score first
					$output_for_number_rows_count_2 = mysqli_query($dbc, $sql); // query database
					$total_2 = mysqli_num_rows($output_for_number_rows_count_2);	//get number of rows in databse	
					//echo "skip".$skip;
					//echo "results per page".$results_per_page;								
										
							// read all rows (data) from guestbook table in "test" database
							$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND subcategory_id = "."'$subcategory_id'"." AND cart_number="."'0'"." ORDER BY item_id DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first
							                                       // must be piblished then set 1                        and is not added to somebody cart - cart_number is default 0                                     
							/*************************************************************************/
							/*  Output in Table - solution 1 - for debuging data from database       */
							/*************************************************************************/
							// if data properly selected from guestbook database tabele

							echo "<h4>List of items in selected category: $category / $subcategory </h4>";
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
												echo "<th>More info</th>";
												
												
											echo "</tr>";
										while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
											echo " <div class=\"mailinglist\"> " ;
											echo "<tr>";
												//echo "<td>" . $row['item_id'] . "</td>";
												echo "<td class=\"item_name\">" . $row['name_of_item'] . "</td>";
												echo "<td class=\"price\">" . $row['price_eur'] . " € </td>";
												echo "<td>" . $category."/".$subcategory."</td>";
												
													$image_location = IMAGE_PATH.$row['screenshot1'];
												echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"250\"> </td>"; 
												echo '<td colspan="1"><a id="DEL" href="item.php?item_id='.$row['item_id']. '"> >> Visit item page  </a></td></tr>'; //construction of GETable link
											echo "</tr>";
											echo " </div> " ;
										}
										echo "</table>";
										//count nuber of pages total
										$num_pages_2 = ceil($total_2 / $results_per_page);
										
										//generate navigational page links if we have more than one page
										
										if($num_pages > 1) {
											$user_search = ""; // not implemented yet, then set as clear values
											if(empty($sort_by)) { // if not obtained by get then default order is applied
												$sort_by="default";
											};
											if(empty($order)) { // if not obtained by get then default order is applied
												$order="1";
											};
											
											// included function for pagination generation function stored in functions.php page
											echo generate_page_links($user_search, $sort_by, $order, $cur_page, $num_pages);
											echo "<br><br>";
										}
										// Free result set
										mysqli_free_result($output);
									} else{
										echo "There is no item for sell. Please add one."; // if no records in table
									}
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
								}



							// Close connection
							mysqli_close($dbc);
					
					
					};
			};						



/*************************************************************************/
/*  Output in paginated form                                             */
/*************************************************************************/

 /***
  *  Display pagination on the page - part included to listening in this area
  */
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

//GET data for pagination send to page herself

//calculate pagination information
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
$skip = (($cur_page -1) * $results_per_page);





// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}

// first  question to database table for obtaining number of published items in a database - obtain value for $total
$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY item_id DESC ";  // read in reverse order of score - highest score first              
$output_for_number_rows_count = mysqli_query($dbc, $sql); // query database
$total = mysqli_num_rows($output_for_number_rows_count);	//get number of rows in databse				
    
            
//older approach without SORT functionality read all rows (data) from guestbook table in "test" database
// $sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY item_id DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first

/**
 * SORTING - PART II. Here is into sql request implemented along which filed and how ascend or desc is output ordered
 */
if(isset($_GET['sort_by']) && isset($_GET['order']) ){
	// take a data from GET link generated by adminscript
	$sort_by = htmlspecialchars($_GET['sort_by']);
	$order = htmlspecialchars($_GET['order']);
	// debug echo "sort_by".$sort_by;
	// debug echo "order".$order;

	if(($sort_by == "name") && ($order == "1")) { // along name and ASC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY name_of_item ASC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "name") && ($order == "-1")) { // along name and DESC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY name_of_item DESC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "price") && ($order == "1")) { // along price and ASC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY price_eur ASC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "price") && ($order == "-1")) { // along price and DESC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY price_eur DESC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "category") && ($order == "1")) { // along category and ASC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY subcategory_id ASC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "category") && ($order == "-1")) { // along category and DESC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY subcategory_id DESC LIMIT $skip, $results_per_page"; 
	};

	if(($sort_by == "default")) { // along category and DESC order
		$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY item_id DESC LIMIT $skip, $results_per_page"; 
	};

} else {  // first run without ordering - no get link generated
    $sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY item_id DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first
}



/*************************************************************************/
/*  Output in Table - solution 1 - for debuging data from database       */
/*************************************************************************/
// if data properly selected from guestbook database tabele
echo "<br><br>";
echo "<h4>Latest added items for you! </h4>";
echo "<br>";

/***
 *  Obtaining wished number of item per page - option for select
 */
?>
<form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
<div class="form-group">
<label> Set expected number of items per page -5 is default:</label>
<input list="number_per_page" name="number_per_page" placeholder="please select or write nr.">
	  <datalist id="number_per_page"> <!-- must be converted in subcategory_id in script - marked with (*) -->
	      <option value="5">
		  <option value="10">
		  <option value="15">	
		  <option value="20">	
		  <option value="50">
		  <option value="100">	    
		</datalist>
	 
	
	

	  <!-- users_id from session obtaining - for debuging and testing is set as hidden -->
	 
	  <button type="submit" name="nr_of_pages" class="btn btn-warning"> Use selected number of pages! </button>
</div>




</form>


<?php

echo "<br>"; echo "<br>";

if($output = mysqli_query($dbc, $sql)){
	if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
		// create table output
		echo "<table>"; //head of table
			echo "<tr>";
				//echo "<th>id</th>";
				// functionality for ordering result
				/**
				 * SORTING - PART I. Here are generated GET links for UP/DOWN ordering by appropriate category
				 */
				echo '<th>Name  <br /><a id="SORT" href="index.php?sort_by=name&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
				                <a id="SORT" href="index.php?sort_by=name&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a> </th>'; //order 1 up -1 down
				echo '<th>Price <br /><a id="SORT" href="index.php?sort_by=price&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
			                	<a id="SORT" href="index.php?sort_by=price&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
				echo '<th>Category <br /><a id="SORT" href="index.php?sort_by=category&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
				                <a id="SORT" href="index.php?sort_by=category&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a>	</th>';
				echo "<th>Screenshot1</th>";
				echo "<th>More info</th>";
				
				
			echo "</tr>";
		while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
			echo " <div class=\"mailinglist\"> " ;
			echo "<tr>";
				//echo "<td>" . $row['item_id'] . "</td>";
				echo "<td class=\"item_name\">" . $row['name_of_item'] . "</td>";
				echo "<td class=\"price\">" . $row['price_eur'] . " € </td>";

							/* convert category_id in to category and subcategory */
							$subcategory_id = $row['subcategory_id'];
							$category_idsupl	= "" ;
							$subcategory_idsupl	= "" ;
							// (*) -- conversion of category and subcategory into category%id
								
								//create SELECT query for category and subcategory names from database
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
				echo '<td colspan="1"><a id="DEL" href="item.php?item_id='.$row['item_id']. '"><img id="next" src="./images/next.png">   </a></td></tr>'; //construction of GETable link
			echo "</tr>";
			echo " </div> " ;
		}
		echo "</table>";
		//count nuber of pages total
		$num_pages = ceil($total / $results_per_page);
		
		//generate navigational page links if we have more than one page
		
		if($num_pages > 1) {
			$user_search = ""; // not implemented yet, then set as clear values
			if(empty($sort_by)) { // if not obtained by get then default order is applied
				$sort_by="default";
			};
			if(empty($order)) { // if not obtained by get then default order is applied
				$order="1";
			};
			
			// included function for pagination generation function stored in functions.php page
			echo generate_page_links($user_search, $sort_by, $order, $cur_page, $num_pages);
			echo "<br><br>";
		}
		// Free result set
		mysqli_free_result($output);
	} else{
		echo "There is no item for sell. Please add one."; // if no records in table
	}
} else{
	echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
}


// Close connection
mysqli_close($dbc);


?>
	  

	  
		
		</div>

		<?php  // footer include code
			require_once('footer.php'); // including footer
			generate_footer(1060); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>         
		
    	
		
      
</body>
</html>