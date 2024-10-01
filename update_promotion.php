<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

?>
<?php

if (isset($_POST['update_promotions'])) {
    $update_id = $_GET['id_new'];

     // Sanitize and retrieve form data
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $discount = $_POST['discount'];
    $discount = filter_var($discount, FILTER_SANITIZE_STRING);

     // Retrieve image file details
    $image = $_FILES['image']['name'];
     // if image is uploaded
    if ($image) {
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image;
         // Move uploaded image file to destination folder
        move_uploaded_file($image_tmp_name, $image_folder);
        
        // Update promotion details in the database with image URL
        $update_promotions = $conn->prepare("UPDATE promotions SET title = ?, description = ?, discount = ?, image_url = ? WHERE id = ?");
        $update_promotions->execute([$title, $description, $discount, $image, $update_id]);


        // Update promotion details in the database without image URL
    } else {
        $update_promotions = $conn->prepare("UPDATE promotions SET title = ?, description = ?, discount = ? WHERE id = ?");
        $update_promotions->execute([$title, $description, $discount, $update_id]);
    }
    echo '<script>alert("Update Successful!"); location.replace("admin_promotion.php");</script>';
}
?>

<?php
$update_id = $_GET['update_profile'];
$update_promotions = $conn->prepare("SELECT * FROM promotions WHERE id = ?");
$update_promotions->execute([$update_id]);
if ($update_promotions->rowCount() > 0) {
    while ($fetch_promotions = $update_promotions->fetch(PDO::FETCH_ASSOC)) {
?>

        <!DOCTYPE html>
        <html>

        <head>
            <title>Promotions</title>
        </head>
        <div class="box3">
            <h2>Update Promotions</h2>
        </div>
        <form action="update_promotion.php?id_new=<?= $fetch_promotions['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="
        <?= $fetch_promotions['title']; ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" class="form-control" value="
        <?= $fetch_promotions['description']; ?>">
            </div>

            <div class="form-group">
                <label for="discount">Discount</label>
                <input type="text" name="discount" class="form-control" value="
        <?= $fetch_promotions['discount']; ?>">
            </div>
            <div class="form-group">
                <img src="uploaded_img/<?= $fetch_promotions['image_url']; ?>" alt="" width="200"></img>
            </div>
            </div>
            <div class="form-group">
                <input type="file" name="image" class="form-control-file">
            </div>
            <div class="form-group">
            <form action="admin_promotion.php" method="post" onsubmit="return confirm('Update this Promotion?');">
    <input type="submit" class="btn btn-success" name="update_promotions" value="UPDATE">
</form>

                <a type="button" href="admin_promotion.php" ;
                class="btn1">Back</a>
            </div>
        </form>

<?php
    }
     // If no promotion details
} else {
    echo '<p class="empty">no products found!</p>';
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
ntegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
  ntegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
  crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
  crossorigin="anonymous"></script>


<link rel="stylesheet" href="css/home.css">