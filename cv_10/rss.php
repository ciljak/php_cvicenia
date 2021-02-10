<?php header('Content-Type: text/xml'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">

<channel>
<title>Bazaar - items for sell feed </title>
<link>http://localhost/bazaar/ </link>
<description>Latest items for sell on example Bazaar app created for educational purposes. </description>
<language>en-gb</language>

<?php // main part read info about items for sell and generate feed posts

session_start(); // start the session - must be added on all pages for session variable accessing

// solution using SESSIONS with COOKIES for longer (30days) login persistency

if(!isset($_SESSION['users_id'])) { // if session is no more active
    if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
        $_SESSION['users_id'] = $_COOKIE['users_id'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
    }
 }

 /**
  *  Main part of generator newsfeed
  */

  // connect to the database
  require_once('appvars.php'); // including variables for database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
    // Check connection
        if($dbc === false){
            die("ERROR: Could not connect to database. " . mysqli_connect_error());
        }

  // Obtain all listed items for sell
  $sql = "SELECT * FROM bazaar_item"  ;
  //go through the array of items for sell and format it as RSS
  
			if($output = mysqli_query($dbc, $sql)){
				if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
					
					while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                            //$first_name_buyer = $row['first_name'];
                        echo  '<item>'; 
                        echo  '<title>'.$row['name_of_item'].'</title>'; 
                        echo  '<link> http://localhost/bazaar/item.php?item_id='.$row['item_id'].'</link>';
                        echo  '<pubDate>'.$row['item_add_date'].' '.date('T').'</pubDate>'; 
                        echo  '<description>'.substr($row['item_description'], 0, 64).'</description>'; 
                        
                        
                        echo  '</item>'; 
																			
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



?>
</channel>
</rss>