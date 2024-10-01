<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


if(isset($_POST['add_to_cart'])){
   // Validate and sanitize input data
   $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_STRING);
   $p_name = filter_input(INPUT_POST, 'p_name', FILTER_SANITIZE_STRING);
   $p_price = filter_input(INPUT_POST, 'p_price', FILTER_SANITIZE_STRING);
   $p_image = filter_input(INPUT_POST, 'p_image', FILTER_SANITIZE_STRING);
   $p_qty = filter_input(INPUT_POST, 'p_qty', FILTER_SANITIZE_STRING);
   
   try {
       // Check if the product is already added to the cart
       $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name =? AND user_id = ?");
       $check_cart->execute([$p_name, $user_id]);
       
       if($check_cart->rowCount() > 0){
           throw new Exception('Product already added to cart!');
       }

       // Check if the quantity is less than or equal to available quantity
       $check_qty = $conn->prepare("SELECT qty FROM `products` WHERE id = ?");
       $check_qty->execute([$pid]);
       $fetch_qty = $check_qty->fetch(PDO::FETCH_ASSOC);
       
       if($p_qty > $fetch_qty['qty']){
           throw new Exception('Please select less than or equal to ' . $fetch_qty['qty'] . ' Units!');
       }

       // Insert the product into the cart
       $insert_cart = $conn->prepare("INSERT INTO `cart`(`name`, `price`, `image`, `qty`, `user_id`,`pid`) 
       VALUES(?,?,?,?,?,?)");
       $insert_cart->execute([$p_name, $p_price, $p_image, $p_qty, $user_id, $pid]);
       
       $message[] = 'Added to cart!';
   } catch (Exception $e) {
       // Handle exceptions
       $message[] = $e->getMessage();
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>DNK SUPER</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
  
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/home.css">
   
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
   
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!--fontawesomefile-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<body>
</head>
<?php include 'header.php'; ?>



<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Browse through our user-friendly website</span>
         <h3>Deliver goods at your door step</h3>
         <p> Welcome to DNK SUPER! your one-stop destination for all your grocery needs! At SuperMart, 
            we offer an extensive range of 
            high-quality products at unbeatable prices, making grocery shopping a breeze for you and your family.</p>
         <a href="about.php" class="btn">About us</a>
      </div>

   </section>

</div>

<section class="categories" id="categories">
  
    <h1 class="heading">Product<span>Categories</span></h1>

    <div class="swiper categories-slider">
        <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <img src="images/vegi.png" alt="">
                <h3>Fruits & vegetables</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/grocery.png" alt="">
                <h3>Grocery</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/stationary.png" alt="">
                <h3>Stationary</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/meat.png" alt="">
                <h3>Meat</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper product-slider">
            <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <img src="images/bakery.png" alt="">
                <h3>Bakery & dairy</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/instant.png" alt="">
                <h3>Instant Food</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/beverage.png" alt="">
                <h3>Beverages</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>

            <div class="swiper-slide box">
                <img src="images/frozen.png" alt="">
                <h3>Frozen Foods</h3>
                <a href="shop.php" class="btn1">shop now</a>
            </div>
        </div>
    </div>
</section>

<section class="products">

   <h1 class="title">Latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
        
   ?>
   <form action="" class="box" data-type="product" method="POST" id="<?= $fetch_products['name']; ?>">
      <div class="price"><span><?= $fetch_products['price']; ?></span> LKR</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>






<?php include 'footer.php'; ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  // onkeyup event
$('input[name="search_box"]').on('keyup', function(){
    // get the search keyword and convert it to lowercase
    let search = $(this).val().toLowerCase();
    // check if the search keyword is empty
    if(search == ''){
        // show all products
        $('form[data-type="product"]').show();
    }else{
        // hide all products
        $('form[data-type="product"]').hide();
        // show the products that match the search keyword (case-insensitive)
        $('form[data-type="product"] div.name').filter(function() {
            return $(this).text().toLowerCase().includes(search);
        }).parent().show();
    }
});

</script>


<script src="js/script.js"></script>
<script src="js/swiper.js"></script>
</body>
</html>