 <?php
     //Set timezone for accurate date/time display
 date_default_timezone_set('America/Toronto');

 //Set the current hour in military time
 $currentHour = date('H');
 if ($currentHour < 12) {
     $welcomeMessage = "Good morning!";
 }elseif ($currentHour < 18){
     $welcomeMessage = "Good afternoon!";
 }else{
     $welcomeMessage = "Good night!";
 }

?>
 <!doctype html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Contact - INFT1206</title>
     <link rel="stylesheet" href="Styles/styles.css">
 </head>
 <body>
    <header>
        <h1>Contact Me</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="projects.html">Project Details</a></li>
                <li><a href="Skills.html">Skills</a></li>
                <li><a href="e-Business.php"> e-Business</a></li>
            </ul>
        </nav>
    </header>
 <main>
     <section>
         <h2>Contact Information</h2>
         <p class="welcome message"><?php echo $welcomeMessage; ?>! Thanks for visiting! </p>
         <!--Output the current date and time in the format 'Month, day, year, hour:minute am/pm' -->
         <p class="php-content">Last Updated: <?php echo date('F j, Y, g:i a'); ?></p>
         <p>Email: <a href="mailto:student@example.com">student@example.com</a></p>
         <p>Follow me on social media</p>
         <ul>
             <li><a href="https://linkedin.com/in/yourprofile" target="_blank"> Linkedin</a> </li>
             <li><a href="https://gitLab.com/yourusername" target="_blank"> gitLab</a> </li>
         </ul>
     </section>

     <section>
         <h2>e-Business Models</h2>
         <p>Examples of common e-business models</p>
         <table>
             <caption>e-Business Models</caption>
             <tr>
                 <th>Model</th>
                 <th>Description</th>
                 <th>Example</th>
             </tr>
             <tr>
                 <td>B2C</td>
                 <td>Business to Consumer: Selling products/services to individuals</td>
                 <td>Amazon</td>
             </tr>
             <tr>
                 <td>C2C</td>
                 <td>Consumer to Consumer: Individuals selling to other individuals</td>
                 <td>eBay</td>
             </tr>
             <tr>
                 <td>C2B</td>
                 <td>Consumer to Business: Individuals offering services to businesses</td>
                 <td>Freelancer</td>
             </tr>
         </table>
     </section>
 </main>

    <footer>
        <p>&copy; 2025 Your Name. All rights reserved.</p>
    </footer>
 </body>
 </html>
