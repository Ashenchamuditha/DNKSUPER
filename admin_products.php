<?php
require('fpdf186/fpdf.php');
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $quantity = $_POST['quantity'];
   $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(`name`, `category`, `details`, `price`, `image`, `qty`) 
      VALUES (?, ?, ?, ?, ?, ?)");
      $insert_products->execute([$name, $category, $details, $price, $image, $quantity]);

      if($insert_products){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new product added!';
         }

      }

   }

};



if(isset($_POST['add_category'])){

   $catname = $_POST['catname'];
   $catname = filter_var($catname, FILTER_SANITIZE_STRING);

   $select_category = $conn->prepare("SELECT * FROM `categories` WHERE name = ?");
   $select_category->execute([$catname]);

   if($select_category->rowCount() > 0){
      $message[] = 'category name already exist!';
   }else{

      $insert_category = $conn->prepare("INSERT INTO `categories`(`name`) VALUES (?)");
      $insert_category->execute([$catname]);

      if($insert_category){
         $message[] = 'New category added!';
      }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   //$delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

// Generate PDF if requested
if (isset($_POST['generate_pdf'])) {
   generatePDF($conn);
}

function generatePDF($conn)
{
   $pdf = new FPDF();
   $pdf->AddPage();
   $pdf->SetFont('Arial', 'B', 12);
   $pdf->Cell(40, 10, 'Product Name', 1);
   $pdf->Cell(40, 10, 'Category', 1);
   $pdf->Cell(40, 10, 'Price', 1);
   $pdf->Cell(40, 10, 'Quantity', 1);
   $pdf->Ln();

   $show_products = $conn->prepare("SELECT * FROM `products`");
   $show_products->execute();
   while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
       $pdf->Cell(40, 10, $fetch_products['name'], 1);
       $pdf->Cell(40, 10, $fetch_products['category'], 1);
       $pdf->Cell(40, 10, $fetch_products['price'] . ' LKR', 1);
       $pdf->Cell(40, 10, $fetch_products['qty'] . ' Units', 1);
       $pdf->Ln();
   }

   $pdf->Output('product_table.pdf', 'D');
   exit(); // Stop script execution after PDF generation
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="show-products">

   <h1 class="title">DNK Inventory</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price"><?= $fetch_products['price']; ?> LKR</div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="details" ><?= $fetch_products['qty']; ?> Units</div>
      <div class="details"><?= $fetch_products['details']; ?></div>

      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" 
         class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now products added yet!</p>';
   }
   ?>

   </div>

</section>

<section class="add-products">

   <h1 class="title">Add New Product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Enter product name">
         <select name="category" class="box" required>
            <option value="" selected disabled>select category</option>
    <?php
         $select_category = $conn->prepare("SELECT * FROM `categories`");
         $select_category->execute();
         while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
      ?>
               <option value="<?= $fetch_category['name']; ?>"><?= ucfirst($fetch_category['name']); ?></option>
      <?php
         }
      ?>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
         <input type="number" min="0" name="quantity" class="box" required placeholder="Enter product quantity">
         </div>
      </div>
      <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">

      <textarea name="details" class="box" required placeholder="Enter product description" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add product" name="add_product">
   </form>

</section>
<form method="post">
         <input type="submit" class="btn1" value="Generate PDF" name="generate_pdf">
      </form>

<section class="add-products">

   <h1 class="title">Add new category</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="catname" class="box" required placeholder="Enter category name">

         </div>

      </div>

      <input type="submit" class="btn" value="Add Category" name="add_category">
   </form>

</section>













<script src="js/script.js"></script>

</body>
</html>