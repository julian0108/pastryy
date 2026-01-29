// Custom JS for Sweet Delights

// Function to add product to cart via AJAX (prevents page reload)
function addToCart(productId) {
    fetch(`cart.php?action=add&id=${productId}`)
        .then(response => response.text())
        .then(() => {
            alert('Product added to cart!');
            // Optionally update cart count in navbar (if you add a cart count element)
        })
        .catch(error => console.error('Error:', error));
}

// Form validation for registration
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                alert('Password must be at least 6 characters.');
                e.preventDefault();
            }
        });
    }

    // Attach addToCart to buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            addToCart(productId);
        });
    });

    // Cart quantity update (if needed in cart.php)
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            // Update total dynamically (basic example)
            const row = this.closest('tr');
            const price = parseFloat(row.querySelector('.price').textContent.replace('$', ''));
            const qty = parseInt(this.value);
            const totalCell = row.querySelector('.subtotal');
            totalCell.textContent = `$${ (price * qty).toFixed(2) }`;
            updateGrandTotal();
        });
    });

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.subtotal').forEach(cell => {
            grandTotal += parseFloat(cell.textContent.replace('$', ''));
        });
        document.getElementById('grand-total').textContent = `$${grandTotal.toFixed(2)}`;
    }
});