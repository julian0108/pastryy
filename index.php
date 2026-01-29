<?php
include 'config.php';

// Fetch products
$query = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC";
$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);

// Get categories for filter
$category_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL";
$category_result = $conn->query($category_query);
$categories = $category_result->fetch_all(MYSQLI_ASSOC);

include 'includes/header.php';
?>

<h1>Welcome to Sweet Delights</h1>
<p>Delicious baked goods from local bakeries!</p>

<!-- Category Filter -->
<form method="GET" class="mb-4">
    <label for="category" class="form-label">Filter by Category:</label>
    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
        <option value="">All</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['category']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : ''; ?>><?php echo $cat['category']; ?></option>
        <?php endforeach; ?>
    </select>
</form>

<div class="row">
    <?php
    $filtered_products = $products;
    if (isset($_GET['category']) && $_GET['category'] != '') {
        $filtered_products = array_filter($products, function($p) { return $p['category'] == $_GET['category']; });
    }
    foreach ($filtered_products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/300'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text"><?php echo substr($product['description'], 0, 100); ?>...</p>
                    <p class="card-text fw-bold">$<?php echo $product['price']; ?></p>
                    <div class="mt-auto">
                        <a href="products.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        <button class="btn btn-success add-to-cart-btn" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>