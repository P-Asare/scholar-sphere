CREATE DATABASE scholarsphere;
USE scholarsphere;

-- interests of users for linking purposes
CREATE TABLE interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

-- departments faculty and projects can belong to
CREATE TABLE department (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

-- different roles of users (student or faculty)
CREATE TABLE roles (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(255),
    description VARCHAR(255)
);

-- shared documents (Google API)
CREATE TABLE documents (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    filepath VARCHAR(255)
);

-- programs of study in the university for linking purposes
CREATE TABLE programs (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    dep_id INT,
    FOREIGN KEY (dep_id) REFERENCES department(id) ON DELETE CASCADE
);

-- details for profile page of users
CREATE TABLE profile (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bio VARCHAR(255),
    img VARCHAR(255),
    program_id INT,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
);

-- login and other important details of each user
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    fname VARCHAR(255),
    lname VARCHAR(255),
    password VARCHAR(255),
    dob DATE,
    role_id INT NOT NULL,
    profile_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES profile(id) ON DELETE CASCADE
);

-- shows the different interests of a user
CREATE TABLE user_interests (
    user_id INT NOT NULL,
    interest_id INT NOT NULL,
    PRIMARY KEY (user_id, interest_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (interest_id) REFERENCES interests(id) ON DELETE CASCADE
);

-- Each project created by faculty
CREATE TABLE projects (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description VARCHAR(255),
    createdAt DATETIME,
    status ENUM('in_progress', 'completed'),
    document_id INT,
    dep_id INT,
    faculty_id INT,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    FOREIGN KEY (dep_id) REFERENCES department(id) ON DELETE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES users(id) ON DELETE CASCADE
);

-- collaborators on a project
CREATE TABLE project_collaborators (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    user_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- pending collaborators on a project
CREATE TABLE pending_collaborators (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    user_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- store posts from project
CREATE TABLE post (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    img VARCHAR(255),
    comment VARCHAR(255),
    project_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Table to represent user follows relationships
CREATE TABLE follows (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    followee_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (followee_id) REFERENCES users(id) ON DELETE CASCADE
);

-- populate the interests table
INSERT INTO interests (name, description) VALUES
('Artificial Intelligence', 'Research in machine learning, natural language processing, computer vision, etc.'),
('Data Science', 'Research in data analysis, statistics, machine learning, big data, etc.'),
('Bioinformatics', 'Research in computational biology, genomics, proteomics, etc.'),
('Renewable Energy', 'Research in solar energy, wind energy, biofuels, etc.'),
('Climate Change', 'Research in climate modeling, environmental impact assessment, mitigation strategies, etc.'),
('Materials Science', 'Research in nanotechnology, biomaterials, polymers, etc.'),
('Neuroscience', 'Research in brain function, cognitive neuroscience, neural networks, etc.'),
('Quantum Computing', 'Research in quantum algorithms, quantum information theory, quantum cryptography, etc.');

-- Populate the department table
INSERT INTO department (name, description) VALUES
('Math & Economics', 'Department focusing on mathematics and economics research and education.'),
('Engineering', 'Department focusing on engineering disciplines.'),
('CSIS', 'Department focusing on computer science and information systems.'),
('Humanities', 'Department focusing on humanities disciplines.'),
('Business', 'Department focusing on business and management disciplines.');

-- Populate the roles table
INSERT INTO roles (role, description) VALUES
('Student', 'Role representing a student.'),
('Faculty', 'Role representing a faculty member.');

-- Populate the programs table
INSERT INTO programs (name, dep_id) VALUES
('Computer Science', 3),
('Management Information Systems', 3),
('Computer Engineering', 2),
('Economics', 1),
('Electrical and Electronics Engineering', 2),
('Business Administration', 5),
('Mechanical Engineering', 2);
