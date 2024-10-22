<?php
// Hanush Singh Rajputh

// Connect to MySQL
$host = "localhost";
$username = "root";
$password = "123456";
$database = "student_db";

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    // Select the newly created database
    $conn->select_db($database);
} else {
    die("Error creating database: " . $conn->error);
}

// Create Department table
$departmentTable = "CREATE TABLE IF NOT EXISTS Department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(50) UNIQUE NOT NULL
)";
if ($conn->query($departmentTable) !== TRUE) {
    die("Error creating Department table: " . $conn->error);
}

// Create Student table
$studentTable = "CREATE TABLE IF NOT EXISTS Student (
    student_id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(50) NOT NULL,
    department_name VARCHAR(50)
)";
if ($conn->query($studentTable) !== TRUE) {
    die("Error creating Student table: " . $conn->error);
}

// Create Mapping table
$mappingTable = "CREATE TABLE IF NOT EXISTS Mapping (
    student_id VARCHAR(50),
    department_id INT,
    FOREIGN KEY (student_id) REFERENCES Student(student_id),
    FOREIGN KEY (department_id) REFERENCES Department(department_id)
)";
if ($conn->query($mappingTable) !== TRUE) {
    die("Error creating Mapping table: " . $conn->error);
}

// Handling form submission for Create, Read, Update, and Delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // Get the action from the form

    // Create Operation
    if ($action == "create") {
        $name = $_POST['name'];
        $student_id = $_POST['student_id'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $department_name = strtolower($_POST['department']);

        // Default to "No Department" if empty
        if (empty($department_name)) {
            $department_name = "no department";
        }

        // Insert into Department table if it doesn't exist
        $sql = "INSERT INTO Department (department_name) VALUES (?)
                ON DUPLICATE KEY UPDATE department_name=department_name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $department_name);
        if ($stmt->execute() !== TRUE) {
            echo "Error: " . $stmt->error . "<br>";
        }
        $stmt->close();

        // Get department_id
        $sql = "SELECT department_id FROM Department WHERE department_name=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $department_name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $department_id = $row['department_id'];
            $stmt->close();

            // Insert into Student table
            $sql = "INSERT INTO Student (student_id, name, dob, gender, address, mobile, email, department_name)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $student_id, $name, $dob, $gender, $address, $mobile, $email, $department_name);
            if ($stmt->execute() === TRUE) {
                echo "New student record created successfully.<br>";

                // Insert into Mapping table
                $sql = "INSERT INTO Mapping (student_id, department_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $student_id, $department_id);
                if ($stmt->execute() === TRUE) {
                    echo "Mapping record created successfully.<br>";
                } else {
                    echo "Error: " . $stmt->error . "<br>";
                }
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            echo "Error fetching department_id: " . $stmt->error . "<br>";
        }
    }
    // Read Operation
    else if ($action == "read") {
        $sql = "SELECT Student.*, Department.department_name 
                FROM Student
                LEFT JOIN Mapping ON Student.student_id = Mapping.student_id
                LEFT JOIN Department ON Mapping.department_id = Department.department_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Student Details</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "ID: " . $row["student_id"] . "<br>";
                echo "Name: " . $row["name"] . "<br>";
                echo "DOB: " . $row["dob"] . "<br>";
                echo "Gender: " . $row["gender"] . "<br>";
                echo "Address: " . $row["address"] . "<br>";
                echo "Mobile: " . $row["mobile"] . "<br>";
                echo "Email: " . $row["email"] . "<br>";
                echo "Department: " . $row["department_name"] . "<br><br>";
            }
        } else {
            echo "No student records found.";
        }
    }
    // Update Operation
    else if ($action == "update") {
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $department_name = strtolower($_POST['department']);

        // Update the Student table
        $sql = "UPDATE Student SET name=?, dob=?, gender=?, address=?, mobile=?, email=?, department_name=? WHERE student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $dob, $gender, $address, $mobile, $email, $department_name, $student_id);
        if ($stmt->execute() === TRUE) {
            echo "Student record updated successfully.<br>";
        } else {
            echo "Error updating record: " . $stmt->error . "<br>";
        }
        $stmt->close();
    }
    // Delete Operation
    else if ($action == "delete") {
        $student_id = $_POST['student_id'];

        // First, remove from Mapping table
        $sql = "DELETE FROM Mapping WHERE student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $stmt->close();

        // Then remove from Student table
        $sql = "DELETE FROM Student WHERE student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        if ($stmt->execute() === TRUE) {
            echo "Student record deleted successfully.<br>";
        } else {
            echo "Error deleting record: " . $stmt->error . "<br>";
        }
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
