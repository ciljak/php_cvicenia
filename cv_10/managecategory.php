<!-- ******************************************************************* -->
<!-- PHP "self" code handling adding adding category by admin of page    -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 11.10-11.10.2020 by CDesigner.eu            -->
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
	$category = "";
	$subcategory = "";
	
	$is_result = false; //before hitting submit button no result is available
	


	
        
    // If category with subcategory data was submitted
	if(filter_has_var(INPUT_POST,'subcategorysubmit')){
		// Data obtained from $_postmessage are assigned to local variables
        $subcategory = htmlspecialchars($_POST['subcategory']);
        $category = htmlspecialchars($_POST['category']);

        //echo "$category in subcategory debug ";
        //cho "$subcategory in subcategory debug ";
        
		
		
		

		// Controll if all required fields was written
		if(!empty($category && $subcategory) ){
			
            // add category into a databse - there will by fields without subcategory but they will by omited for showing

			// make database connection
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			// Check connection
				if($dbc === false){
					die("ERROR: Could not connect to database. " . mysqli_connect_error());
				}
			
			
			   

			   // create INSERT query
			   $sql = "INSERT INTO bazaar_category (category, subcategory) 
						VALUES ('$category','$subcategory')";



				if(mysqli_query($dbc, $sql)){
					
					$msg = 'New category ' . $category . ' sucesfully added into a bazaar_category table.';
					$msgClass = 'alert-success';

					// clear entry fileds after sucessfull deleting from database
					$category= "";
                   
                    $is_result = false; //before hitting submit button no result is available
				} else{
					
					$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					$msgClass = 'alert-danger';
				}

			// end connection
				mysqli_close($dbc);

			}
			
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all * marked contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		};    

		
  
	

	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> bazaar - category management  </title>
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
        <img id="calcimage" src="./images/addicon.png" alt="Calc image" width="150" height="150">
        <br>

     
          
          <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group">
		      <label>* Set name for new subcategory:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['subcategory']) ? $subcategory : ''; ?>'" name="subcategory" class="form-control" value="<?php echo isset($_POST['subcategory']) ? $subcategory : 'Please provide name of new subcategory'; ?>">
              <br> 
              <label>* Select main category for nesting created subcategory:</label>
		      <input list="category" name="category" >
                <datalist id="category">
					<?php // here read data from mysql bazaar_category and display existing category whre subcategory will be nested
					 	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

						    // Check connection
							 if($dbc === false){
								 die("ERROR: Could not connect to database. " . mysqli_connect_error());
							 };
						 
						 
							
			 
							// create SELECT query for category names from database
							$sql = "SELECT DISTINCT category FROM bazaar_category ORDER BY category ASC";

							// execute sql and populate data list with existing category in database
							if($output = mysqli_query($dbc, $sql)){
								if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
									
									while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
									
											echo "<option value=" . $row['category'] . ">";
											
											
									
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
              <br> 
			  
              <button type="submit" name="subcategorysubmit" class="btn btn-warning"> Create new subcategory </button>
			  <input type="reset" class="btn btn-info" value="Reset">			  
	      </div>
          <hr> 
          </form> 
         	 
		  

		  
		  
		  
		  
          <br><br>
		  
		  
		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
                               <td><h5> <em> Category: </em> $category with subcategory $subcategory </h5> <h5> has been succesfully added to category list </h5> ";
                                  
						
						  
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

$results_per_page_2 = 8;  
//calculate pagination information
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
$skip = (($cur_page -1) * $results_per_page_2);	

// first  question to database table for obtaining number of published items in a database - obtain value for $total
$sql ="SELECT * FROM bazaar_category";  // read in reverse order of score - highest score first
$output_for_number_rows_count_2 = mysqli_query($dbc, $sql); // query database
$total_2 = mysqli_num_rows($output_for_number_rows_count_2);	//get number of rows in databse	

// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}


    
            
// read all rows (data) from guestbook table in "test" database
//$sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC";  // read in reverse order of score - highest score first

/**
 * SORTING - PART II. Here is into sql request implemented along which filed and how ascend or desc is output ordered
 */
if(isset($_GET['sort_by']) && isset($_GET['order']) ){
	// take a data from GET link generated by adminscript
	$sort_by = htmlspecialchars($_GET['sort_by']);
	$order = htmlspecialchars($_GET['order']);
	// debug echo "sort_by".$sort_by;
    // debug echo "order".$order;
    if(($sort_by == "subcategory_id") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_category ORDER BY subcategory_id ASC LIMIT $skip, $results_per_page_2";  

	};

	if(($sort_by == "subcategory_id") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_category ORDER BY subcategory_id DESC LIMIT $skip, $results_per_page_2";
    }; 

	    
    if(($sort_by == "subcategory") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_category ORDER BY subcategory ASC LIMIT $skip, $results_per_page_2";
    };

    if(($sort_by == "subcategory") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_category ORDER BY subcategory DESC LIMIT $skip, $results_per_page_2"; 
    }; 

	
	if(($sort_by == "category") && ($order == "1")) { // along category and ASC order
        $sql = "SELECT * FROM bazaar_category ORDER BY category ASC LIMIT $skip, $results_per_page_2";
	};

	if(($sort_by == "category") && ($order == "-1")) { // along category and DESC order
        $sql = "SELECT * FROM bazaar_category ORDER BY category DESC LIMIT $skip, $results_per_page_2";
	};

	if(($sort_by == "default")) { // along category and DESC order
        
        $sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC LIMIT $skip, $results_per_page_2";  // read in reverse order of score - highest score first

	};

} else {  // first run without ordering - no get link generated
    $sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC LIMIT $skip, $results_per_page_2";  // read in reverse order of score - highest score first

}

/*************************************************************************/
/*  Output in Table - listening all category in bazaar_category table    */
/*************************************************************************/
// if data properly selected from guestbook database tabele

echo "<h4>List of active categories and subcategories</h4>";
echo "<br>";
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'admin.php\'" type="button">  admin page -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo '<th>subcategory_id <br /><a id="SORT" href="managecategory.php?sort_by=subcategory_id&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="managecategory.php?sort_by=subcategory_id&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>category <br /><a id="SORT" href="managecategory.php?sort_by=category&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="managecategory.php?sort_by=category&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo '<th>subcategory <br /><a id="SORT" href="managecategory.php?sort_by=subcategory&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="managecategory.php?sort_by=subcategory&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo "<th></th>";
					echo "<th>delete category</th>";
                    
                    
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['subcategory_id'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
					echo "<td>" . $row['subcategory'] . "</td>";
					 // removal line with removing link line
                
					 
					 echo "<td  colspan=\"1\"> Manage entry: </td>"; // description on first line
						 echo '<td colspan="1"><a id="DEL" href="removecategory.php?subcategory_id='.$row['subcategory_id'] . '&amp;category='
						 . $row['category'] . '&amp;subcategory='. $row['subcategory'] .'"><center><img id="next" src="./images/delicon.png"></center> </a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
                echo "</tr>";
                echo " </div> " ;
            }
			echo "</table>";
			
			//count nuber of pages total
			$num_pages_2 = ceil($total_2 / $results_per_page_2);
                
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

          
		
		
		<?php  // footer include code
			require_once('footer.php'); // including footer
			generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>  
		
      
</body>
</html>