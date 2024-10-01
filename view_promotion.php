<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title> New Promotions</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<link rel="stylesheet" href="css/style.css">
  
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

   <h1 class="title">New Promotions</h1>

   <div class="box-container">

   <?php
      $select_promotions = $conn->prepare("SELECT * FROM `promotions`");
      $select_promotions->execute();
      if($select_promotions->rowCount() > 0){
         while($fetch_promotions = $select_promotions->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" data-type="promotions" method="POST" id="<?= $fetch_promotions['title']; ?>">
      
   <div class="title"><span><?= $fetch_promotions['title']; ?></div>
   <div class="description" style="font-size: 16px ; "><h2><?= $fetch_promotions['description']; ?></h2></div>
         </br>
         </br>
   <div class="discount" style="font-size: 11px ; font-family:Arial ;"><h2>
      <?= $fetch_promotions['discount']; ?>% OFF</div>
   <img src="uploaded_img/<?= $fetch_promotions['image_url']; ?>" alt=""></img>
   <a href="shop.php">
      <input type="button" value="shop now" class="btn" name="add_to_cart">
         </a>
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