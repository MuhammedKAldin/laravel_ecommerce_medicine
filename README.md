# Medical E-commerce Platform

A Laravel-based e-commerce platform specialized for medical and healthcare products.

 ## 🐱‍💻 Objective example
    ```bash
    Build a  mini medical e-commerce system  using  Laravel  + Blade + MySQL  , simulating a 
    simplified version of our internal admin and customer platform. The focus is on functionality, 
    structure, and code quality.
    ``` 

2. User account:
   ```bash
    emaIl : mohamed@gmail.com
    password : password
   ```

## 👤 Accounts example
1. Admin account:
   ```bash
    emaIl : admin@gmail.com
    password : password
   ```
2. User account:
   ```bash
    emaIl : mohamed@gmail.com
    password : password
   ```
## 💻 Technology Stack

- **Backend Framework**: Laravel
- **Frontend**: 
  - Blade templating
  - TailwindCSS
  - Bootstrap
  - JavaScript
- **Database**: MySQL

## 🚀 Getting Started

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install Node dependencies:
   ```bash
   npm install
   ```
4. Configure your environment:
   - Copy `.env.example` to `.env`
   - Configure your database settings
   
5. Run migrations:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

7. Compile assets:
   ```bash
   npm run dev
   ```

## 🏗 Project Structure

Admin Directories
```
national-care/
├── app/                    
│   ├── Http/Controllers/admin # Controllers for Admin logic
├── resources/
│   ├── views/admin            # Blade templates for Admin page
├── public/admin              # Publicly accessible files for Admin page
```

```
national-care/
├── app/                    # Core application code
│   ├── Http/              # Controllers, Middleware, Requests
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic services
│   ├── Observers/         # Model observers
│   ├── Events/            # Event classes
│   ├── Listeners/         # Event listeners
│   └── Providers/         # Service providers
├── routes/                # Application routes
│   ├── web.php           # Web routes
│   └── auth.php          # Authentication routes
├── resources/            # Frontend resources
│   ├── views/            # Blade templates
│   ├── js/              # JavaScript files
│   └── css/             # CSS/SCSS files
├── public/              # Publicly accessible files
├── database/            # Database migrations and seeders
├── config/             # Configuration files
├── storage/            # Application storage
└── tests/              # Test files
```

## 🔑 Key Components

### Authentication & Authorization
- Laravel Breeze for authentication system
- Admin middleware for administrative access control
- Role-based access control (User/Admin) via Boolean check "is_admin" in User model

### User Management
- User profiles with address and contact information
- Order history
- Dashboard for both users and administrators

### Product Management
- Complete CRUD operations for products
- Product logging system
- Image handling and storage
- Inventory management

### Orders Management
- CRUD operations of "customer_invoices_details" created by the User which is the Items Added to the Invoice  
- Update status of "customer_invoice"
- Display User's information created the "customer_invoice"
- Invoice is accessable by Auth/Guest users with the link corresponding to the Invoice code

### Shopping System
- Shopping cart functionality
- Checkout process
- Order Confirmation and Invoice generation and handling

## 🛣 Route Structure

### Public Routes
- Home page: `/`
- Products listing: `/products`
- Single product: `/products/{id}`
- Cart management: `/cart`
- Checkout: `/checkout`
- Order confirmation: `/order-confirmation`

### Authenticated User Routes
- User dashboard: `/dashboard`
- Profile management: `/profile`
- Order history: `/orders`

### Admin Routes (Requires Auth & Admin)
- Admin dashboard: `/admin/dashboard`
- Product management: `/admin/products`
- Product logs: `/admin/products/logs`
- Invoice management: `/admin/invoices`

## 🔧 Development Guidelines

### Code Organization
- Controllers should be thin and delegate business logic to Services
- Use Models for database interactions
- Implement Observers for model events
- Maintain Folder Structure that aligns with routing functionality

## 📄 License

This project is licensed under the [MIT License](LICENSE).
