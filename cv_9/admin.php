<!-- ******************************************************************* -->
<!-- PHP "self" code handling administration of removal                  -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 27-XX.X.2020 by CDesigner.eu               -->
<!-- ******************************************************************* -->

<?php // leading part of page for simple header securing and basic variable setup
    require_once('appvars.php'); // including variables for database
    require_once('authorize.php'); // authorization script for simple header authorization
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
		
		
?>

<!-- ******************************************* -->
<!-- HTML code for benchmarkchart administration -->
<!-- ******************************************* -->
<!DOCTYPE html>
<html>
<head>
	<title> Benchmark - admin  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="admin.php">3dmark results chart v 1.0 - admin section</a>
          <a class="navbar-brand" href="index.php"> --> return to main score page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?> <!-- alert showing part -->
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
      <br> <!-- logo on the center of the page -->
        <img id="calcimage" src="./images/admin.jpg" alt="Calc image" width="150" height="150">
      <br>

       
            
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

echo "<h4>Administration of benchmark result posts</h4>";
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

                // removal line with removing link line
                
                echo "<tr>";
                echo "<td  colspan=\"3\"> Manage content: </td>"; // description on first line
                    echo '<td colspan="2"><a id="DEL" href="remove.php?id='.$row['id'] . '&amp;score='
                    . $row['score'] . '&amp;nickname='. $row['nickname'] . '&amp;write_date='
                    . $row['write_date'] . '&amp;screenshot='. $row['screenshot'] .'"> DEL - Remove score </a></td></tr>'; //construction of GETable link
                    // for remove.php input
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