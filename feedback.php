<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


$comments = $_POST['comments'];
$name = $_POST['name'];
$email = $_POST['email'];
$num = $_POST['num'];


$insert_feedbacks = $conn->prepare("INSERT INTO `feedbackreview`(`name`, `email`, `phone`, `comments`) 
VALUES (?,?,?,?)");
$insert_feedbacks->execute([$name, $email, $num, $comments]);

echo '<script>alert("Thank You..! Your Feedback is Valuable to Us"); location.replace(document.referrer);</script>';




?>