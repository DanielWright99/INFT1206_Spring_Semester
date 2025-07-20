<?php

include '../includes/header.php';
require_once '../includes/db_connect.php';

//If the user is not logged in redirect them to Login page
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

//Initialize variables
$errors = [];
$success = '';
$user_id = $_SESSION['user_id'];

//Generate a token of one does not already exist
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SESSION['REQUEST_METHOD'] === "POST" && isset($_FILES['profile_picture'])){
    //validate CSRF token
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        $errors[] = 'Invalid CSRF Token';
    }else{
        $file = $_FILES['profile_picture'];
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024; //2MB Max Size

        //Validate File
        if($file['error'] !== UPLOAD_ERR_OK){
            $errors[] =  "File upload failed";
        }elseif(!in_array($file['type'], $allowed_types)){
            $errors[] =  "File type not allowed";
        } elseif($file['size'] > $max_size){
            $errors[] =  "File size must be less than or equal to 2 MB";
        }
        //If no errors, process the file upload
        if(empty($error)){
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $user_id . '' . time() . $ext;
            $destination = 'images/' . $filename;


            if(move_uploaded_file($file['tmp_name'], $destination)){
               $conn = get_db_connection();
               $query = "UPDATE users SET profile_picture = $1 WHERE id = $2";
               $result = pq_query_params($conn, $query, [$filename, $user_id]);
               if($result){
                   $success = "Profile picture uploaded successfully";
               }else{
                   $errors[] = "Failed to upload profile picture";
               }
               pg_close($conn);
            }else{
                $errors[] = "Failed to move profile picture";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Profile Picture - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>

<main>
    <section>
    <h2>Upload Profile Picture</h2>
        <p>Upload a JPEG or PNG (max 2MB)</p>

        <div class="form-container">

            <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if($success !== ''): ?>
                <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <form method="post" action="upload.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <label for="profile_picture">Profile Picture: </label><br>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg, image/png" required><br>
                <button type="submit">Upload</button>
            </form>


        </div>
    </section>

</main>


</body>

</html>