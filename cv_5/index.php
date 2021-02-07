<!DOCTYPE html>
<html>
<head>
<title>Ex. 2. PHP & SQL - expressions and string operation.</title>
<link rel="stylesheet" href="style.css">


</head>
<body>
<div class="container">


  
    <h1 id="Nadpis_headline">PHP language - 2. expression and string operation</h1> <!-- Príklad vloženia nadpisu prvej úrovne -->

    <h2>How to embeed PHP code into a page?</h2>

   


      <div class="articles">
        
        <?php
              echo "<p>Basic expression example:</p> "; 
                    // there is option for disabling show of notice or other error messages 
                     // error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

                     // Report all errors except E_NOTICE
                     // error_reporting(E_ALL & ~E_NOTICE);

              $x = "new";
              $y = $x;

              // basic operators and priority of them

              $c1 = 5.5;
              $c2 = 2;

              $r1 = $c1 + $c2;
              $r2 = $c1 - $c2;
              $r3 = $c1 * $c2;
              $r4 = $c1 / $c2;
              $modulos = $c1 % $c2;

              echo "<p>Modulo of $c1 and $c2 is $modulos</p>";

              // work with strings
              $part_1 = "New";
              $part_2 = "world";
              $part_3 = "2";

              // adding strings
              $new_sentence = $part_1 . $part_2;  // . concatenating of strings
              echo  "<p>$part_1 . $part_2 =  $new_sentence</p>";

              // interesting ways how operation can be interpreted

              $part_4 = "2 worlds";

              $if_string_contains_number = $part_4 * 2;
              echo "<p> $part_4 * 2 = $if_string_contains_number</p>";


              // another example of interpretation of string and numeric variable
              $p1 = 2;
              $p2 = "25";

              $v1 = $p1 + $p2; // addas numeric value
              $v2 = $p1 . $p2; // concatenated as a strings

              echo "<p> $p1 + $p2 = $v1 but $p1 . $p2 = $v2 </p>";

              // incrementaton or decrementation
              $a=0;
              echo "<p> a = $a </p>";
              $a++;
              echo "<p> a++ = $a </p>"; // evaluation and then incrementation
              ++$a;
              echo "<p> ++a = $a </p>";  // increment and next evaluate expression

              //comparisn of variables
              $a = 22;
              $b = 22.00;

              $c = $a == $b; // both variable must be same value
              $d = $a === $b; // both same value but also same type!!!

              echo "<p> is a=22 === b=22.00 - $d </p>"; 

              //bool operators ! &&  ||

              //bitwise operators

              //ternary or podmienený operátor

              $day = date('d'); // more about date can be read at https://www.w3schools.com/php/php_date.asp
             
              echo ($day%2) ? "<p class=\"red\">Today is $day. - odd day</p>" : "<p class=\"green\">Today is $day. - even day</p>";

                 

                     


        ?>  

      </div>  
</div>

</body>
</html>