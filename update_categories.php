<?php
@include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};
?>
<?php
if (isset($_POST['update_categories'])) 
{
    $update_id = $_GET['id'];
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $update_categories = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $update_categories->execute([$name, $update_id]);
    
    echo '<script>alert("Update Successful!"); location.replace("admin_category.php");</script>';
}
?>
<?php
$update_id = $_GET['id']; // Corrected URL parameter name
$update_categories = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$update_categories->execute([$update_id]);
if ($update_categories->rowCount() > 0) {
    while ($fetch_categories = $update_categories->fetch(PDO::FETCH_ASSOC)) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Category</title>
</head>
<body>
    <div class="box3">
        <h2>Update Category</h2>
    </div>
    <form action="update_categories.php?id=<?php echo $update_id; ?>" method="post">
        <div class="form-group">
            <label for="title">Id</label>
            <input type="text" name="id" class="form-control" value="<?= $fetch_categories['id']; ?>">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="<?= $fetch_categories['name']; ?>">
        </div>
        <input type="submit" class="btn btn-success" name="update_categories" value="UPDATE">
    </form>
    <a type="button" href="admin_category.php" class="btn1">Back</a>
</body>
</html>
<?php
    }
} 
else 
{
    echo '<p class="empty">No category found!</p>';
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