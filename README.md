# TshuksERP

A professional, self-hosted vanilla PHP and MySQL business management system designed for cross-border trading operations.

## Features

- **Guided Web Installer**: Easy setup with automatic database initialization
- **Authentication & RBAC**: Secure staff authentication with role-based access control
- **Inventory Management**: Product records and automated stock movement logging
- **Sales Pipeline**: Quotations, invoices, and order management
- **Financial Tracking**: Customer payments, expenses, and settlements
- **Reporting & Analytics**: Chart.js dashboards and CSV exports
- **Document Verification**: Public QR-linked verification page
- **Security**: XSS protection, SQL injection prevention, session guards, activity audit logging

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- 512MB RAM minimum

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure database credentials
3. Navigate to `public/install.php` in your browser
4. Follow the guided installer
5. Login with the created admin credentials

## Directory Structure

```
tshukserp/
├── public/              # Web-accessible files
├── src/                 # Core application code
├── tests/               # Unit and integration tests
└── docs/                # Documentation
```

## Documentation

- [Database Schema](docs/DATABASE.md)
- [API Reference](docs/API.md)
- [Setup Guide](docs/SETUP.md)

## License

All rights reserved.
