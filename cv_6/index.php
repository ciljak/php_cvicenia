<!-- ************************************************* -->
<!-- PHP "self" code handling calculation              -->
<!-- ************************************************* -->
<!-- Vrsion: 1.0        Date: 1.9.2020 by CDesigner.eu -->

<?php
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$operator = ''; // at the beggining is no operator selected
	$is_result = "false"; //before hitting submit button no result is available
	$result = 0; // result and boath number are by default at zero values initialized


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')){
		// Data obtained from $_POST are assigned to local variables
		$nr1 = htmlspecialchars($_POST['nr1']);
		$nr2 = htmlspecialchars($_POST['nr2']);
		$operator = htmlspecialchars($_POST['operation']); 
		
		$is_result = "true";

		// calculation of appropriate results
		/* if ($operator == "+") {          
			$result = $nr1 + $nr2;     
		};
		if ($operator == "-") {          
			$result = $nr1 - $nr2;     
		};
		if ($operator == "*") {          
			$result = $nr1 * $nr2;     
		};
		if ($operator == "/") {          
			$result = $nr1 / $nr2;     
		};
        */
		//this part can be reworked for switch command for educational purposes like this
		switch ($operator) {
		  case "+": {          
			           $result = $nr1 + $nr2;     
					}; break;
		  case "-": {          
						$result = $nr1 - $nr2;     
					 }; break;
		  case "*": {          
						$result = $nr1 * $nr2;     
					 }; break;
		  case "/": {   
						if ($nr2 != 0)  { //dividing by zero mittigation, if nr2 is zero then error message is outputed
							$result = $nr1 / $nr2;
							$msg = '';} 
							else { 
								$msg = 'Dividing by zero NaN'; // text of the message
				                $msgClass = 'alert-danger'; // bootstrap type alert-danger is outputed
							 } ;  
						     
					 }; break;	
		  case "Power": {          
						$result = Pow($nr1, $nr2);     
					 }; break;	
		  case "Log10": {          
						$result = Log10($nr1);     
					 }; break;	
		  case "sin": {          
						$result = sin($nr1);     
					 }; break;	
		  case "cos": {          
						$result = cos($nr1);     
					 }; break;	
		  case "tg": {          
						$result = tan($nr1);     
					 }; break;				 		 
		};
		
	}
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Simple PHP calc  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Simple PHP calc v. 1.0</a>
        </div>
      </div>
    </nav>
    <div class="container">	
    	
		<img id="calcimage" src="./images/calc.png" alt="Calc image" width="200" height="200">
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <div class="form-group">
		      <label>First number - n1:</label>
		      <input type="text" name="nr1" class="form-control" value="<?php echo isset($_POST['nr1']) ? $nr1 : '0'; ?>">
	      </div>
	      <div class="form-group">
	      	<label>Second number - n2:</label>
	      	<input type="text" name="nr2" class="form-control" value="<?php echo isset($_POST['nr2']) ? $nr2 : '0'; ?>">
	      </div>
	      
		  <div class="form-group">
			  <label>Select your operation:</label> <br>
			  <table class="table table-secondary ">
			    <tr>
				    <td>
					<input class="inputSelector" type="radio" id="+" name="operation" value="+"  checked> 
					<label> <h4> n1 + n2 </h4></label>
		            </td>
					<td>
					<input class="inputSelector" type="radio" id="-" name="operation" value="-"  <?php echo ($operator == "-") ? "checked" : ''; ?> >  
					<label><h4> n1 - n2</h4></label>
					</td>
					<td>
					<input class="inputSelector" type="radio" id="Power" name="operation" value="Power"  <?php echo ($operator == "Power") ? "checked" : ''; ?> > 
					<label><h4> Power n1 on n2 </h4></label> 
					</td>
		       </tr>
				<tr>		
					<td>
					
					<input class="inputSelector" type="radio" id="*" name="operation" value="*"  <?php echo ($operator == "*") ? "checked" : ''; ?>  >  
					<label><h4> n1 * n2</h4></label>
					</td>
					<td>
					<input class="inputSelector" type="radio" id="/" name="operation" value="/"  <?php echo ($operator == "/") ? "checked" : ''; ?> > 
					<label><h4>/</h4></label> 
					</td>
					<td>
					<input class="inputSelector" type="radio" id="Log10" name="operation" value="Log10"  <?php echo ($operator == "Log10") ? "checked" : ''; ?> >  
					<label><h4>log10(n1)</h4></label>
					</td>
				</tr>	
				<tr>		
					<td>
					<input class="inputSelector" type="radio" id="sin" name="operation" value="sin"  <?php echo ($operator == "sin") ? "checked" : ''; ?>  >  
					<label><h4> sin(n1)</h4></label>
					</td>
					<td>
					<input class="inputSelector" type="radio" id="cos" name="operation" value="cos"  <?php echo ($operator == "cos") ? "checked" : ''; ?> > 
					<label><h4>cos(n1)</h4></label> 
					</td>
					<td>
					<input class="inputSelector" type="radio" id="tg" name="operation" value="tg"  <?php echo ($operator == "tg") ? "checked" : ''; ?> >  
					<label><h4>tg(n1)</h4></label>
					</td>
		        </tr>	
		      </table>
			</div>
	      <br>

	      <button type="submit" name="submit" class="btn btn-primary"> Calculate result </button>

		  <?php   //($is_result == "true") ? {          
			     // echo "<label> = </label> ";
				 // echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;   
				 //    } : ''; 
				 if ($is_result && $msg == '') {
					$result = number_format($result, 2, ',', ' '); // formating number refer to https://www.php.net/manual/en/function.number-format.php

						echo "<br> <br>";
						 echo " <table class=\"table table-success\"> ";
						echo " <tr>
						       <td><h3> = $result</h3> <td>
							  </tr> "; 
							  echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				} ; 
				 ?>
                 <br>
				 	
					 
    	            <?php if($msg != ''): ?>  <!-- This part show error or warning message if one of the operand does not meet calculations requirements - dividing by zero -->
						<br><br>	
    		        <div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
		            <?php endif; ?>
					
      </form>
	</div>
	
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
      
</body>
</html>