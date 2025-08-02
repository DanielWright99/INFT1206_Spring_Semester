<?php



//Include shared database connection
require_once '../includes/db_connect.php';

$errors = [];
$success = '';
$username= '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if (empty($errors)) {

        $conn = get_db_connection();
        $query = "SELECT id, username, password FROM users WHERE username = $1";
        $result = pg_query_params($conn, $query, [$username]);
        if ($row = pg_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $success = "Login successful! Welcome, " . htmlspecialchars($username) . '.';

                $log_query = "INSERT INTO activity_logs (user_id, action, description, created_at) VALUES($1, $2, $3, CURRENT_TIMESTAMP)";
                $log_result = pg_query_params($conn, $log_query, [
                   $row['id'],
                   'login',
                   'User' . $username . ' logged in.',
                ]);

                if(!$log_result){
                    $errors[] = "Falied to log login action";
                }
                if($log_result){
                    pg_free_result($result);
                }

                header('Location: ../views/index.php');
                exit;
            }else{
                $errors[] = 'Invalid credentials. Login failed.';
            }
            pg_free_result($result);
            pg_close($conn);
        }
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
<?php include '../includes/header.php'; ?>


<main>
    <section>
        <h2>Login</h2>
        <p>Log in to access your account</p>

        <?php if(!empty($errors)): ?>
        <?php foreach($errors as $error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if($success !== ''): ?>
        <p class="success-message"> <?php htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <div class="form-container">

            <form method="post" action="login.php">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username)?>" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
                <button type="submit">Login</button>
            </form>



        </div>
    </section>
</main>
<footer>
    <p> 2025 Daniel Wright. All rights reserved.</p>
</footer>
</body>
</html>