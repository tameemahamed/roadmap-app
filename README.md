# Roadmap App

## Project Setup

### Create Laravel Project
```bash
laravel new roadmap_app
```
choose `Laravel Breeze`
choose `Vue with Inertia`
choose `None`
choose `PHPUnit`
choose `mysql`
```bash
cd roadmap_app
npm install && npm run build
```

### Fix Little Issues
In `bootstrap/app.php` add these lines
```php
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
```

## Write Database Migrations
### Create migrations
```bash
php artisan make:migration statuses
sleep 2
php artisan make:migration roadmaps
sleep 2
php artisan make:migration roadmap_upvotes
sleep 2
php artisan make:migration roadmap_comments
sleep 2
php artisan make:migration roadmap_comment_replies
sleep 2
php artisan make:migration items
sleep 2
php artisan make:migration roadmap_items
```

### Run the Migrations
Obviously you have to write code for them. Or just copy them from this repo. After writing
```bash
php artisan migrate:fresh
```



