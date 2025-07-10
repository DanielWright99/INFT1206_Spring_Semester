<?php


require_once '../includes/db_connect.php';

$errors = [];
$success = '';
$username = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    //validate input
    if(empty($username)) {
        $errors[] = "Username is required";
    } elseif(strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }

    if(empty($password)) {
        $errors[] = "Password is required";
    } elseif(strlen($password) < 3) {
        $errors[] = "Password must be at least 3 characters";
    }

    if(empty($errors)) {
        $conn = get_db_connection();
        $query = "SELECT id FROM users WHERE username = $1";
        $result = pg_query_params($conn, $query, $username);

        if(pg_num_rows($result) > 0) {
            $errors[] = "Username is already taken";
        }
        pg_free_result($result);
    }
    //If no errors, save the user to the database
    if(empty($error)) {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password) VALUES ($1, $2)";
        $result = pg_query_params($conn, $query, [$username, $hashPassword]);
        if($result) {
            $success = "Registered successfully. Please log in";
            $username = '';

        }else{
            $errors[] = "Registration failed";
        }
        pg_close($conn);
    }

}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Registration -INFT 1206</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>


<?php include '../includes/header.php'?>;

<main>
    <section>
        <h2>Register</h2>
        <p>Create an account to access exclusive features:</p>
        <div class="form-container">

            <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach ?>
            <?php endif ?>

            <?php if($success !== ''): ?>
                <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
            <?php endif;?>

            <form method="post" action="register.php">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username)?>" required><br>
                <label for="password">Password:</label><br>
                <input type="text" id="password" name="password" required><br>
                <button type="submit">Register</button>
            </form>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Daniel Wright. All rights reserved.</p>
</footer>
</body>
</html>
