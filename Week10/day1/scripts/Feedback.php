<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

//Initialize variations for form processing
$error = [];
$success = "";
$name = "";
$feedback = "";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

//Generate CSRF Token
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        $errors[] = "Invalid CSRF Token";
    }else {
        $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
        $feedback = isset($_POST["feedback"]) ? trim($_POST["feedback"]) : "";

        $feedback_id =isset($_POST["feedback_id"]) ? $_POST["feedback_id"] : null;

        //validate input
        if (empty($name)) {
            $error[] = "Name is required";
        } elseif (strlen($name) < 3) {
            $error[] = "Name must be at least 3 characters long";
        }


        if (empty($feedback)) {
            $error[] = "Feedback is required";
        } elseif (strlen($feedback) < 10) {
            $error[] = "Feedback must be at least 10 characters long";
        }

        if (empty($error)) {
            //connect to database
            $conn = get_db_connection();

            if($feedback_id){
                $query = 'UPDATE feedback SET name = $1, feedback = $2 WHERE id = $3 AND user_id = $4';
                $queryResult = pg_query_params($conn, $query, [$name, $feedback, $feedback_id, $user_id]);
                if($queryResult){
                    $success = "Feedback updated successfully";
                    $log_query ='INSERT INTO activity_logs (use_id, action, description, created_at) VALUES ($1, $2, $3, CURRENT_TIMESTAMP)';
                    $log_result = pg_query_params($conn, $log_query, [$user_id, 'feedback_update', 'User updated feedback ID', $feedback_id]);

                    if(!$log_result){
                        $errors[] = "Failed to log feedback update";
                    }else{
                        pg_free_result($log_result);
                    }




                }else{
                    $errors[] = "Failed to update feedback";
                }
            } else {
                $query = "INSERT INTO Feedback (Name, Feedback, user_id) VALUES ($1, $2, $3)";
                $queryResult = pg_query_params($conn, $query, [$name, $feedback, $user_id]);
                if ($queryResult) {
                    $success = "Thank you for your feedback, " . htmlspecialchars($name) . "It has been saved";
                    $name = "";
                    $feedback = "";
                    $log_query = 'INSERT INTO activity_logs(user_id, action, description, created_at) VALUES ($1, $2, $3, CURRENT_TIMESTAMP)';
                    $log_result = pg_query_params($conn, $log_query, [$user_id, 'Feedback submit', 'User submitted feedback ID', $feedback_id]);

                    if(!$log_result){
                        $errors[] = 'Failed to log feedback submit';
                    }else{
                        pg_free_result($log_result);
                    }
                } else {
                    $error[] = "Failed to save feedback";
                }
                if($queryResult){
                    pg_free_result($queryResult);
                }
            }
            pg_close($conn);
        }

        //If AJAX Request, return JSON response
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'errors' => $errors]);
            exit;
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css" >

    <script>
        function validateForm() {

            const feedback = document.getElementById('feedback').value;
            if (feedback.length < 10) {
                alert('Feedback must be at least 10 characters long.');
                return false;
            }
            return true;
        }
            function submitForm(event) {
                event.preventDefault();
                if (!validateForm()) return;

                const form = document.getElementById('feedback-form');
                const formData = new FormData(form);

                fetch('feedback.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Request-With' 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {

                        const messageDiv = document.getElementById('form-messages');
                        messageDiv.innerHTML = '';
                        if (data.errors.length > 0) {
                            data.errors.forEach(error => {
                                const p = document.createElement('p');
                                p.className = 'error-message';
                                p.textContent = error;
                                messageDiv.appendChild(p);
                            });
                        }

                        if (Data.success) {
                            const p = document.createElement('p');
                            p.className = 'success-message';
                            p.textContent = data.success;
                            messageDiv.appendChild(p);
                            form.reset();
                        }

                    })
                    .catch(error => {
                        const messageDiv = document.getElementById('form-messages');
                        messageDiv.innerHTML = '<p class=error-message>An error occurred. Please try again</p>';
                        console.error('[ERROR] Form Submission Error: ', error.message);
                    });
            }

    </script>


</head>

<body>

<main>
    <section>
        <h2>Provide Feedback</h2>
        <p>Please share your feedback about the portfolio</p>
        <div class="form-container">
           <div id="form-messages"></div>

            <form id="feedback-form" method="post" action="Feedback.php" onsubmit="submitForm(event)">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name) ?>" required><br>
                <label for="Feedback">Feedback:</label><br>
                <textarea id="Feedback" name="Feedback" rows="5" value="<?php echo htmlspecialchars($feedback) ?>" required></textarea><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </section>
</main>
    <footer>
        <p>© 2025 Daniel Wright. All rights reserved</p>
    </footer>

</body>

</html>
