<?php
// Include the database connection file
include('conn.php');

// Check if the form data is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $result = $_POST['result'] === 'true' ? 1 : 0; // Convert 'true'/'false' to boolean (1/0)
    $remarks = $_POST['remarks'];

    // Prepare the SQL query to insert the data into FinalUpdates table
    $query = "INSERT INTO FinalUpdates (email, status, remarks) 
              VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE 
              status = VALUES(status), remarks = VALUES(remarks)";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters to the query
        $stmt->bind_param("sis", $email, $result, $remarks); // 's' for string, 'i' for integer

        // Execute the query
        if ($stmt->execute()) {
            // If successful, display a success message
            echo "<p>Update has been successfully recorded!</p>";
        } else {
            // If there's an error, display an error message
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Close the connection
$conn->close();
?>

<!-- Provide a link to go back to the verify.php page -->
<p><a href="verify.php">Back to Verify Applications</a></p>
