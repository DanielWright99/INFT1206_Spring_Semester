<?php
include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Portfolio - INFT 1206</title>

    <!-- Reference custome styles -->
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>

<main>
    <section id="home">
        <h2>Welcome to My Portfolio</h2>
        <P>This is the homepage for my INFT 1206 portfolio</P>
        <img src="../images/profile.svg" alt="Portfolio Image">
    </section>

    <section id="projects">
        <h2>My Projects</h2>
        <p>Below is a summary of my current projects</p>
        <table border="1" style="text-align: center; width: 80%">
            <caption>Projects Summary</caption>
            <tr>
                <th>Project Name</th>
                <th colspan="2">Details</th>
            </tr>
            <tr>
                <td>Portfolio Website</td>
                <td>A personal portfolio for INFT 1206</td>
                <td>In Progress</td>
            </tr>

            <tr>
                <td>Task Manager</td>
                <td>A web application for managing tasks</td>
                <td>Planning</td>
            </tr>

            <tr>
                <td>Blog Platform</td>
                <td>A simple blog with user posts</td>
                <td>Future (backlog)</td>
            </tr>
        </table>
    </section>

    <section id="skills">
        <h2>My Skills</h2>
        <p>Here are some of my technical skills:</p>
        <table border="1" style="text-align: center; width: 60%">
            <caption>Skills Overview</caption>
            <tr>
                <th>Category</th>
                <th>Skill</th>
                <th>Proficiency</th>
            </tr>
            <tr>
                <td>Web Development</td>
                <td>HTML</td>
                <td>Intermediate</td>
            </tr>
            <tr>
                <td>Web Development</td>
                <td>CSS</td>
                <td>Beginner</td>
            </tr>
            <tr>
                <td>Web Development</td>
                <td>PHP</td>
                <td>Beginner</td>
            </tr>
        </table>
    </section>

    <section id="contacts">
        <h2>Contact Information</h2>
        <P>Email: <a href="daniel.wright2@dcmail.ca">daniel.wright2@dcmail.ca</a></P>
        <p>Follow me on social media</p>
        <ul>
            <li><a href="https://www.linkedin.com/in/yourprofile" target="_blank">Linkedin</a></li>
            <li><a href="https://gitlab.com/yourusername" target="_blank">Gitlab</a></li>
        </ul>
    </section>
</main>

<footer>
    <p>2025 Daniel Wright. All rights reserved lalala©</p>
</footer>

</body>

</html>
