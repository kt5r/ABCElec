# ABCElec - Electronics Retail Management System

ABCElec is a modern web-based electronics retail management system built with Laravel and Tailwind CSS. It provides a comprehensive solution for managing an electronics retail business, including product management, order processing, customer management, and sales analytics.

## Features

### Customer Features
- User registration and authentication
- Multi-language support (English and Sinhala)
- Product browsing by categories
- Product search and filtering
- Shopping cart functionality
- Secure checkout process
- Order history and tracking
- User profile management

### Admin Features
- Dashboard with key metrics and analytics
- Product management (CRUD operations)
- Category management
- Order management and processing
- Customer management
- Sales reports and analytics
- User role management
- Inventory tracking

## Technical Stack

- **Backend Framework:** Laravel 10.x
- **Frontend:** 
  - Tailwind CSS
  - Alpine.js
  - Blade Templates
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Asset Compilation:** Vite
- **Localization:** Laravel's built-in localization

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 8.0
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/ABCElec.git
cd ABCElec
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abcelec
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Seed the database with initial data:
```bash
php artisan db:seed
```

9. Compile assets:
```bash
npm run dev
```

10. Start the development server:
```bash
php artisan serve
```

## Usage

### Development

- Run Vite development server:
```bash
npm run dev
```

- Run Laravel development server:
```bash
php artisan serve
```

### Production

1. Build assets for production:
```bash
npm run build
```

2. Optimize Laravel:
```bash
php artisan optimize
```

3. Configure your web server (Apache/Nginx) to point to the `public` directory

## Directory Structure

```
ABCElec/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   └── web.php
└── tests/
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

For support, email support@abcelec.com or create an issue in the repository.

## Acknowledgments

- Laravel Team
- Tailwind CSS Team
- All contributors who have helped shape this project