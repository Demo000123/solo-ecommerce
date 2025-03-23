# Solo E-commerce

A modern e-commerce website built with PHP following the MVC pattern.

## Features

- Product browsing with categories and search
- Product detail pages
- Shopping cart functionality
- Responsive design for mobile and desktop
- Clean, modern UI

## Technologies Used

- PHP 8.1+
- MySQL
- HTML/CSS
- JavaScript
- MVC Architecture (manual implementation)
- PDO for database operations

## Project Structure

```
├── src/
│   ├── controllers/    # Controllers for handling requests
│   ├── models/         # Data models
│   ├── services/       # Business logic
│   ├── views/          # Templates for UI
│   ├── Core/           # Core framework files
│   └── config/         # Configuration files
├── public/             # Publicly accessible files
│   ├── css/            # CSS files
│   ├── js/             # JavaScript files
│   └── images/         # Image assets
├── database/           # Database scripts
└── index.php           # Application entry point
```

## Setup Instructions

### Prerequisites

- PHP 8.1+
- MySQL 5.7+
- Apache/Nginx web server

### Installation

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/solo-ecommerce.git
   cd solo-ecommerce
   ```

2. Set up the database
   ```bash
   mysql -u root -p < database/ecommerce.sql
   ```

3. Configure your web server to point to the project directory
   - For Apache, ensure the DocumentRoot is set to the project root
   - For Nginx, configure the server block to point to the project root

4. Update database configuration (if needed)
   - Edit `src/config/config.php` with your database credentials

5. Make sure the directories have appropriate permissions
   ```bash
   chmod -R 755 .
   ```

6. Access the website in your browser
   ```
   http://localhost/solo-ecommerce/
   ```

## Development

### Code Standards

- PSR-12 coding standards
- Strict typing
- Meaningful variable and function names
- Separation of concerns (MVC)

### Adding New Features

To add new features:

1. Create relevant Model(s) in `src/models/`
2. Implement business logic in `src/services/`
3. Create Controller(s) in `src/controllers/`
4. Add view templates in `src/views/`
5. Update the Router in `src/Core/Router.php`

## License

This project is licensed under the MIT License.

## Credits

Created by [Your Name] 