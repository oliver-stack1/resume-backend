<?php
// Yahan Clever Cloud ki details dalein
$host = "brw82kyqkjtibyvmca6y-mysql.services.clever-cloud.com"; // jaise: xyz-mysql.services.clever-cloud.com
$user = "u2qws6pnmmwsvxsv"; 
$pass = "HtmH5WOsXuP89H1embnm"; 
$dbname = "brw82kyqkjtibyvmca6y"; 

// Database se connect karna
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

// Automatically 'users' table banana (agar nahi bani hai toh)
$table_query = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    skills TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_query);

// Android App se data receive karna aur save karna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $skills = $_POST['skills'];
    
    // Password Encryption (Aage chalkar isko user input se replace karenge)
    $hashed_password = password_hash("User@123", PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, skills) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $skills);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data saved successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error saving data"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "success", "message" => "API is live! Ready for Android App."]);
}
$conn->close();
?>
