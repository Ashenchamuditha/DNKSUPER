<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body onload="showSlides()">
	<div class="container"></div>>
   
<header class="header">

   <div class="flex">

      <a href="home.php" class="logo">DNK<span>Super</span></a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="shop.php">Shop</a>
         <a href="orders.php">My Orders</a>
         <a href="promotion.php">Promotions</a>
         <a href="about.php">About Us</a>
         
 
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
        
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
           
         ?>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="user_profile_update.php" class="btn">Update profile</a>
         <a href="logout.php" class="delete-btn">Logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
         </div>
      </div>

   </div>
</header>
<!--< class="products"> -->

<h1 class="title"> DNK SUPER</h1>

<!-- slideshow  -->
<script>
	let slideIndex = 0; 
		showSlides();  
		function showSlides()  {   
			let i;   
			let slides = document.getElementsByClassName("mySlides");   
			for (i = 0; i < slides.length; i++)  
			{   
				slides[i].style.display = "none";  
			}   
			slideIndex++;   
			if (slideIndex > slides.length)  
			{slideIndex = 1}  
			slides[slideIndex-1].style.display = "block"; 
			setTimeout(showSlides, 4000); //Change image every 3 seconds 
		} 
 </script>
	<div class="slideshow-container">
	    <div class="mySlides fade">
		<img src="images/slide1.jpg" width="100%">
		</div>
	</div>
		<div class="slideshow-container">
	    <div class="mySlides fade">
		<img src="images/slide2.jpg" width="100%">
			</div>
	</div>
		<div class="slideshow-container">
	    <div class="mySlides fade">
		<img src="images/slide3.png" width="100%">
			</div>
	
	</div>
	</div>

</section>
<!-- slideshow_end    -->


<section class="about">

   <div class="row">

      <div class="box">
         <img src="images/about-img-1.png" alt="">
         <h3>why choose us?</h3>
         <p> DNK Super, a leading name in the health and wellness industry, 
            recognizes the significance of fostering trust, 
            transparency, and rapport with its audience.This transparency not only fosters 
            trust but also empowers customers to make informed choices, 
            knowing the integrity behind the products they choose.
            website is not merely a choice but a strategic imperative.
</br>
         </p>
         <a href="feedbackform.php" class="btn">Feedbacks</a>
      </div>

      <div class="box">
         <img src="images/about-img-2.png" alt="">
         <h3>what we provide?</h3>
         <p>DNK Super Shop prides itself on sourcing and curating only the highest quality products, 
            handpicked from trusted suppliers and manufacturers. From organic produce and wholesome pantry 
            staples to natural skincare and wellness essentials, every item available at 
            DNK Super Market Shop is carefully selected to meet stringent quality standards and exceed 
            customer expectations.</p>
         <a href="shop.php" class="btn">our shop</a>
      </div>

   </div>

</section>


<section class="products">

   <h1 class="title">Customer's Reviews</h1>

   <div class="box-container">

   <?php
   
      $select_feedbackreview = $conn->prepare("SELECT * FROM `feedbackreview`");
      $select_feedbackreview->execute();
      if($select_feedbackreview->rowCount() > 0){
         while($fetch_feedbackreview = $select_feedbackreview->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" data-type="promotions" method="POST" id="<?= $fetch_promotions['id']; ?>">
      
   <div class="name" style="color:#EFC3CA;"><h2><?= $fetch_feedbackreview['name']; ?></h2></div>
   <div class="comments" style="font-size: 16px ;"><span><?= $fetch_feedbackreview['comments']; ?></span></div>
   <br>
         </br>
   <div class="email"><h2><?= $fetch_feedbackreview['email']; ?></div>
   <div class="num"><h2><?= $fetch_feedbackreview['phone']; ?></div>
   
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no promotions added yet!</p>';
   }
   ?>

   </div>

</section>



<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>