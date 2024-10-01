<?php 
@include 'config.php';

// Start session to manage user authentication
session_start();


// Retrieve admin ID
$admin_id = $_SESSION['admin_id'];

// admin id is not back to login page
if(!isset($admin_id)){
   header('location:login.php');
};


if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];

      // Prepare SQL statement to delete the promotion with specified ID
    $delete_promotions = $conn->prepare("DELETE FROM promotions WHERE id = ?");

      // Prepare SQL statement to execute the promotion with specified ID
    $delete_promotions->execute([$delete_id]);

     //back to crud panel after delete  
    header('location:admin_promotion.php');
     
     // display part
    echo '<script>alert("Thank You..!"); location.replace(document.referrer);</script>';
}
   
?>