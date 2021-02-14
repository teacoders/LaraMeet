# Laravel Video Chat
Laravel Video Chat using Laravel Websocket and WebRTC

## Installation

Clone the repo
```php
git clone https://github.com/teacoders/laravel-video-chat.git
```

Go to project directory
```php
cd laravel-video-chat
```
Install all Composer dependencies
```php
composer install
```

copy .env.example to .env
```php
cp .env.example .env
```

Change into following enviroment variable
```php
DB_CONNECTION=your_db_connection
DB_HOST=db_host_name
DB_PORT=db_port
DB_DATABASE=db_name
DB_USERNAME=db_user_name
DB_PASSWORD=db_password

BROADCAST_DRIVER=pusher

// If you want, you can write anything here but you cannot leave it empty

PUSHER_APP_ID=anyId 
PUSHER_APP_KEY=anyKey
PUSHER_APP_SECRET=anySecret
PUSHER_APP_CLUSTER=mt1
```

And 
```php 
php artisan migrate
php artisan storage:link
php artisan serve

change APP_URL in .env
```

## Next Version

- Group Video Call

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
