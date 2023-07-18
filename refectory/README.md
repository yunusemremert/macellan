<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Refectory Service

## Installation

To get started, follow these steps:

#### Please run the following command line in terminal

1. Install the required packages using composer by running the following command:

```bash
composer install
```

2. Create a ".env" file and fill in the necessary database configurations.

```bash
ACCEPTED_SECRETS=

PAYMENT_SERVICE_BASE_URL=http://127.0.0.1:8002/api
PAYMENT_SERVICE_SECRET_KEY=
```

3. Migrate your database by running the following command:
```bash
php artisan migrate
```

4. start server
```bash
php artisan serve --port=8001
```
