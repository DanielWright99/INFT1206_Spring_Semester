<?php

//start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Dynamically compute base path for weekX/dayY
$scriptPath = $_SERVER['SCRIPT_NAME'];
$weekDayMatch = preg_match('#/(week\d+/day\d+)/#', $scriptPath, $matches);
$weekDayFolder = $weekDayMatch ? $matches[1] : 'week7/day1'; //Fallback to week7/day1
define('BASE_PATH', '/' . $weekDayFolder . '/');

//Fetch the profile picture if uer is logged in
$profile_picture = '';
if(isset($_SESSION['uer_id'])) {
    require_once '../includes/db_connect.php';
    $conn = get_db_connection();
    $query = 'SELECT profile_picture FROM users WHERE id = $1';
    $result = pg_query_params($conn, $query, [$_SESSION['uer_id']]);
    if($result && pg_num_rows($result) > 0){
        $row = pg_fetch_assoc($result);
        $profile_picture = $row['profile_picture'];
    }
    pg_free_result($result);
    pg_close($conn);
}


?>
<header>
    <h1>My Personal Portfolio</h1>
    <nav>
        <ul>
            <li><a href="<?php echo BASE_PATH?>views/index.php">Home</a></li>
            <li><a href="<?php echo BASE_PATH?>views/about.php">About</a></li>
            <li><a href="<?php echo BASE_PATH?>views/projects.php">Project Details</a></li>
            <li><a href="<?php echo BASE_PATH?>views/skills.php">Skills</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/contact.php">Contact</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/e-business.php">e-Business</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/converter.php">Converter</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/feedback.php">Feedback</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
<li>
                <?php if ($profile_picture): ?>
                    <img src="<?php echo BASE_PATH; ?>images/<?php echo htmlspecialchars($profile_picture); ?>"
                    alt="Profile Picture" class="nav-profile-picture">
                <?php endif; ?>
</li>
                <li>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></li>
                <li><a href="<?php echo BASE_PATH?>scripts/feedback-list.php">Feedback List</a></li>
                <li><a href="<?php echo BASE_PATH?>scripts/profile.php">Profile</a></li>
                <li><a href="<?php echo BASE_PATH?>scripts/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_PATH?>scripts/register.php">Register</a></li>
                <li><a href="<?php echo BASE_PATH?>scripts/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

