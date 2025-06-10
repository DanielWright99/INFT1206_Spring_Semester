<?php

//Initialize variable for form processing
$result = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    //read and sanitize input
    $number = isset($_POST["number"]) ? trim($_POST["number"]) : '';
    $convert_to = isset($_POST["convert_to"]) ? trim($_POST["convert_to"]) : '';

    /**
     * Accepts a number argument and returns a binary value
     * @param $number
     * @return string
     */
    function toBinary($number) {
        return decbin((int)$number);
    }

    /**
     * Accepts a number argument and returns a hexadecimal value
     * @return string
     */
    function toHex($number) {
        return dechex((int)$number);
    }

    /**
     * Function to convert from specified base to decimal
     * @param $number
     * @param $base
     * @return float|int
     */
    function fromNumber($number, $base) {
        if($base === "binary"){
            return bindec($number);
        } elseif($base === "hexadecimal"){
            return hexdec($number);
        }
        return (int)$number;
    }

    if( $number !== '' && $convert_to !== '' ) {
        $decimal = fromNumber($number, $_POST['base']);
        if($convert_to === 'binary') {
            $result = toBinary($decimal);
        } elseif($convert_to === 'hexadecimal') {
            $result = toHex($decimal);
        }else{
            $result = $decimal;
        }
        $result = "Result: $result";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Number Converter - INFT 1206</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>

<body>
<header>
    <h1>Number Conversions</h1>
    <nav>
        <ul>
            <li><a href="index.html">Home </a></li>
            <li><a href="about.html">About </a></li>
            <li><a href="projects.html">Project Details </a></li>
            <li><a href="Skills.html">Skills </a></li>
            <li><a href="contact.php">Contact </a></li>
            <li><a href="e-Business.php">e-Business</a></li>
        </ul>
    </nav>
</header>

<main>
    <section>
        <h2>Convert Number</h2>
        <p>Enter a number and select the conversion type:</p>
        <div class="form-container">
            <form method="post" action="converter.php"><br>
                <label for="number">Number: </label><br>
                <input type="text" id="number" name="number" required><br>
                <label for="base"> Input Base:</label><br>
                <select id="base" name="base">
                    <option value="decimal">Decimal</option>
                    <option value="binary">Binary</option>
                    <option value="hexadecimal">Hexadecimal</option>
                </select><br>

                <label for="convert_to">Convert To:</label><br>
                <select id="convert_to" name="convert_to">
                    <option value="decimal">Decimal</option>
                    <option value="binary">Binary</option>
                    <option value="hexadecimal">Hexadecimal</option>
                </select><br>
                <button type="submit">Convert</button>

            </form>
            <!-- Display the conversion result -->
            <?php if($result !== ''): ?>
            <p class="conversion-result"><?php echo htmlspecialchars($result) ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Daniel Wright. All rights reserved.</p>
</footer>

</body>

</html>
