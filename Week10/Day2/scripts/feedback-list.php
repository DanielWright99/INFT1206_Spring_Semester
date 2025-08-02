<?php



session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

require_once '../includes/db_connect.php';

//Initialize variables for form processing
$errors = [];
$success = '';
$user_id = $_SESSION['user_id'];
$is_admin = false;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

//Generate CSRF Token
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//Determine if the user logged in is an 'admin' user
$conn = get_db_connection();
$query = "SELECT role FROM users WHERE id = $1";
$queryResult = pg_query_params($conn, $query, [$user_id]);
if($queryResult && pg_num_rows($queryResult) > 0){
    $row = pg_fetch_assoc($queryResult);
    $is_admin = isset($row['role']) && $row['role'] == 'admin';
}else{
    $errors[] = "Failed to fetch user role";
}
pg_free_result($queryResult);

//Delete feedback logic
if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['delete_id'])){

    if(isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF Token";
    }else{
        $delete_id = (int)$_POST['delete_id'];
        if($is_admin){
            $query = "DELETE FROM users WHERE id = $1";
            $queryResult = pg_query_params($conn, $query);
        }else{
            $query = "DELETE FROM feedback WHERE id = $1 AND user_id = $2";
            $queryResult = pg_query_params($conn, $query, [$delete_id, $user_id]);
        }
        if($queryResult){
            $success = "Feedback deleted";

            $log_query = "INSERT INTO activity_logs (user_id, action, description, created_at) VALUES($1, $2, $3, CURRENT_TIMESTAMP)";
            $log_result = pg_query_params($conn, $log_query, [
                $user_id,
                'feedback_delete',
                'User deleted feedback successfully ',
            ]);
            if(!$log_result){
                $errors[] = "Falied to log feedback deletion. ";
            }
            if ($log_result) {
                pg_free_result($log_result);
            }
        }else{
            $errors[] = "Failed to delete feedback";
        }
        if($queryResult){
            pg_free_result($queryResult);
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
    <title>Feedback List - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css">

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const apikey = 'your_secure_api_key_12345';
            const searchInpuy = document.getElementById('search');
            const feedbackTable = document.getElementById('feedback-table').getElementsByTagName('tbody')[0];

            function fetchFeedback(search = '') {
                fetch(`../api/feedback.php?api_key=${apiKey}&search=${encodeURIComponent(search)}`)
                    .then(response => response.json())
                    .then(data => {
                        feedbackTable.innerHTML = '';
                        if(data.status === 'success' && data.data.length > 0){
                            data.data.forEach(entry => {
                                const row = feedbackTable.insertRow();
                                row.insertCell(0).textContent = entry.name;
                                row.insertCell(1).textContent = entry.feedback;
                                row.insertCell(2).textContent = new Date(entry.submitted_at).toLocaleString();
                                const actionsCell = row.insertCell(3);
                                if(entry.user_id == <?php echo $user_id; ?> || <?php echo $is_admin; ?> 'true : 'false; ?>) {
                                    actionsCell.innerHTML = `
                                    <a href=feedback.php?feedback_id${entry.id}>Edit</a>
                                    <form method="post" action="feedback-list.php" style='display:inline;'>
                                    <input type="hidden" name=csrf_token value="<?php echo htmlspecialchars($_SESSION['csrf_token'])?>"><input>
                                    input type="hidden" name="delete_id" value="${entry.id}">
                                    <button type="submit onclick="return confirm("Confirm Delete?")"> Delete</button>
                                    </form>
                                    `;
                                }
                            })
                        }else{
                            feedbackTable.innerHTML = 'tr><td colspan="4"> No Feedback available</td>></tr>'
                        }
                    })
                    .catch(error =>) {
                        feedbackTable.innerHTML = '<tr><td> colspan="4"> No Feedback available</td></tr>'
                }
            }
            fetchFeedback();

            //search
            document.getElementById('search-form').addEventListener('submit', function(event)){
                event.preventDefault();
                fetchFeedback(searchInput.value);
            })

        });



    </script>

</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <section>
        <h2>Feedback List</h2>
       <p>Search and view Feedback</p>
        <div class="form-container">
            <form method="get" action="feedback-list.php">
                <label for="search">Search Feedback</label>
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Enter Search Keyword"><br>
                <button type="submit">Search</button>
            </form>
        </div>

        <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error) ?></p>
        <?php endforeach ?>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <p class="success-message"> <?php echo htmlspecialchars($success)?></p>
        <?php endif ?>


        <p>Below the feedback submitted by users:</p>
        <?php if(empty($feedbackData)): ?>
        <p>NO feedback available</p>
        <?php else: ?>
        <table class="feedback-table">
            <caption>Submitted Feedback</caption>
            <tr>
                <th>Name</th>
                <th>Feedback</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
            <?php foreach($feedbackData as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['name'])?></td>
                <td><?php echo htmlspecialchars($entry['feedback'])?></td>
                <td><?php echo htmlspecialchars($entry['submitted_at'])?></td>
                <td>
                    <?php if($entry['user_id'] == $user_id || $is_admin): ?>
                    <a href="feedback.php?feedback_id" <?php echo $entry['id']; ?>>Edit</a>
                    <form method="post" action="feedback-list.php" style="display:inline">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="delete_id" value="<?php echo $entry['id']; ?>">
                        <button type="submit" onclick="return confirm('Confirm deletion?')">Delete</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2025 Daniel Wright. All rights reserved</p>
</footer>

</body>
</html>