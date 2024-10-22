# Student-Management-System
*Introduction*:  
This project is a Student Management System built using MySQL to store and manage student information along with their respective department details. It provides a simple structure to store student records, department information, and relationships between them. The system allows for queries to fetch student details, including those with and without department affiliations, and generate reports on the number of students in each department.

*Features:*
Store basic student information (ID, Name, DOB, Gender, Address, Mobile, Email).
Maintain department records and link students with their respective departments.
Handle cases where students may not be assigned to any department.
Perform SQL queries to fetch specific student and department reports.


![image](https://github.com/user-attachments/assets/758a964c-44c1-4d3e-a52f-762e88ff6035)



# How to Use:
Set up MySQL Database from terminal
mysql

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


