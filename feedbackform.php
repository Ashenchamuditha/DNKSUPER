
<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};
?>
<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>
<!-- custom-theme -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Elegant Feedback Form  Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //custom-theme -->
<link href="css/stl.css" rel="stylesheet" type="text/css" media="all" />
<link href="//fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
</head>
<body class="agileits_w3layouts">
    <h1 class="agile_head text-center">Feedback Form</h1>
    <div class="w3layouts_main wrap">
	  <h3>Please help us to serve you better by taking a couple of minutes. </h3>
	    <form action="feedback.php" method="post" class="agile_form">
		    
			<h2>If you have specific feedback, please write to us...</h2>
			<textarea placeholder="Additional comments" class="w3l_summary" name="comments" id="comments" required="">

			</textarea>
			<input type="text" placeholder="Your Name (optional)" name="name" id="name" required=""/>
			<input type="email" placeholder="Your Email (optional)" name="email" id="email" required=""/>
			<input type="text" placeholder="Your Number (optional)" name="num" id="num" required=""/><br>
			<center><input type="submit" value="submit Feedback" class="agileinfo"  
			onclick="return confirm('Are you sure you want to submit the feedback?')" /></center>
			
	  </form>
	</div>

	<div class="agileits_copyright text-center">
		
			<a href = "about.php">
			<button class="btn btn1"> See Reviews </button>
			</a>
		
			<a href="home.php"> 
			<button class="btn btn2"> Home </button>
			</a>

			
	</div>
	
</body>
</html>

