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
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(40, 10, 'Name', 1);
    $pdf->Cell(80, 10, 'Comments', 1);
    $pdf->Cell(40, 10, 'Email', 1);
    $pdf->Cell(40, 10, 'Phone Number', 1);
    $pdf->Ln();
 
    $select_feedback = $conn->prepare("SELECT * FROM `feedbackreview`");
    $select_feedback->execute();
    while ($fetch_feedback = $select_feedback->fetch(PDO::FETCH_ASSOC)) {
       $pdf->Cell(20, 10, $fetch_feedback['id'], 1);
       $pdf->Cell(40, 10, $fetch_feedback['name'], 1);
       $pdf->Cell(80, 10, $fetch_feedback['comments'], 1);
       $pdf->Cell(40, 10, $fetch_feedback['email'], 1);
       $pdf->Cell(40, 10, $fetch_feedback['phone'], 1);
       $pdf->Ln();
    }
 
    $pdf->Output('feedback_reviews.pdf', 'D');
    exit(); // Stop script execution after PDF generation
 }
 
?>


</header>
<!DOCTYPE html>
<html>
<head>
<title>Admin Feedback</title>
</head>
        <div class="box2">
        <h2>All Reviews</h2>
        
        
        <a href="admin_page.php" class="btn btn-success">Home</a>
        </div>
                <table class="table table-hover table-bordered table-striped">
                 <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                 <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                
    $promotion = $conn->prepare("SELECT * FROM `feedbackreview`");
    $promotion->execute();
    if($promotion->rowCount() > 0){
        while($fetch_promotion = $promotion->fetch(PDO::FETCH_ASSOC)){
   
            ?>
                   <tr>
                   <td> <span><?= $fetch_promotion['id']; ?></span></td>
                   <td> <span><?= $fetch_promotion['name']; ?></span></td>
                   <td> <span><?= $fetch_promotion['comments']; ?></span></td>
                   <td> <span><?= $fetch_promotion['email']; ?></span></td>
                   <td> <span><?= $fetch_promotion['phone']; ?></span></td>
                   
               
                   
                   <td><a  href="delete_feedback.php?delete=<?= $fetch_promotion['id']; ?>" 
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

        
    </form>

    <form action="" method="POST" enctype="multipart/form-data">
            <input type="submit" class="btn btn-success" value="Generate PDF" name="generate_pdf">
         </form>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
ntegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
       
 <link rel="stylesheet" href="css/admnstyle.css">
 <link rel="stylesheet" href="css/promotions.css">


   




   