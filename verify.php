<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch username and password from the form
    $username = $_POST['Username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === "vishal_lala" && $password === "lala@123") {
        // If credentials are valid, proceed to admin panel
        echo "<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .header {
            background-color: rgb(35, 67, 132);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e8f5e9;
        }

        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .form-container label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-container input[type='submit'] {
            width: auto;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        .form-container input[type='submit']:hover {
            background-color: #45a049;
        }

        .form-container textarea {
            resize: vertical;
        }

        .logout {
            text-align: center;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            color: white;
            background-color: rgb(35, 67, 132);
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout a:hover {
            background-color: rgb(25, 55, 100);
        }
    </style>
</head>

<body>
    <div class='header'>
        <h1>IIITDM HEALTH AUTHORITY - Admin Panel</h1>
    </div>

    <div class='container'>
        <h1>User Applications</h1>";

        // Include the database connection file
        include('conn.php');

        // Query to join both tables and fetch the necessary details
        $query = "SELECT 
                    ApplyDetails.email,
                    ApplyDetails.adharno,
                    ApplyDetails.annualinc,
                    ApplyDetails.getwhere,
                    ApplyDetails.password AS applyPassword,
                    registrationDetails.name,
                    registrationDetails.mobile,
                    registrationDetails.password AS regPassword
                  FROM ApplyDetails
                  JOIN registrationDetails
                  ON ApplyDetails.email = registrationDetails.email";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>S.No.</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Aadhar Number</th>
                        <th>Annual Income</th>
                        <th>Health Card Delivery</th>
                        <th>Password</th>
                    </tr>";

            $sno = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $sno . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['mobile']) . "</td>
                        <td>" . htmlspecialchars($row['adharno']) . "</td>
                        <td>" . htmlspecialchars($row['annualinc']) . "</td>
                        <td>" . htmlspecialchars($row['getwhere']) . "</td>
                        <td>" . htmlspecialchars($row['applyPassword']) . "</td>
                      </tr>";
                $sno++;
            }
            echo "</table>";
        } else {
            echo "<p>No applications to display.</p>";
        }

        echo "
    </div>

    <div class='form-container'>
        <h2>Transfer Update</h2>
        <form action='updates.php' method='POST'>
            <label for='email'>Email:</label>
            <input type='email' id='email' name='email' required>

            <label for='result'>Result:</label>
            <select id='result' name='result' required>
                <option value='true'>True</option>
                <option value='false'>False</option>
            </select>

            <label for='remarks'>Remarks:</label>
            <textarea id='remarks' name='remarks' rows='4' placeholder='Enter your remarks...' required></textarea>

            <input type='submit' value='Submit'>
        </form>
    </div>

    <div class='logout'>
        <a href='admin.html'>Logout</a>
    </div>
</body>

</html>";
    } else {
        // If credentials are invalid, show an error message
        echo "<h1>Invalid Admin Credentials</h1>";
        echo "<p>Please <a href='admin.html'>try again</a>.</p>";
    }
} else {
    // Redirect to the login form if accessed directly
    header("Location: admin.html");
    exit();
}
?>
