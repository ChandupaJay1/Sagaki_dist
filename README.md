<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sagaki Distribution Management System

A comprehensive Distribution Management System (DMS) built with Laravel 12, designed to handle end-to-end distribution, inventory, and sales operations.

## Features

### Customer Management
- Complete CRM functionality with customer profiles
- Customer categorization and routing
- Credit limit management
- VAT/SVAT registration tracking
- Multiple contact persons per customer
- Bank details for payment processing
- Route-based customer assignment

### Vendor/Supplier Management
- Supplier registration and categorization
- Vendor contact management
- Purchase order integration

### Product & Inventory Management
- Multi-level product hierarchy (Main Products → Sub-products)
- Product categorization (Item Categories, Sub-categories)
- Brand and Model management
- Location/Warehouse tracking (Floor, Rack, Row, Bin)
- Unit of measurement support
- Reorder points and alert quantities
- Serialized item tracking
- Multi-price levels support
- Product cost and pricing (Min/Max sale prices)

### Sales & Distribution
- Sales Order management
- Customer-specific pricing
- Route-based delivery scheduling
- Sales Returns processing
- Territory and Area management

### Purchasing & Receiving
- Purchase Order creation
- Goods Receipt Notes (GRN)
- GRN Returns
- Vendor return management

### Inventory Operations
- Stock Adjustments
- Inventory Transfers between locations
- Stock level monitoring

### Financial Management
- Account/Chart of Accounts
- Multi-currency support
- Payment Terms management
- Project-based accounting

### Territory & Route Management
- Territory hierarchy
- Area management
- Route configuration
- Sales Representative assignment

### API & Mobile Support
- RESTful API endpoints
- Laravel Sanctum authentication
- Token-based login
- Serial number validation for license control

## Technology Stack

- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Database:** MySQL/MariaDB (configurable)
- **Authentication:** Laravel Sanctum
- **Frontend:** Blade Templates + Vite (Bootstrap/custom CSS)

## Project Structure

```
Sagaki_distribution/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AccountController.php      # Financial accounts
│   │   │   ├── AdminController.php        # Admin functions
│   │   │   ├── AreaController.php         # Area management
│   │   │   ├── BrandController.php        # Product brands
│   │   │   ├── CategoryController.php     # Categories
│   │   │   ├── CurrencyController.php     # Currency management
│   │   │   ├── CustomerCategoryController.php
│   │   │   ├── CustomerController.php     # Customer CRM
│   │   │   ├── GrnController.php           # Goods Receipt Notes
│   │   │   ├── GrnReturnController.php    # GRN returns
│   │   │   ├── InventoryTransferController.php
│   │   │   ├── InvoiceController.php      # Invoicing
│   │   │   ├── ItemCategoryController.php # Product categories
│   │   │   ├── LocationController.php     # Warehouses
│   │   │   ├── ModelMasterController.php  # Product models
│   │   │   ├── ProductController.php      # Product management
│   │   │   ├── ProductSubCategoryController.php
│   │   │   ├── ProjectController.php       # Projects
│   │   │   ├── PurchaseOrderController.php
│   │   │   ├── RouteController.php        # Route management
│   │   │   ├── SalesOrderController.php
│   │   │   ├── SalesReturnController.php
│   │   │   ├── StockAdjustmentController.php
│   │   │   ├── SupplierCategoryController.php
│   │   │   ├── TermController.php         # Payment terms
│   │   │   ├── TerritoryController.php    # Territory management
│   │   │   ├── UnitController.php          # Unit of measure
│   │   │   ├── VendorController.php        # Supplier management
│   │   │   └── Api/                        # REST API endpoints
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── CheckAccountStatus.php
│   └── Models/
│       ├── Account.php
│       ├── Area.php
│       ├── Brand.php
│       ├── Category.php
│       ├── Currency.php
│       ├── Customer.php
│       ├── CustomerCategory.php
│       ├── Grn.php
│       ├── GrnReturn.php
│       ├── Invoice.php
│       ├── Location.php
│       ├── ModelMaster.php
│       ├── Product.php
│       ├── ProductSubCategory.php
│       ├── Project.php
│       ├── PurchaseOrder.php
│       ├── Route.php
│       ├── SalesOrder.php
│       ├── SalesReturn.php
│       ├── StockAdjustment.php
│       ├── SupplierCategory.php
│       ├── Territory.php
│       ├── Unit.php
│       ├── User.php
│       └── Vendor.php
├── database/
│   └── migrations/                         # Database migrations
├── config/                                  # Laravel configuration
├── public/assets/                          # Static assets (CSS, JS, Images)
└── routes/                                 # Route definitions
```

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL/MariaDB or SQLite (for development)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Sagaki_distribution
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sagaki_distribution
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Install frontend dependencies**
   ```bash
   npm install
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

### Using the Setup Script

Laravel provides a setup script that automates most of these steps:

```bash
composer setup
```

## API Documentation

### Authentication

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "your-password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "token": "your-sanctum-token",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "admin",
        "serial_number": "SAGAKI-XXXXXXXX",
        "is_active": true
    }
}
```

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/login` | User authentication |
| GET | `/api/customers` | List customers |
| POST | `/api/customers` | Create customer |
| GET | `/api/products` | List products |
| POST | `/api/products` | Create product |
| GET | `/api/vendors` | List vendors |
| POST | `/api/vendors` | Create vendor |

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Thank you for considering contributing to the Sagaki Distribution Management System! Please feel free to submit pull requests or create issues for bugs and feature requests.

## Support

For support and questions, please contact the development team.

## Screenshots

The system includes a modern dashboard interface with:
- Customer management views
- Product inventory management
- Sales and purchase order processing
- Route and territory visualization
- Financial reporting dashboards
