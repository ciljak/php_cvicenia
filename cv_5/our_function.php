<?php
/**
 * Example of external function location - all functon located in this fie are available form line after including or requiring this file in another
 * document
 */

 function my_copyright($text_for_display,$year) { // function for generating footer copyright
     
     echo "<span class=\"text_center\"> $text_for_display, &copy $year </span> ";
     
 }


function max_temperature_function($room_temperature, &$max_temperature, &$position_of_max_temperature_in_array) { // first parameter is passed by value and next two by reference
    $max_temperature = $room_temperature[1]; // we expect maximal temperature at first position and next test position by position
    $position_of_max_temperature_in_array = 1;
    $index = 1;

    $number_of_elements_in_array = sizeof($room_temperature); // get size of elements in array

    do {
          if ($room_temperature[$index] > $max_temperature ) { // test if appropriate position is not lower as minimal value
                $max_temperature = $room_temperature[$index];  // if this value is lower, then rewrite it
                $position_of_max_temperature_in_array = $index; // also minimum position is new

          }
          $index++; // important!!! - variable in condition must be manipulated by programmer, after finite number of iterations condition for run must be not fullfiled 

    } while ($index <=  $number_of_elements_in_array) ;
 }

 function sucet($x, $y) {
       $sucet = $x + $y;
       return $sucet;
 }


?>