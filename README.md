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


