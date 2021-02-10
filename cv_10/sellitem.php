<!-- ******************************************************************* -->
<!-- PHP "self" code handling adding item to sell by registered user     -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 17.10.2020 by CDesigner.eu                 -->
<!-- ******************************************************************* -->

<?php
	require_once('appvars.php'); // including variables for database
	require_once('functions.php'); // include external functions - generating links for pagination
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
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')) {
		// Data obtained from $_postmessage are assigned to local variables
		$name_of_item = htmlspecialchars($_POST['name_of_item']);
		$price_eur = htmlspecialchars($_POST['price_eur']);
		//$users_id = htmlspecialchars($_POST['users_id']);
		$users_id = $_SESSION['users_id']; // now reworked to obtain users_id from SESSION value
		//echo 'users_id'; echo $users_id;
		
		$category_subcategory = htmlspecialchars($_POST['category_subcategory']); // must be converted to subcategory_id (*)
			// separate category and subcategory with strtok() function 
			$words = explode('-', $category_subcategory);
			$category=$words[0];
			//echo $category;
			//echo '<br>';
			$subcategory=$words[1];
			//echo $subcategory;
		
		
		$screenshot1 = htmlspecialchars($_FILES['screenshot1']['name']);
		$screenshot2 = htmlspecialchars($_FILES['screenshot2']['name']);
		$screenshot3 = htmlspecialchars($_FILES['screenshot3']['name']);
		$item_description = htmlspecialchars($_POST['item_description']);

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


		

		// Controll if all required fields was written
		if(!empty($name_of_item) && !empty($price_eur) && !empty($subcategory_id) && !empty($screenshot1)) { // these item identifiers are mandatory and can not be empty
			// If check passed - all needed fields are written
			// Check if E-mail is valid
			

                
                // move image to /images final folder from demporary download location
				$target1 = IMAGE_PATH . $screenshot1;
				$target2 = IMAGE_PATH . $screenshot2;
				$target3 = IMAGE_PATH . $screenshot3;

				// !!! Add entry to the database and redraw all score in chart list descending from highest score

				   // insert into databse 
                      if (move_uploaded_file($_FILES['screenshot1']['tmp_name'], $target1)) {
							move_uploaded_file($_FILES['screenshot2']['tmp_name'], $target2);
							move_uploaded_file($_FILES['screenshot3']['tmp_name'], $target3);
							// make database connection
							$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
							// Check connection
								if($dbc === false){
									die("ERROR: Could not connect to database. " . mysqli_connect_error());
								}
							
							// INSERT new entry
						
							$sql = "INSERT INTO bazaar_item (name_of_item, price_eur, subcategory_id, users_id, item_add_date, screenshot1, screenshot2, screenshot3, item_description) 
							VALUES ('$name_of_item', $price_eur , '$subcategory_id' , '$users_id' , now(), '$screenshot1', '$screenshot2', '$screenshot3', '$item_description' )";
							//show added item true
							$is_result = true; 


							if(mysqli_query($dbc, $sql)){
								
								$msg = 'New item '.$name_of_item. ' for '. $price_eur. ' € succesfully added to sell item - waiting for admin approvall.';
								$msgClass = 'alert-success';
							} else {
								
								$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
								$msgClass = 'alert-danger';
							}

							// end connection
								mysqli_close($dbc);
								
			
			
						} else {
							// Failed - if not all fields are fullfiled
							$msg = 'Please fill in all * marked contactform fields';
							$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
						};
			};				

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
      <div class="container" id="header_container_580">
        <div class="navbar-header">    
		<?php 
		   require_once('headermenu.php');
		?>	 
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
	  <?php endif; ?>	
	  
	  <br> 
	  <h4> Add your item for sell listening ... </h4>

<!-- *************************************************** -->
<!-- HTML part available after succesfull login as user -->
<!-- *************************************************** -->		
<?php if(isset($_SESSION['users_id']) ) { //if user is loged with users_id then editprofile form is available?> 
        
        <br> 
        <img id="calcimage" src="./images/sell-product.png" alt="Calc image" width="150" height="150">
        <br>

      <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
	      <div class="form-group">
		      <label>* Please provide name of selling item:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['name_of_item']) ? $name_of_item : ''; ?>'" name="name_of_item" class="form-control" value="<?php echo isset($_POST['name_of_item']) ? $name_of_item : 'Name for product'; ?>">
              

			  <label>* Please provide price for item in €:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['price_eur']) ? $price_eur : ''; ?>'" name="price_eur" class="form-control" value="<?php echo isset($_POST['price_eur']) ? $price_eur : 'Price in €'; ?>">
              
			  <!-- slection of category and subcategory -->
              <label>* Select main category-subcategory for proper item listing on bazar pages:</label>
		      <input list="category_subcategory" name="category_subcategory" >
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
	      
          <p> In this part you can select upto 3 pictures of the product. First picture is required! </p>
          <label>* Please select location of your score screenshot from drive - max 5MB!</label>
          <div class="custom-file">
          
	      <input type="file" name="screenshot1" class="custom-file-input" id="screenshot1" lang="en" onchange="getFilename(this)">
              <label class="custom-file-label1 custom-file-label"  for="customFile">Screenshot1 - required:</label>

			
			 
		  </div>	 

          <div class="custom-file">
		  <input type="file" name="screenshot2" class="custom-file-input" id="screenshot2" lang="en" >
              <label class="custom-file-label2 custom-file-label" for="customFile">Screenshot2 - optional:</label>

			 
			  
             
          </div>

		  <div class="custom-file">
		  <input type="file" name="screenshot3" class="custom-file-input" id="screenshot3" lang="en" >
              <label class="custom-file-label3 custom-file-label" for="customFile">Screenshot3 - optional:</label>
			  	
			  	    
			  </div>

			  <script type="application/javascript"> // javascript handling chaging filename of selected file
               $(document).ready(function(){
				$("#screenshot1").change(function(){
					//alert("A file 1 has been selected.");
                    var thefile1 = document.getElementById('screenshot1');
                    
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
		 
          
		  <div class="form-group">
	      	<label>* Item description:</label>  <!-- textera for input large text -->
	      	<textarea id="item_description" onfocus="this.value='<?php echo isset($_POST['item_descriptio']) ? $item_description : 'Please provide description of selling item ...'; ?>'" name="item_description" class="form-control" rows="3" cols="50"><?php echo isset($_POST['item_description']) ? $item_description : 'Description of item for sell goes here ...'; ?></textarea>
	      </div>

		  

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Add item for sell </button>
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
		  
		  <!-- remove comment after implementation
		  <button type="submit" name="delete" class="btn btn-danger"> Delete recently posted score </button>
          -->
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
          <br><br>
		  

		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
							   <td><h5> <em> Item for selll: </em> $name_of_item for $price_eur €  </h5> <h5> has been succesfully added to selling list. Item will be visible
							   on bazaar page after admin approval. </h5> ";
                               $image_location = IMAGE_PATH.$screenshot1;
                        echo " <img src=\"$image_location\" alt=\" score image \"  height=\"150\"> ";       
						
						  
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

 /***
 *  Display pagination on the page - part included to listening in this area
 */ 

//calculate pagination information
$results_per_page = 5;
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
$skip = (($cur_page -1) * $results_per_page);		

		// Check connection
		if($dbc === false){
			die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
		}

// first  question to database table for obtaining number of published items in a database - obtain value for $total
$sql ="SELECT * FROM bazaar_item  ";  // read in reverse order of score - highest score first
$output_for_number_rows_count_2 = mysqli_query($dbc, $sql); // query database
$total_2 = mysqli_num_rows($output_for_number_rows_count_2);	//get number of rows in databse	

// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}


    
            
// read all rows (data) from guestbook table in "test" database
//$sql = "SELECT * FROM bazaar_item ORDER BY item_id DESC";  // read in reverse order of score - highest score first
/**
 * SORTING - PART II. Here is into sql request implemented along which filed and how ascend or desc is output ordered
 */
if(isset($_GET['sort_by']) && isset($_GET['order']) ){
	// take a data from GET link generated by adminscript
	$sort_by = htmlspecialchars($_GET['sort_by']);
	$order = htmlspecialchars($_GET['order']);
	// debug echo "sort_by".$sort_by;
    // debug echo "order".$order;
    if(($sort_by == "item_id") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY item_id ASC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "item_id") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY item_id DESC LIMIT $skip, $results_per_page";
    }; 

	if(($sort_by == "name") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY name_of_item ASC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "name") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY name_of_item DESC LIMIT $skip, $results_per_page";
    };    
        
    if(($sort_by == "published") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY published ASC LIMIT $skip, $results_per_page";
    };

    if(($sort_by == "published") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY published DESC LIMIT $skip, $results_per_page"; 
    }; 
    
    if(($sort_by == "date") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date ASC LIMIT $skip, $results_per_page";
    };

    if(($sort_by == "date") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page"; 
    }; 

	if(($sort_by == "price") && ($order == "1")) { // along price and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY price_eur ASC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "price") && ($order == "-1")) { // along price and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY price_eur DESC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "category") && ($order == "1")) { // along category and ASC order
        $sql = "SELECT * FROM bazaar_item ORDER BY subcategory_id ASC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "category") && ($order == "-1")) { // along category and DESC order
        $sql = "SELECT * FROM bazaar_item ORDER BY subcategory_id DESC LIMIT $skip, $results_per_page";
	};

	if(($sort_by == "default")) { // along category and DESC order
        
        $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page"; 
	};

} else {  // first run without ordering - no get link generated
    $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page";   // read in reverse order of score - highest score first
}

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
                    echo '<th>id <br /><a id="SORT" href="sellitem.php?sort_by=item_id&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="sellitem.php?sort_by=name&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>Name <br /><a id="SORT" href="sellitem.php?sort_by=name&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="sellitem.php?sort_by=name&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a</th>';
                    echo '<th>Price <br /><a id="SORT" href="sellitem.php?sort_by=price&amp;order=1"><img id="arrow" src="./images/arrowup.png"></a>
                    <a id="SORT" href="sellitem.php?sort_by=price&amp;order=-1"><img id="arrow" src="./images/arrowdown.png"></a></th>';
                    echo '<th>Category <br /><a id="SORT" href="sellitem.php?sort_by=category&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="sellitem.php?sort_by=category&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo "<th>Screenshot1</th>";
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['item_id'] . "</td>";
                    echo "<td>" . $row['name_of_item'] . "</td>";
                    echo "<td>" . $row['price_eur'] . "</td>";
                    echo "<td>" . $row['subcategory_id'] . "</td>";
                    $image_location = IMAGE_PATH.$row['screenshot1'];
                        echo "<td> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"95\"> </td>"; 
                echo "</tr>";
                echo " </div> " ;
            }
			echo "</table>";
			  /***
			   *  Display pagination on the page - part included to listening in this area
			   */ 
								//Pagination support code - count nuber of pages total
								$num_pages_2 = ceil($total_2 / $results_per_page);
								
								//generate navigational page links if we have more than one page
								
								if($num_pages_2 > 1) {
									$user_search = ""; // not implemented yet, then set as clear values
									if(empty($sort_by)) { // if not obtained by get then default order is applied
										$sort_by="default";
									};
									if(empty($order)) { // if not obtained by get then default order is applied
										$order="1";
									};
									// included function for pagination generation function stored in functions.php page
									echo generate_page_links($user_search, $sort_by, $order, $cur_page, $num_pages_2);
									
									echo "<br><br>";
										}
			echo "<br />";
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

<!-- ***************************************** -->
<!-- HTML part displayed for unloged user      -->
<!-- ***************************************** --> 
<?php } else { // else if user is not loged then form will noot be diplayed?>  
    
     
        <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>For listening items for sell you must be loged in <a class="navbar-brand" href="login.php"> here. </a></h4>
        <br>
      

<?php } ?>  
	  

	  
		
		</div>

          
		
		
		<?php  // footer include code
			require_once('footer.php'); // including footer
			generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>  
		
      
</body>
</html>