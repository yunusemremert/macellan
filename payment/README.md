<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Payment Service

<br>

To get started, follow these steps:

#### Please open the terminal of the project and run it (Step: 1, 2, 4)

1. Install the required packages using composer by running the following command:

```bash
composer install
```

2. Please create the **.env** file with the following command line.

```bash
copy .env.example .env
```

3. Please fill in the **.env** file with the required configurations below.

```
ACCEPTED_SECRETS=

TAG_QR_SALT_KEY=
TAG_QR_CALLBACK_SUCCESS_URL=
TAG_QR_CALLBACK_FAILED_URL=
```

4. Please start server

```bash
php artisan serve --port=8002
```
