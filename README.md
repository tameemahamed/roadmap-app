# Table of Contents

* [Roadmap App](#roadmap-app)

  * [Technology Stack](#technology-stack)

    * [Backend](#backend)
    * [Frontend](#frontend)
  * [Features](#features)
    * [User Authentication](#user-authentication)
    * [Roadmap Display](#roadmap-display)
    * [Upvoting](#upvoting)
    * [Commenting](#commenting)
    * [Nested Comment Replies](#nested-comment-replies)
  * [Run The Project](#run-the-project)

    * [Clone Repository](#clone-repository)
    * [Setup `.env` File](#setup-env-file)
    * [Run Project](#run-project)
  * [Project Setup](#project-setup)

    * [Create Laravel Project](#create-laravel-project)
    * [Fix Little Issues](#fix-little-issues)
  * [Write Database Migrations](#write-database-migrations)

    * [Database Schema](#database-schema)
    * [Create migrations](#create-migrations)
    * [Run the Migrations](#run-the-migrations)
  * [Make Seeders](#make-seeders)

    * [Create Seeders](#create-seeders)
    * [Create Models for each Table](#create-models-for-each-table)
    * [Write Codes](#write-codes)
    * [Run Seeders](#run-seeders)
  * [User Authentication](#user-authentication)

    * [Login Using Username and Password](#login-using-username-and-password)
    * [Add username in registration form](#add-username-in-registration-form)
    * [Implement Email Verification](#implement-email-verification)
  * [Optimize Lines of Codes](#optimize-lines-of-codes)
  * [Make API](#make-api)

    * [Make API Routes](#make-api-routes)
    * [Use API in VueJs](#use-api-in-vuejs)
  * [Make APIs for Roadmap Pages](#make-apis-for-roadmap-pages)
  * [Make Frontend](#make-frontend)
  * [Make Backend](#make-backend)

    * [Process Api calls](#process-api-calls)
    * [Process Post Requests](#process-post-requests)
  * [Problems in the Project](#problems-in-the-project)

    * [Refreshes Page](#refreshes-page)
    * [Only one controller to handle all of the requests](#only-one-controller-to-handle-all-of-the-requests)

# Roadmap App
## Technology Stack
### Backend
For the backend I will be using Laravel 12. 
### Frontend
For the frontend I used `VueJs` along with `InertiaJs`.

## Features
### User Authentication
- User should Signup using name, email, username, password.
- After signingup a verification email is sent to the email of the user.
- User should also need to verify the email address to see and interect with the content of the website(e.g. Roadmaps, upvote, comment etc.)
- Users can Login using email/username and password.
### Roadmap Display
- This app has a clean intutive UI to display roadmap items.
- Users can filter roadmap items based on statuses(Planned, In progress, Completed).
- Users can also filter roadmaps based on popularity(upvotes).
- The roadmap card was inspired from the [Microsoft365 Roadmap](https://www.microsoft.com/en-us/microsoft-365/roadmap) card. 
### Upvoting
- Users can upvote roadmap items. Also can remove his own upvote.
- User can not upvote a roadmap item if it is already upvotted by him.
### Commenting
- Users are able to leave comments on any roadmap item to provide feeback, ask questions or discuss further.
- Users are able able to edit or delete their own comments.
- Deleted comments are removed from UI and databse.
- Shows `Edited` if a comment is edited.
### Nested Comment Replies
- Users are able to reply to other comments in a nested format. This creates a conversation chain under each comments.
- Limited the nesing dept to 3 levels to maintain readability and prevent clutter.
- Each nested comment is visually indented to indiciate its hierarchy in the thread.

## Run The Project
### Clone Repository
```bash
git clone https://github.com/tameemahamed/roadmap-app.git
cd roadmap-app
npm install
composer install
```
### Setup `.env` File
Copy that `.env.example` file and paste it it into `.env`. Make sure to edit the followings as you want to:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=roadmap_app
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=null # set yours one
MAIL_PASSWORD=null # set yours one
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
### Run Project
```bash
npm run build
php artisan key:generate
php artisan migrate
php artisan db:seed # This is to seed random data to the database
php artisan serve
```
After this:
- Navigate to the link shown in the terminal.
- Navigate to Login page (the password is `password` for every users) 

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
### Database Schema
As there was not any exact information about the database schema for `roadmap` in the task requirements`(Roadmap items will be predefined and come from the database)`, I have considered it as like as `microsoft 365 roadmaps` layout. And according to this I will be working with the following schema: <br>
![Database Schema Image](https://i.ibb.co/s97p9nMR/Roadmap-App-2.png)
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
You can find the migrations code in this repository in `database/migrations` directory.
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

## User Authentication
Laravel Breeze provides user authentication by default. But it does not comes along with `Login using username and password`. Also it does not provides `Email verification` by default. So we will implement these first.

### Login Using Username and Password
In `app/Models/User.php`
```php
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];
```
In `app/Http/Requests/Auth/LoginRequest.php`
```php
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('login', 'password');

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

            if (
                !Auth::attempt(
                    [
                        $field => $credentials['login'],
                        'password' => $credentials['password']
                    ],
                    $this->boolean('remember')
                )
            ) {
                RateLimiter::hit($this->throttleKey());
    
                throw ValidationException::withMessages([
                    'login' => trans('auth.failed'), 
                ]);
            }    

        RateLimiter::clear($this->throttleKey());
    }
```
In `resources/js/Pages/Auth/Login.vue`
```vue
<script setup>
const form = useForm({
    login: '',
    password: '',
    remember: false,
});
</script>
            <div>
                <InputLabel for="login" value="Email or Username" />

                <TextInput
                    id="login"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.login"
                    required
                    autofocus
                    autocomplete="login"
                />

                <InputError class="mt-2" :message="form.errors.login" />
            </div>
```

### Add username in registration form
In `resources/js/Pages/Auth/Register.vue`
```vue
<script setup>
const form = useForm({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: '',
});
</script>

            <div class="mt-4">
                <InputLabel for="username" value="Username" />

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>
```
In `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:'.User::class,
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
```



### Implement Email Verification

Inside the `.env` file edit these
```ini
APP_NAME="Roadmap App"

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=yourmail@gmail.com
MAIL_PASSWORD=yourpass
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="yourmail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

In `app/Models/User.php` add these
```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{

}
```
## Optimize Lines of Codes
In `resources/js/app.js`
```js
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Link', Link)
            .component('Head', Head)
            .component('AuthenticatedLayout', AuthenticatedLayout)
            .mount(el);
    },
```
Now we can remove these followings from `resources/js/*.vue`
```vue
<script setup>
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
```

## Make API

### Run Command to Create API
```bash
php artisan install:api
```

Add these in `app/Models/User.php`
```php
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /*
        rest of the codes
    */

}
```

Now we need to create and put authorization tokens in `session()` method. To implement these go through `app/Http/Controllers/Auth/AuthenticatedSessionController.php` and add these
```php
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $token = $user->createToken($user->id)->plainTextToken;

        $request->session()->put('auth_token', $token);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('auth_token');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
```
For these an `api key` is generated everytime a user logs in and gets destroyed when the user logs out.<br>


Also in `app/Http/Controllers/Auth/RegisteredUserController.php` add these.
```php
        $token = $user->createToken($user->id)->plainTextToken;

        $request->session()->put('auth_token', $token);

        event(new Registered($user));
```
Upon registering an user is redirected to the `/dashboard` route. So we need to generate an `api key` for a newly registered user.

Now we need to add the token to `Inertia.js` shared props in `HandleInertiaRequests.php` middleware
```php
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'auth_token'  => $request->session()->get('auth_token'),
        ];
```

### Make API Routes
In `routes/api.php` we can make api routes. Also `auth:sanctum` middleware is used for authentications. For which we actually need that `auth_token`.

```php
// we will do this part later. just I want to say that everything just works like a charm. :)
```

### Use API in VueJs
```vue
<script setup>
import axios from 'axios';
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

onMounted(() => {
  // Create headers with auth token
    const headers = {};
    if (page.props.auth_token) {
        headers['Authorization'] = `Bearer ${page.props.auth_token}`;
    }

    axios.get('/api/your_api_route', { headers })
        .then(response => {
        
        })
        .catch(error => {
        
        });
});
</script>
```

## Make APIs for Roadmap Pages
### Make Controller
```bash
php artisan make:controller RoadmapController
```
Now in `app/Http/Controllers/RoadmapController.php` write the codes responses

### Make APIs
In `routes/api.php` make the api route to get roadmaps
```php
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/roadmaps/{status_ids}/{filter_upvotes}', [RoadmapController::class, 'filteredRoadmaps']);
});
```
Here `auth:sanctum` middleware is added so that only authenticated users can call that api.

## Make Frontend
For frontend we will use VueJs. 
### Make Frontend Layouts
Layouts are very useful for code reuseability. For this reason we will be making two layouts. 
- For RoadmapCard in `RoadmapLayout.vue`
- For RoadmapComments in `RoadmapCommentLayout.vue`

### Make Frontend Pages (`/roadmap`)
At first in `web.php` we have to register the route `/roadmap`.
```php
Route::middleware(['auth', 'verified'])->group(function() {
    // rest of the codes
    Route::inertia('/roadmap', 'Roadmaps')->name('roadmap');    
});
```
Now it will render the page `resources/js/Pages/Roadmaps.vue`. You will find the code for this in that repository.
It will call the `RoadmapLayout.vue` for each roadmaps.

### Make Frontend Pages (`/roadmap/{roadmap_id}`)
Make the route in `web.php`
```php
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/roadmap/{roadmap_id}', function($roadmap_id) {
        return Inertia::render('Roadmap', [
            'roadmap_id' => $roadmap_id
        ]);
    });
});
```
As you can see, It will then render the `Roadmap.vue` page. Also It passes a prop `roadmap_id`. To get or use the prop we will do the following:
```vue
<script>
const props = defineProps({
    roadmap_id: {
        type: [Number, String],
        required:true}
})
</script>
```

It has two child layouts `RoadmapLayout.vue` and `CommentLayout.vue`. Please see the source codes in `resources/js/Pages` and in `resources/js/Layouts` directory.

## Make Backend
To process API calls and Post requests from the frontend, a relevant backend is necessary.

### Process Api calls
In `routes/api.php` make routes to call the API
```php
use App\Http\Controllers\RoadmapController;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/roadmaps/{status_ids}/{filter_upvotes}', [RoadmapController::class, 'filteredRoadmaps']);
    Route::get('/roadmap/{roadmap_id}', [RoadmapController::class, 'selectedRoadmap']);
});
```
Now in `app/Http/Controllers/RoadmapController.php`, we have done the work with `filteredRoadmaps` and `selectedRoadmap` methods.

### Process Post Requests
In `routes/web.php` make necessary post routes
```php
Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('/liked', [RoadmapController::class, 'liked'])->name('liked.roadmap');
    Route::post('/addComment', [RoadmapController::class, 'addComment']);
    Route::post('/addReply', [RoadmapController::class, 'addReply']);
    Route::post('/editComment', [RoadmapController::class, 'editComment']);
    Route::post('/deleteComment', [RoadmapController::class, 'deleteComment']);
    Route::post('/editReply', [RoadmapController::class, 'editReply']);
    Route::post('/deleteReply', [RoadmapController::class, 'deleteReply']);
});
```
Now in `app/Http/Controllers/RoadmapController.php`
```php
    public function editComment(Request $req){
        $comment_id = $req->comment_id;
        $content = $req->content;
        $user_id = Auth::id();
        
        RoadmapComment::where('id', $comment_id)
            ->where('user_id', $user_id)
            ->update([
                'content' => $content,
                'edited' => 1,
                'updated_at' => now()
            ]);
    }

    public function deleteComment(Request $req) {
        $comment_id = $req->comment_id;
        $user_id = Auth::id();
        RoadmapComment::where('id', $comment_id)
            ->where('user_id', $user_id)
            ->delete();
    }
```
As you can see that we also used `Auth::id()` to verify the user is authenticated or not. Also it checks if the edit request that user is making is his comment or not. Which ensures the security of the webapp and the users. 
## Problems in the Project
The Project that we have just made has some issues.
### Refreshes Page
Every time a user post, edit or delete a comment or a reply, the webpage reloads. For this reason the user gets to the top of the page again. 
For this reason the user will have to scroll back to the place where he/she did any one of the operations mentioned. 
Also for this, hits to the database increases. 
### Only one controller to handle all of the requests
As you noticed, there are only one controller for most of the api calls and post requests. This is a best practice to use seperate controllers for seperate tasks. 
This could be solved by creating another controller in `app/Http/Controllers/CommentsController.php` and use comment and reply methods from `RoadmapController.php` to `CommentsController.php`. Which does code structure more accurate.

