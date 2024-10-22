# Student-Management-System
Run mysql command in the terminal

# Step 1: Create a Database (if not already created)
CREATE DATABASE university_db;

# Step 2: Use the Database
USE university_db;

# Step 3: Create the Tables (if not already created)
CREATE TABLE Department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) UNIQUE NOT NULL
);
CREATE TABLE Student (
    student_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    dob DATE,
    gender VARCHAR(10),
    address VARCHAR(100),
    mobile VARCHAR(15),
    email VARCHAR(50),
    department_name VARCHAR(100),
    FOREIGN KEY (department_name) REFERENCES Department(department_name)
    ON DELETE SET NULL ON UPDATE CASCADE
);

# Step 4: Insert Data into Department Table
INSERT INTO Department (department_name) VALUES 
('Human Intelligence'),
('Rocket Engineering'),
('Artificial Intelligence'),
('Astrophysics');

# Step 5: Insert Data into Student Table
INSERT INTO Student (student_id, name, dob, gender, address, mobile, email, department_name) VALUES
('112299', 'Elon', '2000-01-20', 'Male', 'SpaceX Office', '9988776654', 'elonmusk@spacex.com', 'Human Intelligence'),
('112300', 'Alice', '1998-04-10', 'Female', 'Mars Colony Alpha', '9898989898', 'alice@mars.com', 'Artificial Intelligence'),
('112301', 'Bob', '2001-08-15', 'Male', 'Earth Base HQ', '9876543210', 'bob@earth.com', NULL),
('112302', 'Charlie', '1997-11-02', 'Other', 'Moon Research Facility', '9123456789', 'charlie@moon.org', 'Astrophysics');



# Explanation:
CREATE DATABASE: If you haven’t created the database, this command will do it.
USE DATABASE: Selects the database to work with.
CREATE TABLE: Makes sure the necessary tables exist.
INSERT INTO: Now the insert statements will work because you are using the correct database.
Let me know if you encounter any other issues!


# Fetch Student Details with Department Information

SELECT 
    Student.student_id, 
    Student.name, 
    Student.dob, 
    Student.gender, 
    Student.address, 
    Student.mobile, 
    Student.email, 
    Department.department_name
FROM 
    Student
JOIN 
    Department ON Student.department_name = Department.department_name;
# Fetch All Student Details Including Those Without Department

SELECT 
    Student.student_id, 
    Student.name, 
    Student.dob, 
    Student.gender, 
    Student.address, 
    Student.mobile, 
    Student.email, 
    Department.department_name
FROM 
    Student
LEFT JOIN 
    Department ON Student.department_name = Department.department_name;
# Obtain Department Name and Count of Associated Students

SELECT 
    Department.department_name, 
    COUNT(Student.student_id) AS student_count
FROM 
    Department
LEFT JOIN 
    Student ON Student.department_name = Department.department_name
GROUP BY 
    Department.department_name;
How to Run These Commands:
Open your MySQL terminal.
Use the database you created with:
USE university_db;


Copy and paste each query into the terminal and press Enter.
