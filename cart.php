<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM cart WHERE id = ?");
   $delete_cart_item->execute([$delete_id]);
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:shop.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
   $p_id = $_POST['p_id']; 
   
   try {
       // Check if the product exists and retrieve its quantity
       $check_qty = $conn->prepare("SELECT qty FROM products WHERE id = ?");
       $check_qty->execute([$p_id]);
       
       if($check_qty->rowCount() > 0) {
           $fetch_qty = $check_qty->fetch(PDO::FETCH_ASSOC);
           if($fetch_qty !== false && $p_qty <= $fetch_qty['qty']){
               // Update quantity in the cart
               $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
               $update_qty->execute([$p_qty, $cart_id]);
               header('location:cart.php');
               exit; // Exit to prevent further execution
           } else {
               // Quantity exceeds available stock
               $message[] = 'Please select less than or equal to ' . $fetch_qty['qty'] . ' Units!';
           }
       } else {
           // Product not found
           $message[] = 'Error: Product not found for ID ' . $p_id;
       }
   } catch (PDOException $e) {
       // Handle database errors
       $message[] = 'Database error: ' . $e->getMessage();
   } catch (Exception $e) {
       // Handle other errors
       $message[] = 'Error: ' . $e->getMessage();
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times"
       onclick="return confirm('delete this from cart?');"></a>
      <a href="cart.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      
      <div class="price"><?= $fetch_cart['price']; ?> LKR</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <input type="hidden" name="p_id" value="<?= $fetch_cart['pid']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['qty']; ?>" class="qty" name="p_qty">
         <input type="submit" value="update" name="update_qty" class="option-btn">
      </div>
      <div class="sub-total"> Total : <span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['qty']); ?> LKR</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span><?= $grand_total; ?> LKR</span></p>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="cart.php?delete_all" class="delete-btn" onclick="
      return confirm('delete this from cart?');" <?= ($grand_total > 1)?'':'disabled'; ?>>Delete All</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Buy Now</a>
   </div>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>