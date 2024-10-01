<?php
@include 'config.php';

// Check if form is submitted for adding promotions
if (isset($_POST['add_promotions'])) {
    
   //retrive data 
   $title = $_POST['title'];
   $description = $_POST['description'];
   $discount = $_POST['discount'];
   
   
    // Retrieve file details from uploaded image
   $fileName = $_FILES["image"]["name"];
   $fileTmpName = $_FILES["image"]["tmp_name"];
   $fileSize = $_FILES["image"]["size"];
   $fileError = $_FILES["image"]["error"];
   $uploadDir = "uploaded_img/";
   // Check if file is uploaded without any errors
   if ($fileError === 0) {
      // Move the uploaded file to the desired location
      $destination = $uploadDir . $fileName;
      move_uploaded_file($fileTmpName, $destination);
      if ($title == "" || empty($title)) {
         header('location:admin_promotion.php?message=You need to fill the Title!');
      } else {


         $insert_promotions = $conn->prepare("INSERT INTO promotions(title, description, discount, image_url) 
       VALUES (?,?,?,?)");
         $insert_promotions->execute([$title, $description, $discount, $fileName]);
         echo '<script>alert("Thank You..!"); location.replace(document.referrer);</script>';
      }
   } else {
      echo "Error uploading file.";
}
}