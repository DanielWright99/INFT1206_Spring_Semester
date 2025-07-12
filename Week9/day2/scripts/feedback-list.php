<?php

include '../includes/header.php';

require_once '../includes/db_connect.php';

if(!isset($S_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}





$conn = get_db_connection();
$feedbackData = [];
if($conn){
    $query = "SELECT name, feedback, submitted_at FROM feedback ORDER BY submitted_at DESC";
    $result = pg_query($conn, $query);
    if($result){
        while($row = pg_fetch_assoc($result)){
            $feedbackData[] = $row;
        }
    }
    pg_close($conn);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback List - INFT 1206</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<main>
    <section>
        <h2>Feedback List</h2>
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
            </tr>
            <?php foreach($feedbackData as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['name'])?></td>
                <td><?php echo htmlspecialchars($entry['feedback'])?></td>
                <td><?php echo htmlspecialchars($entry['submitted_at'])?></td>
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