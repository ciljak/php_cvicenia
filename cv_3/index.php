<?php    // script obtaining data from form imput in heading part of html
              echo "<p>This is output from heading script, this is usable only for debuging output but not for generating content in main page.</p> "; 
             
            // two variables for message and styling of the mesage with bootstrap
            $msg = '';
            $msgClass = '';

            // default values of auxiliary variables

            $is_result = "false"; //before hitting submit button no result is available
            $result = 0; // result and boath number are by default at zero values initialized

            // echo $_SERVER['SERVER_SOFTWARE']; superglobálne asociatívne pole server
// Control if data was submitted
if(filter_has_var(INPUT_GET, 'submit')){
      // Data obtained from $_POST are assigned to local variables
      $room_temperature = htmlspecialchars($_GET['room_temperature']);
      $season = htmlspecialchars($_GET['season']);
     
      
      $is_result = "true"; // data obtained from get, now result is available

     
      //example of switch operation
      switch ($season) {
        case "winter": {          
                       $msg = "<p>Heating in winter - inner temperature is $room_temperature<span>&#8451;</span>.</p>";  
                       $msgClass = 'alert-info';     
                        }; break;
        case "summer": {          
                      $msg = "<p>Heating in summer - inner temperature is $room_temperature<span>&#8451;</span>.</p>"; 
                      $msgClass = 'alert-warning';    
                        }; break;
        default: { 
                       echo "Warning only winter and summer can be accepted as input."; 
                        }; break;                
 				 		 
      };

     /* if ($room_temperature>=25) {
            $msg .= "<p> A bit internal overheating :-).</p>";
      };

      if ($room_temperature<18) {
            $msg .= "<p> Too cold for this time :-(.</p>";
      }; */

       if (($room_temperature>=18) && ($room_temperature<=25)) {
            $msg .= "<p> Optimal temerature in your environment.</p>";
      } else {
            $msg .= "<p> Temperatures are not in optimal limits.</p>";
      };
      
}
                 

                     

?> 

<!DOCTYPE html>
<html>
<head>
<title>Ex. 3. PHP & SQL - if and switch.</title>

<!-- <link rel="stylesheet" href="./bootstrap/bootstrap.min.css">  bootstrap mini.css file -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">


</head>
<body>
<div class="container">


  
    <h1 id="Nadpis_headline">PHP language - 3. if and switch with simple input </h1> <!-- Príklad vloženia nadpisu prvej úrovne -->

    

   


      <div class="articles ">
        
          <div class="form-group">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">

                        <label for="room_temperature">Plese provide your room temperature in form XX.X:</label>
                        <input type="text" name="room_temperature" value="<?php echo isset($_GET['room_temperature']) ? $room_temperature: '21.0'; ?>">

                        <span>&#8451;</span>
                        <br /><br />
                        <label for="season">Select current season: </label>
                        <select name="season" id="season" size="2">
                           <option value="winter" selected="selected">Winter</option>
                           <option value="summer">Sumer</option>
                        </select>
                        <br /><br />

                        <button type="submit" name="submit" class="btn btn-primary center"> Send your temperature </button>      
                  
                  </form>
            </div>

                  <?php if($msg != ''): ?>  <!-- This part show  message after sending room temperature -->
						<br><br>	
    		        <div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
		      <?php endif; ?>
            
      </div>  
</div>

</body>
</html>