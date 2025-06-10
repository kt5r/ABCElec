# CABC Electronics Store

A full-stack e-commerce web application built with Laravel, featuring multi-language support, role-based access control, and comprehensive product management system.

## Features

### Customer Features
- User registration and authentication
- Browse products by categories (Kitchen, Bathroom, Living, Other)
- Shopping cart functionality
- Secure checkout process with payment gateway integration
- Order history and tracking
- Multi-language support (English & Sinhala)
- Responsive design with Tailwind CSS

### Admin Features
- Full CRUD operations for products and categories
- User management with role-based permissions
- Order management and status updates
- Sales reporting and analytics
- Dashboard with key metrics

### Role-Based Access Control
- **Customer**: Browse, purchase, view order history
- **Admin**: Full system access
- **Operation Manager**: Same permissions as Admin
- **Sales Manager**: View daily sales reports only

## Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Authorization**: Custom role-based middleware
- **Image Processing**: Intervention Image
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite Excel

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7 or higher

### Step 1: Clone Repository
```bash
git clone https://github.com/your-username/cabc-electronics.git
cd cabc-electronics
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` file with your database credentials:
```env
DB_DATABASE=cabc_electronics
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Storage & Assets
```bash
php artisan storage:link
npm run build
```

### Step 6: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to view the application.

## Quick Setup Script
For development environment:
```bash
composer run setup-dev
```

For production deployment:
```bash
composer run fresh-install
```

## Default Users

After running seeders, you can login with these accounts:

### Admin User
- **Email**: admin@cabc.lk
- **Password**: password

### Operation Manager
- **Email**: operations@cabc.lk  
- **Password**: password

### Sales Manager
- **Email**: sales@cabc.lk
- **Password**: password

### Customer
- **Email**: customer@cabc.lk
- **Password**: password

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── CartController.php
│   │   ├── CategoryController.php
│   │   ├── CheckoutController.php
│   │   ├── DashboardController.php
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── ProfileController.php
│   │   └── LanguageController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   ├── SalesManagerMiddleware.php
│   │   ├── CheckUserStatusMiddleware.php
│   │   ├── SetLocale.php
│   │   ├── CartMiddleware.php
│   │   └── LogUserActivityMiddleware.php
│   ├── Requests/
│   │   ├── ProductRequest.php
│   │   ├── CheckoutRequest.php
│   │   ├── CategoryRequest.php
│   │   ├── UserRequest.php
│   │   └── ProfileUpdateRequest.php
│   └── Policies/
│       ├── ProductPolicy.php
│       ├── OrderPolicy.php
│       ├── CategoryPolicy.php
│       ├── UserPolicy.php
│       └── DashboardPolicy.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── CartItem.php
│   ├── Role.php
│   └── Permission.php
├── Services/
│   ├── PaymentService.php
│   ├── CartService.php
│   ├── NotificationService.php
│   └── MailService.php
└── Providers/
    ├── AuthService.php
    ├── NotificationProvider.php
    ├── ImageProvider.php
    ├── SearchProvider.php
    └── ReportProvider.php
```

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration  
- `POST /api/logout` - User logout

### Products
- `GET /api/products` - Get all products
- `GET /api/products/{id}` - Get product details
- `GET /api/categories/{id}/products` - Get products by category

### Cart
- `POST /api/cart/add` - Add item to cart
- `GET /api/cart` - Get cart items
- `PUT /api/cart/{id}` - Update cart item
- `DELETE /api/cart/{id}` - Remove cart item

### Orders
- `POST /api/orders` - Create order
- `GET /api/orders` - Get user orders
- `GET /api/orders/{id}` - Get order details

## Security Features

- CSRF Protection
- XSS Prevention
- SQL Injection Protection
- Password Hashing (Bcrypt)
- Rate Limiting
- Input Validation & Sanitization
- Role-based Access Control
- Secure Session Management

## Multi-Language Support

The application supports:
- **English** (Default)
- **Sinhala** (සිංහල)

Language files are located in:
- `lang/en/messages.php`
- `lang/si/messages.php`

## Payment Integration

The application includes payment gateway integration setup for:
- PayHere (Sri Lankan payment gateway)
- Configurable for other payment providers

## Testing

Run tests with:
```bash
php artisan test
```

## Deployment

### Production Checklist
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up proper mail configuration
5. Configure payment gateway credentials
6. Run optimization commands:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, email support@cabc.lk or create an issue on GitHub.

## Changelog

### Version 1.0.0
- Initial release
- Multi-language support
- Role-based access control
- Complete e-commerce functionality
- Payment gateway integration
- Responsive design

---

**CABC Private LTD** - Your Electronics Partner