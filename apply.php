<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the connection file
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = htmlspecialchars($_POST['email']);
    $adharno = htmlspecialchars($_POST['adharno']);
    $annualInc = htmlspecialchars($_POST['annualincome']);
    $password = htmlspecialchars($_POST['password']);
    $getwhere = htmlspecialchars($_POST['way']);
    $detailsAgreement = isset($_POST['details']) ? 'Yes' : 'No';

    // Check if email exists and password matches in the registrationDetails table
    $sql = "SELECT email, password FROM registrationDetails WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email and password match - Insert data into ApplyDetails table
        $createTableQuery = "
        CREATE TABLE IF NOT EXISTS ApplyDetails (
            email VARCHAR(255) PRIMARY KEY,
            adharno VARCHAR(12) NOT NULL,
            annualinc INT NOT NULL,
            getwhere VARCHAR(50) NOT NULL,
            password VARCHAR(50) NOT NULL
        )";

        if ($conn->query($createTableQuery) === TRUE) {
            // Insert data into ApplyDetails
            $insertQuery = "
            INSERT INTO ApplyDetails (email, adharno, annualinc, getwhere, password) 
            VALUES ('$email', '$adharno', '$annualInc', '$getwhere', '$password')";

            if ($conn->query($insertQuery) === TRUE) {
                echo "
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f9;
                    }
                    .response-message {
                        max-width: 600px;
                        margin: 50px auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        background-color: #ffffff;
                        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                    .response-message h2 {
                        color: #4CAF50;
                        margin-bottom: 20px;
                    }
                    .response-message p {
                        color: #555;
                        font-size: 16px;
                    }
                </style>
                <div class='response-message'>
                    <h2>Application Submitted Successfully</h2>
                    <p>Please wait while we review your application.</p>
                </div>";
            } else {
                echo "<h3 style='color:red; text-align:center;'>Error: " . $conn->error . "</h3>";
            }
        } else {
            echo "<h3 style='color:red; text-align:center;'>Error creating table: " . $conn->error . "</h3>";
        }
    } else {
        // Email not found or password mismatch
        echo "<h3 style='color:red; text-align:center;'>Email not found or password incorrect. Please register first.</h3>";
    }

    // Close the connection
    $conn->close();
} else {
    echo "<h3 style='color:red; text-align:center;'>Invalid request method.</h3>";
}
?>
