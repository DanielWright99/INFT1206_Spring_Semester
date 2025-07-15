<?php

//start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//start session for navigation


// Dynamically compute base path for weekX/dayY
$scriptPath = $_SERVER['SCRIPT_NAME'];
$weekDayMatch = preg_match('#/(week\d+/day\d+)/#', $scriptPath, $matches);
$weekDayFolder = $weekDayMatch ? $matches[1] : 'week7/day1'; //Fallback to week7/day1
define('BASE_PATH', '/' . $weekDayFolder . '/');
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

