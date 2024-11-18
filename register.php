<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the connection file
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['Name']);
    $age = htmlspecialchars($_POST['age']);
    $email = htmlspecialchars($_POST['email']);
    $gender = htmlspecialchars($_POST['gender']);
    $address = htmlspecialchars($_POST['address']);
    $pincode = htmlspecialchars($_POST['pincode']);
    $mobile = htmlspecialchars($_POST['mobilenumber']);
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['cpassword']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<h3 style='color:red; text-align:center;'>Passwords do not match. Please go back and try again.</h3>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into registrationDetails table
    $sql = "INSERT INTO registrationDetails (name, age, email, gender, address, pincode, mobile, password) 
            VALUES ('$name', '$age', '$email', '$gender', '$address', '$pincode', '$mobile', '$password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
            }
            .success-message {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                border: 1px solid #cfcfcf;
                border-radius: 10px;
                background-color: #ffffff;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }
            .success-message h2 {
                color: #4CAF50;
                text-align: center;
            }
            .success-message p {
                margin: 10px 0;
                color: #555;
            }
            .success-message strong {
                color: #333;
            }
        </style>
        <div class='success-message'>
            <h2>Registration Successful</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Age:</strong> $age</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Gender:</strong> $gender</p>
            <p><strong>Address:</strong> $address</p>
            <p><strong>Pincode:</strong> $pincode</p>
            <p><strong>Mobile Number:</strong> $mobile</p>
        </div>";
    } else {
        echo "<h3 style='color:red; text-align:center;'>Error: " . $conn->error . "</h3>";
    }

    // Close the connection
    $conn->close();
} else {
    echo "<h3 style='color:red; text-align:center;'>Invalid request method.</h3>";
}
?>
