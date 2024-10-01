<?php
require('fpdf186/fpdf.php');

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
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
 // Generate PDF if requested
if (isset($_POST['generate_pdf'])) {
   generatePDF($conn);
}

function generatePDF($conn)
{
   $pdf = new FPDF();
   $pdf->AddPage();
   $pdf->SetFont('Arial', 'B', 12);
   $pdf->Cell(40, 10, 'Category ID', 1);
   $pdf->Cell(100, 10, 'Category Name', 1);
   $pdf->Ln();

   $category = $conn->prepare("SELECT * FROM `categories`");
   $category->execute();
   while ($fetch_category = $category->fetch(PDO::FETCH_ASSOC)) {
       $pdf->Cell(40, 10, $fetch_category['id'], 1);
       $pdf->Cell(100, 10, $fetch_category['name'], 1);
       $pdf->Ln();
   }

   $pdf->Output('category_list.pdf', 'D');
   exit(); // Stop script execution after PDF generation
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categories</title>

   <div class="box2">
        <h2>All Categories</h2>
        
        
        <a href="admin_page.php" class="btn btn-success">Home</a>
        </div>
                <table class="table table-hover table-bordered table-striped">
                 <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Update</th>
                    
                 <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                
    $category = $conn->prepare("SELECT * FROM `categories`");
    $category->execute();
    if($category->rowCount() > 0){
        while($fetch_category = $category->fetch(PDO::FETCH_ASSOC)){
   
            ?>
                   <tr>
                   <td> <span><?= $fetch_category['id']; ?></span></td>
                   <td> <span><?= $fetch_category['name']; ?></span></td>
                   <td><a  href="update_categories.php?id=<?= $fetch_category['id']; ?>" 
                  
                   class="btn btn-success">Update</td>
                   
                   <td><a  href="delete_categories.php?delete=<?= $fetch_category['id']; ?>" 
                   onclick="return confirm('delete this Promotion?')"; 
                   class="btn btn-danger">DELETE</td>
           </tr>
                       <?php
                   }
                }
                 ?>
         

         
        </tbody>
        </table>


   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
ntegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

   <!-- custom css file link  -->

   
<form action="" method="POST" enctype="multipart/form-data">
    <input type="submit" class="btn" value="Generate PDF" name="generate_pdf">
</form>
   
</head>
<body>