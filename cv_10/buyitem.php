<!-- ***************************************************************************** -->
<!-- PHP "self" code GET buying selected product                                   -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 31.10.2020 by CDesigner.eu                           -->
<!-- ***************************************************************************** -->

<?php // leading part of page for simple header securing and basic variable setup
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
	
?>

<!-- ******************************************* -->
<!-- script for appropriate scode removal        -->
<!-- ******************************************* -->
<!-- obtain GET data from admin.php and trough   -->
<!-- POST submit remove data from database       -->
<!-- ******************************************* -->
<!DOCTYPE html>
<html>
<head>
	<title> Buy selected item - add to cart  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div class="container" id="header_container_1060">
        <div class="navbar-header">    
          <?php
             require_once('headerlogo.php');
          ?>
          <a class="navbar-brand" href="index.php">Return to main bazaar page <img id="next" src="./images/next_icon.png"></a>
        </div>
      </div>
    </nav>
    <div class="container" id="container_1060">	
		
    	
	  <?php if($msg != ''): ?> <!-- alert showing part -->
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
       
     

      

       
            
      <?php // code for GET info about what to remove and submit removing approval

      /* structure of generated link on admin.php page for further reference
       echo '<td colspan="1"><a id="DEL" href="removeitem.php?item_id='.$row['item_id'] . '&amp;name_od_item='
                         . $row['name_of_item'] . '&amp;price_eur='. $row['price_eur'] .
                         '&amp;published='. $row['published'] . '&amp;screenshot1='. $row['screenshot1'] .
                         '&amp;screenshot2='. $row['screenshot2'] . '&amp;screenshot3='. $row['screenshot3'] . '"> >>Publish/UnPub./Remove  </a></td></tr>';
      */

        if(isset($_GET['item_id'])){
            // take a data from GET link generated by adminscript
            $item_id = htmlspecialchars($_GET['item_id']);
           
           

        } else if (isset($_POST['item_id'])) { //grab score from POST - different behavior for removal
            
            // $item_id = htmlspecialchars($_POST['item_id']);
            
           

        }  else  { //error info message
            echo '<p class="alert alert-danger"> Please specify any item for buy. </p>';

        };

        if(isset($_POST['submit'])){
             
            if($_POST['confirm'] == 'Yes' ){ // change cart_item to user_id if item is confirmed for buy
              //read all data from $_POST array
              $item_id = htmlspecialchars($_POST['item_id']);
              $name_of_item = htmlspecialchars($_POST['name_of_item']);
              $price_eur = htmlspecialchars($_POST['price_eur']);
              $published = htmlspecialchars($_POST['published']);
              $screenshot1 = htmlspecialchars($_POST['screenshot1']);
              $screenshot2 = htmlspecialchars($_POST['screenshot2']);
              $screenshot3 = htmlspecialchars($_POST['screenshot3']);

              

              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

              // get user id from selected session
              $users_id = $_SESSION['users_id'];

              //create sql query along selected operation
                    
                    $sql = "UPDATE bazaar_item SET cart_number ="."'$users_id'"." WHERE item_id = $item_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> The item <strong>' . $name_of_item . '</strong> with id <strong>' . $item_id . '</strong> was succesfully added into a cart. </p>';
                  
              
             

              // close database connection
              mysqli_close($dbc);



             

              

             

           
            } else {
                echo  '<p class="alert alert-danger" > The selected operation cannot be performed. </p>'; 
            }
        } else if (isset($item_id)  ) {
             
            // show short describtion of score for deletion
           
              
              //generating removing confirmation form      
            
               /******************************************************************/
               /*     Create detail info about selected product                  */
               /******************************************************************/

              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

              //create sql query along selected operation
              
              $sql = "SELECT * FROM bazaar_item WHERE item_id = $item_id LIMIT 1";
                  
                   
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    if($output = mysqli_query($dbc, $sql)){
                        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
                            // Leading part of product before table output
                        $first_run = 0;    
                        $row = mysqli_fetch_array($output); //next rows outputed in while loop
                            $item_id = $row['item_id'];
                            $screenshot1 = $row['screenshot1'];
                            $screenshot2 = $row['screenshot2'];
                            $screenshot3= $row['screenshot3'];
                            $name_of_item = $row['name_of_item'];
                            $price_eur = $row['price_eur'];
                            $published = $row['published'];
                            $image_location = IMAGE_PATH.$screenshot1;

                            echo '<br>';
                            echo '<h4>Are you sure to add '. $name_of_item .' item to shoping cart for buy?</h4> ';
                            echo '<p> <strong> Price: </strong> ' . $price_eur .  '€<br> <strong> Name: </strong>' . $name_of_item .
                                  
                                 
                                           
                                 '</p>'; 
                            echo " <img src=\"$image_location\" alt=\" score image \"  height=\"350\"> ";

                            echo '<br><br>';
                            // create table output
                    echo '<form method="POST" action="buyitem.php">';    // begining of form submission
                            echo "<table>"; //head of table
                                echo "<tr>";
                                    //echo "<th>item_id</th>";
                                    echo "<th>name</th>";
                                    //echo "<th>published?</th>";
                                    echo "<th>date of listening</th>";
                
                                    echo "<th>price</th>";
                                    echo "<th>category id</th>";
                                    echo "<th>photo 1</th>";
                                    echo "<th>photo 2</th>";
                                    echo "<th>photo 3</th>";
                                    echo "<th>description</th>";
                                    echo "<th colspan=\"2\">Select yes and use BUY button</th>";
                                    
                                    
                                    
                                    
                                echo "</tr>";
                           // while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                                echo " <div class=\"mailinglist\"> " ;
                                echo "<tr>";
                                    //echo "<td>" . $row['item_id'] . "</td>";
                                    echo "<td>" . $row['name_of_item'] . "</td>";
                                    /* if ($row['published']) { // show if published - set 1 or waiting set to 0
                                        echo "<td> ok-Published </td>";
                                    } else {
                                        echo "<td> X-waiting </td>";
                                    } */
                                    
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
                                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"125\"> </td>"; 
                                    if ($row['screenshot2']) {
                                    $image_location = IMAGE_PATH.$row['screenshot2'];
                                        echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product second \"  height=\"125\"> </td>"; 
                                    } else {
                                        echo "<td id=\"gray_under_picture\"> not provided </td>"; 
                                    };
                                    if ($row['screenshot3']) {
                                    $image_location = IMAGE_PATH.$row['screenshot3'];
                                       echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product third \"  height=\"125\"> </td>"; 
                                    } else  {
                                       echo "<td id=\"gray_under_picture\"> not provided </td>"; 
                                    };
                                     // description of product
                                     if ($row['item_description']) {
                                     echo "<td>" . $row['item_description'] . "</td>";
                                     } else {
                                        echo "<td>Seller does not provided any description for product ... </td>";
                                    };
                                     
                                    
                                    // set no to yes for adding item to cart and set user_id to cart_number
                                    echo "<td id=\"frame_red\">";
                                            echo '<input type="radio" name="confirm" value="Yes" /> <strong> Yes </strong>  '; 
                                            echo "<br>";
                                            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
                                            
                                            echo '<input type="hidden" name="item_id" value="'.$item_id.'"  />'; 
                                            echo '<input type="hidden" name="price_eur" value="'.$price_eur.'"  />';
                                            echo '<input type="hidden" name="name_of_item" value="'.$name_of_item.'" />'; 
                                            echo '<input type="hidden" name="published" value="'.$published.'" />'; 
                                            echo '<input type="hidden" name="screenshot1" value="'.$screenshot1.'" />'; 
                                            echo '<input type="hidden" name="screenshot2" value="'.$screenshot2.'" />'; 
                                            echo '<input type="hidden" name="screenshot3" value="'.$screenshot3.'" />'; 
                                            
                                    echo "</td>";
                                    
                                echo "</tr>";
                                echo " </div> " ;
                            }
                            echo "</table>";
                            echo "<br>";
                            echo '<center><input type="submit" class="btn btn-danger btn-lg" value="buy" name="submit" /></center>'; 

                    echo '</form>';  // end of form


/*
                            echo '<form method="POST" action="removeitem.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send
                            echo '<h4> Please select your operation </h4>';

                            echo '<input list="operation" name="operation" placeholder="select" >';
                            echo '<datalist id="operation">';
                            echo '<option value="publish">';
                            echo '<option value="unpublish">';
                            echo '<option value="delete">';
                            echo '</datalist>';
                        

                            echo '<br><br>';

                            
                            echo '<input type="radio" name="confirm" value="Yes" /> Yes   '; 
                            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
                            
                            echo '<input type="hidden" name="item_id" value="'.$item_id.'"  />'; 
                            echo '<input type="hidden" name="price_eur" value="'.$price_eur.'"  />';
                            echo '<input type="hidden" name="name_of_item" value="'.$name_of_item.'" />'; 
                            echo '<input type="hidden" name="published" value="'.$published.'" />'; 
                            echo '<input type="hidden" name="screenshot1" value="'.$screenshot1.'" />'; 
                            echo '<input type="hidden" name="screenshot2" value="'.$screenshot2.'" />'; 
                            echo '<input type="hidden" name="screenshot3" value="'.$screenshot3.'" />'; 
                            echo '<input type="submit" class="btn btn-danger" value="submit" name="submit" />'; 
                            echo '</form>'; 

                            */
                         
                    };         
           
              
             

              // close database connection
              mysqli_close($dbc);
            /* obvious part from remove script
            echo '<form method="POST" action="removeitem.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send
            echo '<h4> Please select your operation </h4>';
                 

            echo '<input list="operation" name="operation"  >';
            echo '<datalist id="operation">';
            echo '<option value="publish">';
            echo '<option value="unpublish">';
            echo '<option value="delete">';
            echo '</datalist>';
            
            echo '<br>';

            
            echo '<input type="radio" name="confirm" value="Yes" /> Yes   '; 
            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
            
            echo '<input type="hidden" name="item_id" value="'.$item_id.'"  />'; 
            echo '<input type="hidden" name="price_eur" value="'.$price_eur.'"  />';
            echo '<input type="hidden" name="name_of_item" value="'.$name_of_item.'" />'; 
            echo '<input type="hidden" name="published" value="'.$published.'" />'; 
            echo '<input type="hidden" name="screenshot1" value="'.$screenshot1.'" />'; 
            echo '<input type="hidden" name="screenshot2" value="'.$screenshot2.'" />'; 
            echo '<input type="hidden" name="screenshot3" value="'.$screenshot3.'" />'; 
            echo '<input type="submit" class="btn btn-danger" value="submit" name="submit" />'; 
            echo '</form>'; */


                
        };

        echo '<br><br>';
        echo  '<p> <a href = "index.php"> <img id="next" src="./images/previous_icon.png"> Back to main Bazaar  page. </a></p>';

?>
	  

	  
		
		</div>

          
		
		
        <?php  // footer include code
            require_once('footer.php'); // including footer
            generate_footer(1060); // function from footer.php for seting width, you can use 580 and 1060px width
        ?>  
		
      
</body>
</html>