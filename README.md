# MediBalance - Healthcare Payment Management System

MediBalance is a B2B2C e-commerce platform designed to streamline medical payment processing and eliminate common issues in healthcare payments such as receipt falsification and payment diversions.

## 🌟 Features

- **Multi-tenant Architecture**
  - Admin Dashboard for system oversight
  - Healthcare Facility Interface for service providers
  - Patient Portal for end-users
  - Third-party Payment Gateway

- **Key Functionalities**
  - Digital Invoice Generation
  - Secure Payment Processing
  - Real-time Payment Tracking
  - User Authentication & Authorization
  - Facility Verification System
  - Third-party Payment Support
  - Comprehensive Audit Trail

## 🏗️ Architecture

### System Architecture
- MVC (Model-View-Controller) Pattern
- PHP Backend
- MySQL Database
- Bootstrap Frontend
- RESTful API Architecture

### Database Schema
- Modular table structure
- Foreign key constraints for data integrity
- Indexed fields for optimized queries
- Transaction support for critical operations

### Security Features
- Password Hashing (bcrypt)
- Session Management
- CSRF Protection
- Input Sanitization
- Prepared Statements

## 📁 Project Structure
```
medibalance/
├── actions/
│   ├── approve_user_action.php
│   ├── delete_user_action.php
│   ├── update_user_action.php
│   └── ...
├── classes/
│   ├── admin_class.php
│   ├── user_class.php
│   └── ...
├── controllers/
│   ├── admin_controller.php
│   ├── user_controller.php
│   └── ...
├── settings/
│   └── db_class.php
├── view/
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── users.php
│   │   └── ...
│   ├── user/
│   │   └── ...
│   └── customer/
│       └── ...
└── js/
    └── main.js
```

## 🚀 Installation

1. Clone the repository
```bash
git clone https://github.com/favouralgo/medibalance.git
```

2. Import database
```bash
mysql -u username -p medibalance < medibalance.sql
```

3. Configure database connection
```php
// Update settings/db_class.php with your credentials
private $host = "localhost";
private $db_name = "medibalance";
private $username = "your_username";
private $password = "your_password";
```

4. Configure web server
- Point document root to project directory
- Enable URL rewriting if using Apache

## 🔧 Configuration

### Requirements
- PHP 8.2+
- MySQL 5.7+
- Apache/Nginx
- Composer (for dependencies)

### Dependencies
- Bootstrap 5.3.2
- jQuery 3.6.0
- SweetAlert2
- Font Awesome

## 🔒 Security Considerations

- All passwords are hashed using bcrypt
- SQL injection prevention through prepared statements
- XSS protection via output escaping
- CSRF tokens for form submissions
- Secure session handling

## 🗃️ Database Structure

### Core Tables
- `admin`: System administrators
- `user`: Healthcare facilities
- `customer`: Patients
- `facility`: Healthcare facility details
- `product`: Medical services/products
- `invoice`: Payment records
- `status`: Payment statuses

### Relationship Tables
- `customer_products`
- `invoice_product`
- `approval_history`

## 💻 Usage

### Admin Interface
```php
// Login
POST /login/admin_login.php
{
    "email": "admin@example.com",
    "password": "secure_password"
}
```

### Facility Interface
```php
// Generate Invoice
POST /actions/generate_invoice.php
{
    "customer_id": "123",
    "products": [{
        "product_id": "456",
        "quantity": 1
    }]
}
```

### Payment Processing
```php
// Process Payment
POST /actions/process_payment.php
{
    "invoice_number": "MED/24/123456789",
    "amount": 100.00
}
```

## 🧪 Testing

- Unit tests for core functionality
- Integration tests for payment processing
- End-to-end tests for user workflows

## 📚 API Documentation

### Authentication
All API endpoints require authentication through session cookies or API tokens.

### Endpoints
```
GET /api/invoices
POST /api/payments
GET /api/users
POST /api/approve-user
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Open pull request

## 📝 License

This project is licensed under the MIT License - see LICENSE file for details.

## 👥 Team

- Lead Developer: @favouralgo

## 🌟 Acknowledgments

- PHP Community
- Bootstrap Team
- MySQL Community
```


## Tech Stack 🛠️

- **Backend**: PHP (Laravel Framework)
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **Other**: Composer, NPM

## Contributing 🤝

We welcome contributions! Follow these steps to contribute:

1. Fork the repository.
2. Create a new branch for your feature:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add new feature"
   ```
4. Push to your fork:
   ```bash
   git push origin feature-name
   ```
5. Submit a pull request.

## License 📄

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact 📧

For any inquiries or support, feel free to contact the project maintainer at [favouralgo](https://github.com/favouralgo).

---

Made with ❤️ by [favouralgo](https://github.com/favouralgo)
```