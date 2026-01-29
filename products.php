<?php
include 'config.php';

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Fetch reviews
$review_query = "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ?";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $id);
$review_stmt->execute();
$reviews = $review_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    $review_stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = ?, comment = ?");
    $review_stmt->bind_param("iiissi", $id, $user_id, $rating, $comment, $rating, $comment);
    $review_stmt->execute();
    header("Location: products.php?id=$id");
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-6">
        <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/500'; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="col-md-6">
        <h2><?php echo $product['name']; ?></h2>
        <p><?php echo $product['description']; ?></p>
        <p class="fw-bold">Price: $<?php echo $product['price']; ?></p>
        <p>Stock: <?php echo $product['stock']; ?></p>
        <button class="btn btn-success add-to-cart-btn" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
    </div>
</div>

<h3>Reviews</h3>
<?php foreach ($reviews as $review): ?>
    <div class="border p-3 mb-3 rounded">
        <strong><?php echo $review['name']; ?> (<?php echo $review['rating']; ?>/5)</strong>
        <p><?php echo $review['comment']; ?></p>
    </div>
<?php