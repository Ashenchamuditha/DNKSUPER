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
       $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
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
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="csss/home.css">
 

</head>
<body>
   
<?php include 'header.php'; ?>



<section class="p-category">

<?php
   $select_category = $conn->prepare("SELECT * FROM `categories`");
   $select_category->execute();
   if($select_category->rowCount() > 0){
      while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
?>
   <a href="category.php?category=<?= $fetch_category['name']; ?>" class="box"><?= $fetch_category['name']; ?></a>
<?php
      }
   }else{
      echo '<p class="empty">no category added yet!</p>';
   }
?>
  

</section>



<section class="products">

   <h1 class="title">Latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
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

<script src="js/script.js"></script>

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


</body>


</html>