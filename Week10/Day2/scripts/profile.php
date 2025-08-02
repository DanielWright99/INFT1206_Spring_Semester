<?php


session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

//Initialize variable for form processing
$errors = [];
$success = '';
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = '';
$bio = '';

$profile_picture = '';

if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//Fetch current profile data
$conn = get_db_connection();
$query = 'SELECT email, bio, profile_picture FROM users WHERE id = $1';
$result = pg_query_params($conn, $query, [$user_id]);
if($result && pg_num_assoc($result) > 0){
    $email = $row['email'];
    $bio = $row['bio'];
    $profile_picture = $row['profile_picture'] ?? '';
}else{
    $errors[] = 'Failed to fetch profile picture.';
}

pg_free_result($result);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

   if(!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
       $errors[] = "Invalid CSRF Token.";
   }else {


       $email = isset($_POST['email']) ? trim($_POST['email']) : '';
       $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';

       if (empty($email)) {
           $errors[] = 'Email is required';
       } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $errors[] = 'Invalid email format';
       }

       if (strlen($bio) > 500) {
           $errors[] = 'Bio must be 500 characters or less';
       }

       if (empty($errors)) {
           $query = 'SELECT id FROM users WHERE email = $1 AND id = $2';
           $result = pg_query_params($conn, $query, [$email, $user_id]);
           if (pg_num_rows($result) > 0) {
               $errors[] = 'Email is already taken.';
           }
           pg_free_result($result);
       }
       if (empty($errors)) {
           $query = 'UPDATE users SET email = $1, bio = $2 WHERE id = $3';
           $result = pg_query_params($conn, $query, [$email, $bio, $user_id]);
           if ($result) {
               $success = 'Profile updated successfully.';

               $log_query = "INSERT INTO activity_logs (user_id, action, description, created_at) VALUES($1, $2, $3, CURRENT_TIMESTAMP)";
               $log_result = pg_query_params($conn, $log_query, [
                   $user_id,
                   'profile_update',
                   'User updated profile email and bio',
               ]);
               if(!$log_result){
                   $errors[] = "Falied to log update action";
               }
               if ($log_result) {
                   pg_free_result($log_result);
               }


           } else {
               $errors[] = 'Error updating profile.';
           }
       }
   }
}
pg_close($conn);
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css">

    <script>
        function validateform() {
            const email = document.getElementById('email').value;
            const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address");
                return false;
            }
            return true;
        }
    </script>

</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <section>
<h2>User Profile</h2>
<p>Update your profile details below:</p>

    <div class="form-container">
        <p><strong>Username: </strong><?php echo htmlspecialchars($username)?></p>
        <?php if($profile_picture): ?>
        <p><strong>Profile Picture</strong></p>
        <img src="../images/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error)?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if($success !== ''): ?>
            <p class="success-message"><?php echo htmlspecialchars($success)?></p>
        <?php endif; ?>

        <form method="post" action="profile.php" onsubmit="validateForm()">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <label for="bio">Biography: </label>
            <textarea id="bio" name="bio" rows="5"><?php echo htmlspecialchars($bio)?></textarea>
            <button type="submit">Update Profile</button>
        </form>
    </div>
    </section>
</main>
<footer>
    <p>© 2025 Daniel Wright. All rights reserved</p>
</footer>
</body>
</html>


