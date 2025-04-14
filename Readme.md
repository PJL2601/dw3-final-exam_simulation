# Mini Product Catalog with Image Upload

A basic product catalog system where users can log in, add products with images, view their products, and delete them.

## Features

- User Login/Logout system
- Add products with name, description, price, and image
- View list of products
- Delete products
- Image upload functionality

## Setup Instructions

1. **Database Setup**
   - Create a MySQL database named `mini_catalog`
   - Run the following SQL queries to create the tables:

   ```sql
   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(50) NOT NULL,
     password VARCHAR(255) NOT NULL
   );

   CREATE TABLE products (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT,
     name VARCHAR(100) NOT NULL,
     description TEXT,
     price DECIMAL(10,2),
     image_path VARCHAR(255),
     FOREIGN KEY (user_id) REFERENCES users(id)
   );
   ```

2. **Create User Account**
   - You'll need to create a user account in the database to log in
   - Use this SQL query (replace the values with your desired username/password):

   ```sql
   INSERT INTO users (username, password) 
   VALUES ('username', '$2y$10$YourHashedPasswordHere');
   ```

   - You can generate a hashed password using PHP's `password_hash()` function
   - Alternatively, create this separate script to add a user:

   ```php
   <?php
   // Create a new user
   require_once 'db.php';
   
   $username = 'admin'; // Change this to your desired username
   $password = 'password123'; // Change this to your desired password
   
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
   $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
   $stmt->execute([$username, $hashed_password]);
   
   echo "User created successfully!";
   ?>
   ```

3. **File Structure**
   Make sure your files are organized as follows:
   ```
   /mini-catalog/
   ├── login.php
   ├── logout.php
   ├── dashboard.php
   ├── add_product.php
   ├── delete_product.php
   ├── db.php
   ├── session.php
   ├── uploads/
   │   └── (images will be stored here)
   ├── css/
   │   └── style.css
   └── README.md
   ```

4. **Permissions**
   - Make sure the `uploads` directory has write permissions

5. **Database Configuration**
   - Update the database connection details in `db.php` to match your environment

## Usage

1. Access the login page at `login.php`
2. Log in with your username and password
3. You'll be redirected to your dashboard
4. Use "Add New Product" to add products with images
5. View your products on the dashboard
6. Use the "Delete" button to remove products

## Requirements

- PHP 7.0 or higher
- MySQL
- GD Library for PHP (for image processing)
