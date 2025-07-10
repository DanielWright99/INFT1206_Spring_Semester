<?php

//Dynamically compute base path for weekX/dayY
$scriptPath = $_SERVER['SCRIPT_NAME'];
$weekDayMatch = preg_match('#/(week\d+/day\d+)/#', $scriptPath, $matches);
$weekDayFolder = $weekDayMatch ? $matches[1] : 'week7/day1';
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
            <li><a href="<?php echo BASE_PATH?>scripts/e-business.php">E-Business</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/converter.php">Converter</a></li>
            <li><a href="<?php echo BASE_PATH?>scripts/feedback.php">Home</a></li>
        </ul>
    </nav>
</header>