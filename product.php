<?php
require_once 'db.php';
require_once 'session.php';


requireLogin();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    
    if (empty($name)) {
        $error = "Product name is required";
    } elseif ($price <= 0) {
        $error = "Price must be greater than zero";
    } else {
        $image_path = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $max_size = 2 * 1024 * 1024;
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $error = "Only JPG, JPEG and PNG files are allowed";
            }
            elseif ($_FILES['image']['size'] > $max_size) {
                $error = "File size must be less than 2MB";
            } 
            else {
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                $filename = uniqid() . '_' . basename($_FILES['image']['name']);
                $upload_path = 'uploads/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_path = $upload_path;
                } else {
                    $error = "Failed to upload image";
                }
            }
        }
        if (empty($error)) {
            $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$_SESSION['user_id'], $name, $description, $price, $image_path]);
            
            if ($result) {
                header("Location: dashboard.php?success=Product added successfully");
                exit();
            } else {
                $error = "Failed to add product";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Mini Product Catalog</title>
    <link rel="stylesheet" href="mini.css">
</head>
<body>
    <div class="container">
        <h1>Add New Product</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image">
                <p class="help-text">Allowed file types: JPG, JPEG, PNG. Max size: 2MB</p>
            </div>
            
            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn">Add Product</button>
            </div>
        </form>
    </div>
</body>
</html>