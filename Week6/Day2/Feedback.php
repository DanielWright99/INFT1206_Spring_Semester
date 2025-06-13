<?php

//Initialize variations for form processing
$error = [];
$success = "";
$name = "";
$feedback = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

        $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $feedback = isset($_POST["feedback"]) ? trim($_POST["feedback"]) : "";


    //validate input
    if(empty($name)){
        $error[] = "Name is required";
    }elseif (strlen($name) < 3){
        $error[] = "Name must be at least 3 characters long";
    }


    if(empty($feedback)){
        $error[] = "Feedback is required";
    }elseif (strlen($feedback) < 10){
        $error[] = "Feedback must be at least 10 characters long";
    }

    if(empty($error)){
        $success = "Thank you for your feedback, " . htmlspecialchars($name);
        $name = "";
        $feedback = "";
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback - INFT 1206</title>
    <link rel="stylesheet" href="Styles/styles.css" >
</head>

<body>
    <header>
        <h1>Feedback</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home </a></li>
                <li><a href="about.html">About </a></li>
                <li><a href="projects.html">Project Details </a></li>
                <li><a href="contact.php">Contact </a></li>
                <li><a href="Skills.html">Skills </a></li>
                <li><a href="e-Business.php">E-Business</a></li>
                <li><a href="converter.php">Converter</a></li>
            </ul>
        </nav>
    </header>

<main>
    <section>
        <h2>Provide Feedback</h2>
        <p>Please share your feedback about the portfolio</p>
        <div class="form-container">
            <?php if(!empty($errors)): ?>
            <?php foreach ($errors as $error ):?>
             <p class="error-message"><?php echo htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if($success !== ""): ?>
            <p class="success-message"> <?php echo htmlspecialchars($success)?></p>
            <?php endif; ?>
            <form method="post" action="Feedback.php">
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
