<?php  
       require_once("our_function.php");  // way how to import part of code from another page , as another options are include() and require() with extension _once
       /* for further reference please visit https://www.w3schools.com/php/php_includes.asp or 
        *    https://stackoverflow.com/questions/2418473/difference-between-require-include-require-once-and-include-once
        */
        /* example of function with a return value $c1 =5;
         $c2 = 7;
         $v = sucet($c1, $c2);
         echo "vysledok suctu $c1 + $c2 = $v";    */
            // two variables for message and styling of the mesage with bootstrap
            $msg = '';
            $msgClass = '';

            // default values of auxiliary variables

            $is_result = "false"; //before hitting submit button no result is available
            $result = 0; // result and boath number are by default at zero values initialized

            // echo $_SERVER['SERVER_SOFTWARE']; superglobálne asociatívne pole server
// Control if data was submitted
if(filter_has_var(INPUT_POST, 'submit')){
      // Data obtained from $_POST are assigned to local variables
      
      $season = htmlspecialchars($_POST['season']); // fom post global variable $_POST we obtain info about selected season
    
      /**
       *  I. Part for reading temperatures from input text fileds with support of cycle
       */

      for($i=1; $i<=5; $i++) {  // example of FOR array, but as you can see foreach is ideal for work with arrays - this solution is only for demonstration 
            $appropriate_index = "room_temperature_".$i;
            // echo $appropriate_index; // debug
            $room_temperature[$i] = htmlspecialchars($_POST[$appropriate_index]);
            // echo "$room_temperature[$i]" . " ";//debug

      }

      /**
       *  II. Part for calculating average temperature - example of foreach cycle (optimal solution for work with arrays)
       */
      // now we will obtain average temperature with foreach, next line of codes demonstrate usage of different types of cycles in PHP 
      // - all things min, max and average can be obtained
      // by only one iteration per array in real application, please keep that in mind!
      $number_of_temperatures = 0;
      $sum_of_temperatures = 0;
      foreach($room_temperature as $index => $temperature) {
            $number_of_temperatures++ ; // increment number of temperatures
            //echo $temperature;
            $sum_of_temperatures = $sum_of_temperatures + $temperature;
      }

      $average_temperature = $sum_of_temperatures / $number_of_temperatures;

      /**
       *  III. Part for calculating minimal temperature ant their position in row - example of while cycle (only for demonstration purpose - not optimal for real usage in this case of values)
       */
      
      $min_temperature = $room_temperature[1]; // we used our array from second position with index 1 - array is indexed from 0 position, but this position was not used
      $position_of_min_temperature_in_array = 1;
      $index = 1;
      $number_of_elements_in_array = sizeof($room_temperature); // get size of elements in array
      // echo "<br />Debug output: number of position in array is $number_of_elements_in_array"; // debug output - we must adjust correct pas trough of our arry, this is mouch worst solution
                        // against foreach that is for this example ideal solution

      while ($index <=  $number_of_elements_in_array) {
            if ($room_temperature[$index] < $min_temperature ) { // test if appropriate position is not lower as minimal value
                  $min_temperature = $room_temperature[$index];  // if this value is lower, then rewrite it
                  $position_of_min_temperature_in_array = $index; // also minimum position is new

            }
            $index++; // important!!! - variable in condition must be manipulated by programmer, after finite number of iterations condition for run must be not fullfiled 

      }

       /**
       *  IV. Part for calculating maximal temperature ant their position in row - example of do ... while cycle (only for demonstration purpose 
       *                                                                        - not optimal for real usage in this case of values)
       *  (now is reworked with call external function, function can return only one value that is why we used calling by reference for second and third parameter)
       */
       max_temperature_function($room_temperature, $max_temperature, $position_of_max_temperature_in_array); // our new function called by reference (volaná odkazom)
                  /* this part was excluded into  our_function.php and created as separate function for further reuse
                        $max_temperature = $room_temperature[1]; // we expect maximal temperature at first position and next test position by position
                        $position_of_max_temperature_in_array = 1;
                        $index = 1;

                        do {
                              if ($room_temperature[$index] > $max_temperature ) { // test if appropriate position is not lower as minimal value
                                    $max_temperature = $room_temperature[$index];  // if this value is lower, then rewrite it
                                    $position_of_max_temperature_in_array = $index; // also minimum position is new

                              }
                              $index++; // important!!! - variable in condition must be manipulated by programmer, after finite number of iterations condition for run must be not fullfiled 

                        } while ($index <=  $number_of_elements_in_array) ;

                  */
            
      
      $is_result = "true"; // data obtained from get, now result is available

     
      //example of switch operation
      switch ($season) {
        case "winter": {          
                       $msg = "<p>Heating in winter - average inner temperature is $average_temperature<span>&#8451;</span>. Min. temperature is 
                                      $position_of_min_temperature_in_array . in row and is $min_temperature <span>&#8451;</span>. Max. temperature is 
                                      $position_of_max_temperature_in_array . in row and is $max_temperature <span>&#8451;</span>.</p>";  
                       $msgClass = 'alert-info';     
                        }; break;
        case "summer": {          
                      $msg = "<p>Heating in summer - average inner temperature is $average_temperature<span>&#8451;</span>. Min. temperature is 
                      $position_of_min_temperature_in_array . in row and is $min_temperature <span>&#8451;</span>. Max. temperature is 
                      $position_of_max_temperature_in_array . in row and is $max_temperature <span>&#8451;</span>.</p>"; 
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

       if (($average_temperature>=18) && ($average_temperature<=25)) {
            $msg .= "<p> Optimal temerature in your environment.</p>";
      } else {
            $msg .= "<p> Temperatures are not in optimal limits.</p>";
      };
      
}
                 

                     

?> 

<!DOCTYPE html>
<html>
<head>
<title>Ex. 5. PHP & SQL - function and external including.</title>

<!-- <link rel="stylesheet" href="./bootstrap/bootstrap.min.css">  bootstrap mini.css file -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">


</head>
<body>
<div class="container">


  
    <h1 id="Nadpis_headline">PHP language - 5. function end external included code </h1> <!-- Príklad vloženia nadpisu prvej úrovne -->

    

   


      <div class="articles ">
        
          <div class="form-group">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- GET is changed to POST for this exercise. Can you say why?-->
                        <label for="room_temperature_1">Plese provide your room temperature in form XX.X </label><br />
                        <label for="room_temperature_1"> at  08.00:</label>
                        <input type="text" name="room_temperature_1" value="<?php echo isset($_POST['room_temperature_1']) ? $room_temperature[1]: '21.0'; ?>">
                        <span>&#8451;</span>
                        <br />

                        <label for="room_temperature_2">at 10.00:</label>
                        <input type="text" name="room_temperature_2" value="<?php echo isset($_POST['room_temperature_2']) ? $room_temperature[2]: '21.0'; ?>">
                        <span>&#8451;</span>
                        <br />

                        <label for="room_temperature_3">at 12.00:</label>
                        <input type="text" name="room_temperature_3" value="<?php echo isset($_POST['room_temperature_3']) ? $room_temperature[3]: '21.0'; ?>">
                        <span>&#8451;</span>
                        <br />

                        <label for="room_temperature_4">at 14.00:</label>
                        <input type="text" name="room_temperature_4" value="<?php echo isset($_POST['room_temperature_4']) ? $room_temperature[4]: '21.0'; ?>">
                        <span>&#8451;</span>
                        <br />

                        <label for="room_temperature_5">at 16.00:</label>
                        <input type="text" name="room_temperature_5" value="<?php echo isset($_POST['room_temperature_5']) ? $room_temperature[5]: '21.0'; ?>">
                        <span>&#8451;</span>
                        <br />


                        
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
      
      <div class="footer">
         <?php
           my_copyright("Created for demonstration purposes","2021");
         ?>
      </div>
      

</body>
</html>