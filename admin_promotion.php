<?php

require('fpdf186/fpdf.php');

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

// Generate PDF if requested
if (isset($_POST['generate_pdf'])) {
    generatePDF($conn);
 }
 
 function generatePDF($conn)
{
    // Creating a new instance of FPDF class
    $pdf = new FPDF();
    // Adding a new page to the PDF
    $pdf->AddPage();
    // Setting font style for title
    $pdf->SetFont('Arial', 'B', 16);
    // Adding Promotion Report title
    $pdf->Cell(0, 10, 'Promotion Report', 0, 1, 'C');

    // Setting font style for table headers
    $pdf->SetFont('Arial', 'B', 12);

    // Adding table header with colored background
    $pdf->SetFillColor(173, 216, 230); // Light blue color
    $pdf->SetTextColor(0, 0, 0); // Black text color
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Title', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Discount', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Image URL', 1, 1, 'C', true); // Move to the next line after this cell

    // Setting font style for data rows
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(255, 255, 255); // White background for data rows

    // Selecting all promotions from the database
    $select_promotions = $conn->prepare("SELECT * FROM promotions");
    $select_promotions->execute();
    
    // Looping through each promotion and adding it to the PDF
    while ($fetch_promotions = $select_promotions->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(20, 10, $fetch_promotions['id'], 1, 0, 'C', true);
        $pdf->Cell(50, 10, $fetch_promotions['title'], 1, 0, 'C', true);
        $pdf->Cell(60, 10, $fetch_promotions['description'], 1, 0, 'C', true);
        $pdf->Cell(30, 10, $fetch_promotions['discount'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $fetch_promotions['image_url'], 1, 1, 'C', true); // Move to the next line after this cell
    }

    // Outputting PDF with a file name and download option
    $pdf->Output('promotions.pdf', 'D');
    // Stop script execution after PDF generation
    exit();
}

?>


</header>
<!DOCTYPE html>
<html>
<head>
<title>Promotions</title>
</head>
        <div class="box2">
        <h2>All Promotions</h2>
        
        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Promotions</button>
        <a href="admin_page.php" class="btn btn-success">Home</a>
        </div>
                <table class="table table-hover table-bordered table-striped">
                 <thead>
                <tr>
                    <th>id</th>
                    <th>title</th>
                    <th>description</th>
                    <th>discount</th>
                    <th>image_url</th>
                    <th>UPDATE</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                
    $promotion = $conn->prepare("SELECT * FROM promotions");
    $promotion->execute();
    if($promotion->rowCount() > 0){
        while($fetch_promotion = $promotion->fetch(PDO::FETCH_ASSOC)){
   
            ?>
                   <tr>
                   <td> <span><?= $fetch_promotion['id']; ?></span></td>
                   <td> <span><?= $fetch_promotion['title']; ?></span></td>
                   <td> <span><?= $fetch_promotion['description']; ?></span></td>
                   <td> <span><?= $fetch_promotion['discount']; ?></span></td>
                   
                   <td> <span><?= $fetch_promotion['image_url']; ?></span></td>
                   <td><a  href="update_promotion.php?update_profile=<?= $fetch_promotion['id']; ?>" 
                   class="btn btn-success">UPDATE</td>
                   <td><a  href="delete_promotion.php?delete=<?= $fetch_promotion['id']; ?>" 
                   onclick="return confirm('delete this Promotion?')"; 
                   class="btn btn-danger">DELETE</td>
           </tr>
                       <?php
                   }
                }
                 ?>
         

         
        </tbody>
        </table>

        <?php
        if(isset($_GET['message'])){
            echo "<h6>".$_GET['message']."</h6>";
        }
        ?>

     <?php
        if(isset($_GET['insert_msg'])){
            echo "<h6>".$_GET['insert_msg']."</h6>";
        }
        ?>

     <?php
        if(isset($_GET['insert_msg'])){
            echo "<h6>".$_GET['insert_msg']."</h6>";
        }
        ?>

     <?php
        if(isset($_GET['delet_msg'])){
            echo "<h6>".$_GET['delet_msg']."</h6>";
        }
        ?>

        <form action="insert_data.php" method="post" enctype="multipart/form-data">
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add promotions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
   
            <div class="form-group">
                <label for="title" required placeholder="Enter Title">Title</label>
                <input type="text" name="title"class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description"class="form-control">
            </div>

            <div class="form-group">
                <label for="discount">Discount</label>
                <input type="text" name="discount"class="form-control">
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image"class="form-control-file">
            </div>
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success" name="add_promotions" value="ADD">
      </div>
    </div>
  </div>
</div>
    </form>

    <form action="" method="POST" enctype="multipart/form-data">
            <input type="submit" class="btn btn-success" value="Generate PDF" name="generate_pdf">
         </form>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
ntegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
crossorigin="anonymous"></script>
       
 <link rel="stylesheet" href="css/admnstyle.css">
 <link rel="stylesheet" href="css/promotions.css">