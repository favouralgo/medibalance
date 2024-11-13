<?php include '../includes/header.php'; ?>

<div class="customer-form-container">
    <form class="customer-form" method="POST" action="">
        <div class="form-section card">
            <h2 class="section-title">Customer Information</h2>
            <div class="form-fields-grid">
                <div class="form-group">
                    <input 
                        type="text" 
                        id="customer_firstname" 
                        name="customer_firstname" 
                        class="form-input" 
                        placeholder="First Name" 
                        required
                    >
                </div>
                <div class="form-group">
                    <input 
                        type="text" 
                        id="customer_lastname" 
                        name="customer_lastname" 
                        class="form-input" 
                        placeholder="Last Name" 
                        required
                    >
                </div>
                <div class="form-group">
                    <input 
                        type="password" 
                        id="customer_password" 
                        name="customer_password" 
                        class="form-input" 
                        placeholder="Password" 
                        required
                    >
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                        <input 
                            type="email" 
                            id="customer_email" 
                            name="customer_email" 
                            class="form-input" 
                            placeholder="Email Address" 
                            required
                        >
                        <i class="email-icon">✉</i>
                    </div>
                </div>
                <div class="form-group full-width">
                    <input 
                        type="text" 
                        id="customer_address" 
                        name="customer_address" 
                        class="form-input" 
                        placeholder="Address" 
                        required
                    >
                </div>
                <div class="form-group">
                    <input 
                        type="text" 
                        id="customer_city" 
                        name="customer_city" 
                        class="form-input" 
                        placeholder="City" 
                        required
                    >
                </div>
                <div class="form-group">
                    <input 
                        type="text" 
                        id="customer_country" 
                        name="customer_country" 
                        class="form-input" 
                        placeholder="Country" 
                        required
                    >
                </div>
                <div class="form-group">
                    <input 
                        type="tel" 
                        id="customer_phonenumber" 
                        name="customer_phonenumber" 
                        class="form-input" 
                        placeholder="Phone Number" 
                        required
                    >
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="create-customer-btn">Add Customer</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>