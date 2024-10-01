<?php 
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};


if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_promotions = $conn->prepare("DELETE FROM `categories` WHERE id = ?");
    $delete_promotions->execute([$delete_id]);
    header('location:admin_category.php');
    echo '<script>alert("Thank You..!"); location.replace(document.referrer);</script>';
}
   
?>
