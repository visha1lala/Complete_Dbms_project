<?php
// Include the database connection file
include('conn.php');

// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];

    // Query to check if email exists in registrationDetails table
    $query = "SELECT * FROM registrationDetails WHERE email = ?";
    
    // Prepare the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if email exists in registrationDetails table
        if ($result->num_rows > 0) {
            // Check if the password matches in ApplyDetails table for the same email
            $query2 = "SELECT * FROM ApplyDetails WHERE email = ? AND password = ?";
            
            if ($stmt2 = $conn->prepare($query2)) {
                $stmt2->bind_param("ss", $email, $password);
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                // If password is correct in ApplyDetails
                if ($result2->num_rows > 0) {
                    // Now, check if the email is in FinalUpdates table and its status
                    $query3 = "SELECT * FROM FinalUpdates WHERE email = ?";
                    
                    if ($stmt3 = $conn->prepare($query3)) {
                        $stmt3->bind_param("s", $email);
                        $stmt3->execute();
                        $result3 = $stmt3->get_result();

                        // If email is found in FinalUpdates table
                        if ($result3->num_rows > 0) {
                            $row = $result3->fetch_assoc();
                            $status = $row['status'];
                            $remarks = $row['remarks'];

                            // Applying styles for final message
                            echo "<div class='message-container'>";
                            if ($status) {
                                // If status is true (approved)
                                echo "<p class='success-message'>Congratulations for getting the health card!</p>";
                                echo "<p class='remarks'>Remarks by admin: " . htmlspecialchars($remarks) . "</p>";
                            } else {
                                // If status is false (rejected)
                                echo "<p class='error-message'>Sorry, your application is rejected.</p>";
                                echo "<p class='remarks'>Remarks by admin: " . htmlspecialchars($remarks) . "</p>";
                            }
                            echo "</div>";
                        } else {
                            // If email is not found in FinalUpdates table
                            echo "<div class='message-container'>";
                            echo "<p class='info-message'>Please wait until your application is reviewed.</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Error in FinalUpdates query: " . $conn->error . "</p>";
                    }
                } else {
                    // If password doesn't match in ApplyDetails
                    echo "<div class='message-container'>";
                    echo "<p class='error-message'>Wrong credentials. Please check your email and password.</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Error in ApplyDetails query: " . $conn->error . "</p>";
            }
        } else {
            // If email is not found in registrationDetails
            echo "<div class='message-container'>";
            echo "<p class='error-message'>Wrong credentials. Please check your email and password.</p>";
            echo "</div>";
        }
        // Close the statement
        $stmt->close();
    } else {
        echo "<p>Error in registrationDetails query: " . $conn->error . "</p>";
    }

    // Close the second and third prepared statements
    if (isset($stmt2)) $stmt2->close();
    if (isset($stmt3)) $stmt3->close();
}

// Close the connection
$conn->close();
?>

<!-- Add the following CSS for styling the message -->

<style>
    .message-container {
        text-align: center;
        padding: 20px;
        margin: 20px;
        border-radius: 10px;
    }

    .success-message {
        color: green;
        font-size: 20px;
        font-weight: bold;
        background-color: #e8f9e8;
        padding: 10px;
        border-radius: 5px;
    }

    .error-message {
        color: red;
        font-size: 20px;
        font-weight: bold;
        background-color: #f9e8e8;
        padding: 10px;
        border-radius: 5px;
    }

    .info-message {
        color: #0056b3;
        font-size: 20px;
        font-weight: bold;
        background-color: #e8f4fc;
        padding: 10px;
        border-radius: 5px;
    }

    .remarks {
        color: #555;
        font-size: 18px;
        margin-top: 10px;
    }
</style>
