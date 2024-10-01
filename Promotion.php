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
        <title>Promotions & Offers</title>
        
        <!--swipe from CDN-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

             <!--fontawesomefile-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
  

  <link rel="stylesheet" href="promotion.css">
  <link rel="stylesheet" href="css/style.css">
</head>

    <body>

   
  
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
    
<section class="products">

<h1 class="title"> Promotions Categories</h1>
</section>
  
    <section class="banner-container">
        <div class="banner">
            <img src="images/offer1.jpeg" alt="">
            <div class="content">
            <h3>special offer</h3>
            
            <a href="view_promotion.php" class="btn1">check out</a>
        </div>
        </div>

        <div class="banner">
            <img src="images/deal.jpg" alt="">
            <div class="content">
            <h3> special offer</h3>
           
            <a href="view_promotion.php" class="btn1">check out</a>
        </div>
        </div>

        

     </section>
 
                  
  <!--home promotion end-->
  
   <!--deals section start-->
     
   <section class="deal" id="deal">
    
    <div class="content">
        <h3 class="title">Deal of the day</h3>
        <p>"Only For Awurudu Season :Save Big on fresh products <br> and Bakery Iteams !!!!"</p>

        <div class="count-down">

            <div class="box">
                <h3 id="day">00</h3>
                <span>day</span>
            </div>


            <div class="box">
                <h3 id="Hour">00</h3>
                <span>Hour</span>
            </div>

            <div class="box">
                <h3 id="Minute">00</h3>
                <span>Minute</span>
            </div>

            <div class="box">
                <h3 id="Sec">00</h3>
                <span>Sec</span>
            </div>
        </div>

        <a href="view_promotion.php" class="btn1">Check The Deal</a>
    </div>

   </section>






   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!--js file start-->
        <script src="js/home.js"></script>
        <script src="js/promotion.js"></script>

    
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
    </body>
</html>