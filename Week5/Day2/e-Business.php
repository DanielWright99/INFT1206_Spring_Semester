<?php
//Define page title dynamically
$pageTitle = "e-Business vs. Traditional Retail";
?>
<!-- Added New file -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?> - INFT 1206</title>
    <link rel="stylesheet" href="Styles/styles.css" >
</head>
<body>
<header>
    <h1><?php echo $pageTitle; ?></h1>
    <nav>
        <ul>
            <li><a href="index.html">Home </a></li>
            <li><a href="about.html">About </a></li>
            <li><a href="projects.html">Project Details </a></li>
            <li><a href="contact.php">Contact </a></li>
            <li><a href="Skills.html">Skills </a></li>
        </ul>
    </nav>
</header>
<main>
    <section>
        <h2>Comparison of e-Business and Traditional Retail</h2>
        <p> Below is a comparison of e-Business and traditional retail</p>
    </section>
    <table>
        <caption>e-Business vs. Traditional Retail</caption>
        <tr>
            <th>Aspect</th>
            <th>e-Business Advantages</th>
            <th>e-Business Disadvantages</th>
            <th>Traditional Retail Advantages</th>
            <th>Traditional Retail Disadvantages</th>
        </tr>
        <tr>
            <td>Reach</td>
            <td>Global market access</td>
            <td>Limited personal interaction</td>
            <td>Local customer loyalty</td>
            <td>Limited physical location</td>
        </tr>
        <tr>
            <td>Cost</td>
            <td>Lower overhead (no physical store)</td>
            <td>Shipping/Logistics costs</td>
            <td>No shipping costs</td>
            <td>High rent/maintenance costs</td>
        </tr>
        <tr>
            <td>Customer Experience</td>
            <td>24/7 availability, convenience</td>
            <td>No tactile product experience</td>
            <td>Hands-on product interaction</td>
            <td>Limited operating hours</td>
        </tr>
        <tr>
            <td>Security</td>
            <td>Secure online payments possible</td>
            <td>Risk of data breaches</td>
            <td>Lower cyber risk</td>
            <td>Physical threat risks</td>
        </tr>
    </table>
</main>

    <footer>
    <p>© 2025 Daniel Wright. All rights reserved</p>
</footer>

</body>
</html>