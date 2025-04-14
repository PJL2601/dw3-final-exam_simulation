<?php
require_once 'db.php';
require_once 'session.php';


requireLogin();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);
    $product = $stmt->fetch();

    if ($product) {
        $delete_stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
        $result = $delete_stmt->execute([$product_id, $_SESSION['user_id']]);
        
        if ($result) {
            if (!empty($product['image_path']) && file_exists($product['image_path'])) {
                unlink($product['image_path']);
            }
            
            header("Location: dashboard.php?success=Product deleted successfully");
        } else {
            header("Location: dashboard.php?error=Failed to delete product");
        }
    } else {
        header("Location: dashboard.php?error=Product not found");
    }
} else {
    header("Location: dashboard.php");
}
exit();
?>