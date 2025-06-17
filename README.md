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

## Make Seeders
As the database is empty right now. To add some dummy data we need to make the seeders.

### Create Seeders
```bash
php artisan make:seeder UsersTableSeeder
php artisan make:seeder StatusesTableSeeder
php artisan make:seeder RoadmapsTableSeeder
php artisan make:seeder RaodmapUpvotesTableSeeder
php artisan make:seeder RoadmapCommentsTableSeeder
php artisan make:seeder RoadmapCommentRepliesTableSeeder
php artisan make:seeder TagsTableSeeder
```
### Create Models for each Table
To create tables without using `DB` or `Schema`, we can simply use models.
```bash
# User model is already in there so we have no need to create this
php artisan make:model Status
php artisan make:model Roadmap
php artisan make:model RoadmapUpvote
php artisan make:model RoadmapComment
php artisan make:model RoadmapCommentReply
php artisan make:model Tag
```
In `app/Models/RoadmapComment.php` add that line
```php
class RoadmapComment extends Model
{
    protected $table = 'roadmap_comments';
}
```
It is a best practice to include the table names in models.
### Write Codes
You can access the codes for the model from this repository in `database/seeders/`
### Run Seeders
in `database/seeders/DatabaseSeeder.php` 
```php
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            StatusesTableSeeder::class,
            RoadmapsTableSeeder::class,
            RaodmapUpvotesTableSeeder::class,
            RoadmapCommentsTableSeeder::class,
            RoadmapCommentRepliesTableSeeder::class,
            TagsTableSeeder::class
        ]);
    }
}
```

now in terminal run
```bash
php artisan db:seed
```


