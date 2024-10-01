<?php 
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};


?>
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/home.css">
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
</header>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Location</title>
    <link rel="stylesheet" href="css/location.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <div class="container">
        <h1 class="heading">Our<span>Store Location</span></h1>
        <link rel="stylesheet" href="css/location.css">
        <!-- Embedded Google Maps iframe -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.6243106075603!2d79.91200367415091!3d6.815466219703349!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25b0008aaacad%3A0xf092f5ac5fa8a2c!2sD%20N%20K%20Super!5e0!3m2!1sen!2slk!4v1715770499708!5m2!1sen!2slk" width="600" height="450" title="DNK Super Shop Location" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
  
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/home.css">
   
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<!--fontawesomefile-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </div>

    <?php include 'footer.php'; ?>
    <script src="js/home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
</body>

</html>
