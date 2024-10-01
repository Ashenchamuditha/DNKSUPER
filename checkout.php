<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


if(isset($_POST['order'])){
    // Sanitize input data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'flat', FILTER_SANITIZE_STRING) . ' ' . 
    filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING) . ' ' . filter_input(INPUT_POST, 
    'city', FILTER_SANITIZE_STRING) . ' ' . filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING) . ' ' . 
    filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING) . ' - ' . filter_input(INPUT_POST, 
    'pin_code', FILTER_SANITIZE_STRING);
    $placed_on = date('d-M-Y');

    try {
        // Fetch cart data and calculate total
        $cart_total = 0;
        $cart_products = [];
        $cart_query = $conn->prepare("SELECT c.*, p.qty AS available_qty FROM `cart` c INNER JOIN `products` p 
        ON c.pid = p.id WHERE c.user_id = ?");
        $cart_query->execute([$user_id]);

        if($cart_query->rowCount() > 0){
            while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
                $cart_products[] = $cart_item['name'].' ( '.$cart_item['qty'].' )';
                $sub_total = ($cart_item['price'] * $cart_item['qty']);
                $cart_total += $sub_total;
            }
        }

        // Construct total_products string
        $total_products = implode(', ', $cart_products);

        // Check if cart is empty
        if($cart_total == 0){
            $message[] = 'Your cart is empty';
        } else {
            // Check if order already exists
            $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND address = ? AND total_products = ? AND total_price = ?");
            $order_query->execute([$name, $number, $email, $address, $total_products, $cart_total]);

            if($order_query->rowCount() > 0){
                $message[] = 'Order already placed!';
            } else {
                // Insert order into database
                $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?)");
                $insert_order->execute([$user_id, $name, $number, $email, $address, $total_products, $cart_total, $placed_on]);

                // Update product quantities and clear cart
                $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $cart_query->execute([$user_id]);

                if($cart_query->rowCount() > 0){
                    while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
                        $product_id = $cart_item['pid'];
                        $product_qty = $cart_item['qty'];
                        $update_qty = $conn->prepare("UPDATE `products` SET qty = qty - ? WHERE id = ?");
                        $update_qty->execute([$product_qty, $product_id]);
                    }
                }

                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                echo '<script>alert("Successfully Ordered!"); location.replace("orders.php");</script>';
               
               exit();
            }
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
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['qty']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= ucfirst($fetch_cart_items['name']); ?> <span>(<?= 'x '. $fetch_cart_items['qty']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">Grand Total : <span><?= $cart_grand_total; ?> LKR</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Name :</span>
            <input type="text" name="name" placeholder="e.g. Ashen chamuditha" class="box" required>
         </div>
         <div class="inputBox">
            <span>Contact Number :</span>
            <input type="number" name="number" placeholder="e.g. 0712345678" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="e.g. ashen@gmail.com" class="box" required>
         </div>

         <div class="inputBox">
            <span>Address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. 308/5" class="box" required>
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. mihindu mawatha" class="box" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="e.g. kadawatha" class="box" required>
         </div>
         <div class="inputBox">
            <span>Province :</span>
            <input type="text" name="state" placeholder="e.g. westerm" class="box" required>
         </div>
         
         <div class="inputBox">
            <span>Zip code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>