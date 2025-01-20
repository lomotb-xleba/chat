<?php
session_start();
include_once "config.php";

$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
    echo "All input fields are required!";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "$email is not a valid email!";
    exit;
}

$email = mysqli_real_escape_string($conn, $email);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

if (mysqli_num_rows($sql) > 0) {
    echo "$email - This email already exist!";
    exit;
}


if (!isset($_FILES['image'])) {
    echo "Image file is required!";
    exit;
}

$img_name = $_FILES['image']['name'];
$img_type = $_FILES['image']['type'];
$tmp_name = $_FILES['image']['tmp_name'];

$allowed_extensions = ["jpeg", "png", "jpg"];
$img_ext = pathinfo($img_name, PATHINFO_EXTENSION);


if (!in_array($img_ext, $allowed_extensions)) {
    echo "Please upload an image file - jpeg, png, jpg";
    exit;
}

$allowed_types = ["image/jpeg", "image/jpg", "image/png"];

if (!in_array($img_type, $allowed_types)) {
    echo "Invalid image type. Please upload a jpeg, png, or jpg file.";
    exit;
}

$new_img_name = time() . $img_name;

if (!move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
    echo "Error uploading image.";
    exit;
}


$ran_id = random_int(100000000, time()); //Более безопасная генерация случайного числа.
$status = "Active now";
$encrypt_pass = md5($password); //Желательно использовать более надежный метод хэширования, например, password_hash().
$fname = mysqli_real_escape_string($conn, $fname);
$lname = mysqli_real_escape_string($conn, $lname);


$insert_query = mysqli_prepare($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($insert_query, "issssss", $ran_id, $fname, $lname, $email, $encrypt_pass, $new_img_name, $status);


if (!mysqli_stmt_execute($insert_query)) {
    echo "Something went wrong. Please try again!";
    exit;
}



$_SESSION['unique_id'] = $ran_id; //Можно установить unique_id сразу после вставки, не делая повторный запрос.
echo "success";

?>
