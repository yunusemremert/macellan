<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# ApiGateway

## Installation App

To get started, follow these steps:

#### Please open the terminal of the project and run it (Step 1, Step 2)

Step 1:

```bash
composer install
```

Step 2:

```bash
copy .env.example .env
```

Step 3:

Please add the following lines to your file for **.env** file configuration.

```
TAG_QR_SALT_KEY=

REFECTORY_SERVICE_BASE_URL=http://127.0.0.1:8001/api
REFECTORY_SERVICE_SECRET_KEY=

PAYMENT_SERVICE_BASE_URL=http://127.0.0.1:8002/api
PAYMENT_SERVICE_SECRET_KEY=
```

Step 4:

Please run the following command line on terminal screen to boot the application.
```bash
php artisan serve --port=8000
```
