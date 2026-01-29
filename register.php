<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $business_name = $role == 'bakery' ? $_POST['business_name'] : NULL;
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, business_name, address, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password, $role, $business_name, $address, $phone);
    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        $error = "Registration failed.";
    }
}

include 'includes/header.php';
?>

<h2>Register</h2>
<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role" required>
            <option value="customer">Customer</option>
            <option value="bakery">Bakery</option>
        </select>
    </div>
    <div class="mb-3" id="business_name_field" style="display:none;">
        <label for="business_name" class="form-label">Business Name</label>
        <input type="text" class="form-control" id="business_name" name="business_name">
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control" id="address" name="address"></textarea>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>

<script>
document.getElementById('role').addEventListener('change', function() {
    document.getElementById('business_name_field').style.display = this.value == 'bakery' ? 'block' : 'none';
});
</script>

<?php include 'includes/footer.php'; ?>