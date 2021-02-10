<!-- ******************************************************************* -->
<!-- PHP "self" code handling managenet of bazaar portal                 -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 18.10-XX.X.2020 by CDesigner.eu            -->
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
    $results_per_page  = 10; // results per page 

	
	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> bazaar - page administration </title>
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
    <div class="container" id="container_1060">	<!-- wider container for admin page - width 1060px - styled in style.css-->

    <br> 
	  <h4> Aministration of Bazaar app </h4>

<!-- *************************************************** -->
<!-- HTML part available after succesfull login as admin -->
<!-- *************************************************** -->		
<?php if(isset($_SESSION['users_id']) && ($_SESSION['user_role']=='admin')) { //if user is loged with users_id then editprofile form is available?> 		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/admin.png" alt="adminimage" width="150" height="150">
        <br>

     
          
         
         	 
		  

		  
		  
		  
		  
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

// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}
// ----------------------------------------------------------------------------------- SHOWING ADMIN TABLES ----------------------------------
/********************************************************************************************/
/* I. Showing items for publish/unpublish and delete table with option for removal          */
/********************************************************************************************/   
// querying bazaar_category for listed category items            
// read all rows (data) from guestbook table in "test" database


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
					$sql ="SELECT * FROM bazaar_item  ";  // read in reverse order of score - highest score first
					$output_for_number_rows_count_2 = mysqli_query($dbc, $sql); // query database
					$total_2 = mysqli_num_rows($output_for_number_rows_count_2);	//get number of rows in databse	
					
//older approach without SORT functionality                    	
// $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first

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

// processing table output form bazaar_category
echo "<h4>I. Manage list of items for sell </h4>";
echo "<br>";
// buttons for access to other pages
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'sellitem.php\'" type="button">  Create new item page -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    // functionality for ordering result
                    /**
                     * SORTING - PART I. Here are generated GET links for UP/DOWN ordering by appropriate category
                     */
                    echo '<th>item_id <br /><a id="SORT" href="admin.php?sort_by=item_id&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=name&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>'; //order 1 up -1 down
                    echo '<th>name <br /><a id="SORT" href="admin.php?sort_by=name&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=name&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>published? <br /><a id="SORT" href="admin.php?sort_by=published&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=published&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>date<br /><a id="SORT" href="admin.php?sort_by=date&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=date&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';

					echo '<th>price <br /><a id="SORT" href="admin.php?sort_by=price&amp;order=1"><img id="arrow" src="./images/arrowup.png"></a>
                    <a id="SORT" href="admin.php?sort_by=price&amp;order=-1"><img id="arrow" src="./images/arrowdown.png"></a></th>';
					echo '<th>category id<br /><a id="SORT" href="admin.php?sort_by=category&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=category&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo "<th>photo 1</th>";
                    echo "<th>photo 2</th>";
                    echo "<th>photo 3</th>";
                    echo "<th colspan=\"2\">Manage</th>";
                    
                    
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['item_id'] . "</td>";
                    echo "<td>" . $row['name_of_item'] . "</td>";
                    if ($row['published']==1) { // show if published - set 1 or waiting set to 0
                        echo '<td><span class="green"> ok-Published </spann></td>';
                    } else if ($row['published']== 0){ // 1 published, 0 unpublished, -1 sold ready for deletion
                        echo '<td><span class="gray"> X-waiting </spann></td>';
                    } else if ($row['published']== -1){ // 1 published, 0 unpublished, -1 sold ready for deletion
                        echo '<td><span class="red"> sold - can be deleted! </spann></td>';
                    } 
                    
                    echo "<td>" . $row['item_add_date'] . "</td>";

                    echo "<td>" . $row['price_eur'] . " € </td>";
                   // echo "<td>" . $row['subcategory_id'] . "</td>";
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
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"95\"> </td>"; 
                    $image_location = IMAGE_PATH.$row['screenshot2'];
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product second \"  height=\"95\"> </td>"; 
                    $image_location = IMAGE_PATH.$row['screenshot3'];
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product third \"  height=\"95\"> </td>"; 

					 // removal line with removing link line
                
					 
					// echo "<td  colspan=\"1\"> Manage entry: </td>"; // description on first line
						 echo '<td colspan="1"><a id="DEL" href="removeitem.php?item_id='.$row['item_id'] . '&amp;name_of_item='
                         . $row['name_of_item'] . '&amp;price_eur='. $row['price_eur'] .
                         '&amp;published='. $row['published'] . '&amp;screenshot1='. $row['screenshot1'] .
                         '&amp;screenshot2='. $row['screenshot2'] . '&amp;screenshot3='. $row['screenshot3'] . '"><img id="next" src="./images/pubunpubremove.png"> </a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
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
            echo "<br>";
            echo "<hr>";
            // Free result set
            mysqli_free_result($output);
        } else{
            echo "There is no benchmark result in chart. Please wirite one."; // if no records in table
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
    }


/*************************************************************************/
/*  II. Showing category/ subcategory table with option for removal          */
/*************************************************************************/ 
$results_per_page_2 = 8;  
//calculate pagination information
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
$skip = (($cur_page -1) * $results_per_page_2);	

// first  question to database table for obtaining number of published items in a database - obtain value for $total
$sql ="SELECT * FROM bazaar_category";  // read in reverse order of score - highest score first
$output_for_number_rows_count_2 = mysqli_query($dbc, $sql); // query database
$total_2 = mysqli_num_rows($output_for_number_rows_count_2);	//get number of rows in databse	

// querying bazaar_category for listed category items            
// read all rows (data) from guestbook table in "test" database

//$sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC LIMIT $skip, $results_per_page_2";  // read in reverse order of score - highest score first

//older approach without SORT functionality                    	
// $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first

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


// processing table output form bazaar_category
echo "<h4>II. Manage List of active categories and subcategories on store</h4>";
echo "<br>";
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'managecategory.php\'" type="button">  Create new category-subcategory -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo '<th>subcategory_id <br /><a id="SORT" href="admin.php?sort_by=subcategory_id&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=subcategory_id&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>category <br /><a id="SORT" href="admin.php?sort_by=category&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=category&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo '<th>subcategory <br /><a id="SORT" href="admin.php?sort_by=subcategory&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=subcategory&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo '<th></th>';
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
						 . $row['category'] . '&amp;subcategory='. $row['subcategory'] .'"><center><img id="next" src="./images/delicon.png"></center></a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
                echo "</tr>";
                echo " </div> " ;
            }
            echo "</table>";
            echo "<br />";
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


/*************************************************************************/
/*  III. Showing registered user table with option for adminster them    */
/*************************************************************************/  
$results_per_page_3 = 8;  
//calculate pagination information
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
// results per page default declater as 5 on top of page and changed in submitt part after reset button handling $results_per_page = 5;
$skip = (($cur_page -1) * $results_per_page_3);	

// first  question to database table for obtaining number of published items in a database - obtain value for $total
$sql ="SELECT * FROM bazaar_user";  // read in reverse order of score - highest score first
$output_for_number_rows_count_3 = mysqli_query($dbc, $sql); // query database
$total_3 = mysqli_num_rows($output_for_number_rows_count_3);	//get number of rows in databse	 
// querying bazaar_category for listed category items            


//older approach without SORT functionality                    	
// $sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC LIMIT $skip, $results_per_page";  // read in reverse order of score - highest score first

/**
 * SORTING - PART II. Here is into sql request implemented along which filed and how ascend or desc is output ordered
 */
if(isset($_GET['sort_by']) && isset($_GET['order']) ){
	// take a data from GET link generated by adminscript
	$sort_by = htmlspecialchars($_GET['sort_by']);
	$order = htmlspecialchars($_GET['order']);
	// debug echo "sort_by".$sort_by;
    // debug echo "order".$order;
    if(($sort_by == "users_id") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_user ORDER BY users_id ASC LIMIT $skip, $results_per_page_3";
	};

	if(($sort_by == "users_id") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_user ORDER BY users_id DESC LIMIT $skip, $results_per_page_3";
    }; 

	if(($sort_by == "username") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_user ORDER BY username ASC LIMIT $skip, $results_per_page_3";
	};

	if(($sort_by == "username") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_user ORDER BY username DESC LIMIT $skip, $results_per_page_3";
    };    
        
    if(($sort_by == "nickname") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_user ORDER BY nickname ASC LIMIT $skip, $results_per_page_3";
    };

    if(($sort_by == "nickname") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_user ORDER BY nickname DESC LIMIT $skip, $results_per_page_3"; 
    }; 
    
    if(($sort_by == "write_date") && ($order == "1")) { // along name and ASC order
        $sql = "SELECT * FROM bazaar_user ORDER BY write_date ASC LIMIT $skip, $results_per_page_3";
    };

    if(($sort_by == "write_date") && ($order == "-1")) { // along name and DESC order
        $sql = "SELECT * FROM bazaar_user ORDER BY write_date DESC LIMIT $skip, $results_per_page_3"; 
    }; 

	if(($sort_by == "user_role") && ($order == "1")) { // along price and ASC order
        $sql = "SELECT * FROM bazaar_user ORDER BY user_role ASC LIMIT $skip, $results_per_page_3";
	};

	if(($sort_by == "user_role") && ($order == "-1")) { // along price and DESC order
        $sql = "SELECT * FROM bazaar_user ORDER BY user_role DESC LIMIT $skip, $results_per_page_3";
	};

	if(($sort_by == "default")) { // along category and DESC order
        
        $sql = "SELECT * FROM bazaar_user ORDER BY users_id ASC LIMIT $skip, $results_per_page_3"; 
	};

} else {  // first run without ordering - no get link generated
    $sql = "SELECT * FROM bazaar_user ORDER BY users_id ASC LIMIT $skip, $results_per_page_3";   // read in reverse order of score - highest score first
}


// processing table output form bazaar_category
echo "<h4>III. Manage List of active bazaar users and roles</h4>";
//echo "<br>";
//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'managecategory.php\'" type="button">  Create new category-subcategory -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo '<th>users_id <br /><a id="SORT" href="admin.php?sort_by=users_id&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=users_id&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>username <br /><a id="SORT" href="admin.php?sort_by=username&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=username&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo '<th>nickname <br /><a id="SORT" href="admin.php?sort_by=nickname&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=nickname&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>write_date <br /><a id="SORT" href="admin.php?sort_by=write_date&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=write_date&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
                    echo '<th>user_role <br /><a id="SORT" href="admin.php?sort_by=user_role&amp;order=1"> <img id="arrow" src="./images/arrowup.png"> </a>
                    <a id="SORT" href="admin.php?sort_by=user_role&amp;order=-1"> <img id="arrow" src="./images/arrowdown.png"> </a></th>';
					echo "<th>manage</th>";
                    
                    
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['users_id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['nickname'] . "</td>";
                    echo "<td>" . $row['write_date'] . "</td>";
                    echo "<td>" . $row['user_role'] . "</td>";
					 // removal line with removing link line
                
					 
					// echo "<td  colspan=\"1\"> Manage entry: </td>"; // description on first line
						 echo '<td colspan="1"><a id="DEL" href="manageuser.php?users_id='.$row['users_id'] . '&amp;username='
						 . $row['username'] . '&amp;user_role='. $row['user_role'] .'"><center><img id="next" src="./images/manageuser.png"></center></a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
                echo "</tr>";
                echo " </div> " ;
            }
            echo "</table>";
             //count nuber of pages total
             $num_pages_3 = ceil($total_3 / $results_per_page_3);
                
             //generate navigational page links if we have more than one page
             
             if($num_pages_3 > 1) {
                 $user_search = ""; // not implemented yet, then set as clear values
                 if(empty($sort_by)) { // if not obtained by get then default order is applied
                     $sort_by="default";
                 };
                 if(empty($order)) { // if not obtained by get then default order is applied
                     $order="1";
                 };
                 
                 // included function for pagination generation function stored in functions.php page
                 echo generate_page_links($user_search, $sort_by, $order, $cur_page, $num_pages_3);
                 echo "<br><br>";
             }
            echo "<br />";
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

<!-- ***************************************** -->
<!-- HTML part displayed for unloged user      -->
<!-- ***************************************** --> 
<?php } else { // else if user is not loged then form will noot be diplayed?>  
    <?php if(isset($_SESSION['users_id']) && $_SESSION['user_role']!='admin') {     ?>  
        <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>You must by loged with administrator role for admin. Please<a class="navbar-brand" href="logout.php"><h4><u>logout</u></h4></a>and the login as admin.</h4>
        <br>
    <?php } else {     ?>  
        <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>For further page administration please log in <a class="navbar-brand" href="login.php"> here. </a></h4>
        <br>
    <?php }      ?>   

<?php } ?>  
	  

	  
		
		</div>

          
		
		
        <?php  // footer include code
            require_once('footer.php'); // including footer
            generate_footer(1060); // function from footer.php for seting width, you can use 580 and 1060px width
        ?> 
		
      
</body>
</html>