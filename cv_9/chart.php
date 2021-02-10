<!-- ******************************************************************* -->
<!-- PHP code of actual chart of submitted benchmarks                    -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 4.10.2020 by CDesigner.eu                  -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Benchmark live charts  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">3dmark live result chart v 1.0</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/benchmark.jpg" alt="benchmark image" width="150" height="150">
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

echo "<h4>Chart of benchmark results</h4>";
echo "<br>";


echo ' <button class="btn btn-success btn-lg " onclick="location.href=\'index.php\'" type="button"> >>> Add your score here <<< </button>';
		  
//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
					//echo "<th>id</th>"; this line is changed to incrementing number showing position of the score in chart
					echo "<th id=\"chart_table_header\">position</th>";
                    echo "<th id=\"chart_table_header\">score</th>";
                    echo "<th id=\"chart_table_header\">nickname</th>";
                    echo "<th id=\"chart_table_header\">date of post</th>";
                    echo "<th id=\"chart_table_header\">screenshot</th>";
                    
                    
				echo "</tr>";
			$position = 1;	// first initialization of position
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
				//echo " <div class=\"mailinglist\"> " ; class identification must differ first, two second and other position
				switch ($position) { // along position from first to bottom displayed rows are marked with different names and recolored in style.css
					case 1: $display='first' ; break;
					case 2:
					case 3: $display='secondandthree' ; break;
					default: $display='others' ;

				};
					
                echo "<tr>";
					//echo "<td>" . $row['id'] . "</td>"; this line is changed to incrementing number showing position of the score in chart
					echo "<td id=\"$display\">" . $position++ . "</td>"; // increases number of position after disply by one
                    echo "<td id=\"$display\">" . $row['score'] . "</td>";
                    echo "<td id=\"$display\">" . $row['nickname'] . "</td>";
                    echo "<td id=\"$display\">" . $row['write_date'] . "</td>";
                    $image_location = IMAGE_PATH.$row['screenshot'];
                        echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" score image \"  height=\"95\"> </td>"; 
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