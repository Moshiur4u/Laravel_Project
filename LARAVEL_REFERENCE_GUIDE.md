# Laravel সম্পূর্ণ রেফারেন্স গাইড - Bangla Tutorial

---

## 📚 টেবিল অফ কন্টেন্টস
1. [Laravel Migration](#1-laravel-migration)
2. [Logging ও Session](#2-logging--session)
3. [Query Builder](#3-query-builder)
4. [Eloquent Relationships](#4-eloquent-relationships)
5. [Flash Messages](#5-flash-messages)
6. [Middleware](#6-middleware)
7. [Validation](#7-validation)
8. [Seeder & Faker](#8-seeder--faker)
9. [Mass Assignment Security](#9-mass-assignment-security)
10. [Alert Notifications](#10-alert-notifications)
11. [Localization (Multi-Language)](#11-localization)
12. [Socialite - Google Login](#12-socialite--google-login)
13. [Custom Auth & Role Management](#13-custom-auth--role-management)
14. [Blade Templates](#14-blade-templates)
15. [Push Notifications](#15-push-notifications)

---

# 1. LARAVEL MIGRATION

## প্রশ্ন: Laravel Migration কি?
**উত্তর:** Migration হল ডাটাবেসের সংস্করণ নিয়ন্ত্রণ সিস্টেম। এটি টেবিল তৈরি, পরিবর্তন এবং ডেটা স্ট্রাকচার ম্যানেজ করতে ব্যবহার করা হয়।

### Migration তৈরি করুন
```bash
# একটি নতুন migration তৈরি করুন
php artisan make:migration create_users_table

# Migration with model এবং controller তৈরি করুন
php artisan make:model User -m  # -m মানে make migration
```

### Migration ফাইলের স্ট্রাকচার
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    // Up Method: যখন migrate করি
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Primary Key - Auto Increment
            $table->string('name');
            $table->string('email')->unique();  // Unique কনস্ট্রেইন্ট
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();  // created_at এবং updated_at
        });
    }
    
    // Down Method: যখন rollback করি
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

## Primary Key নিয়ে বিস্তারিত

```php
Schema::create('users', function (Blueprint $table) {
    // Default Primary Key (Integer)
    $table->id();  // Equivalent to: $table->bigIncrements('id');
    
    // Custom Primary Key
    $table->uuid('id')->primary();  // UUID as primary key
    
    // Composite Primary Key
    $table->primary(['category_id', 'product_id']);
});
```

## Foreign Key - সম্পর্ক তৈরি করুন

```php
// Example: Posts table যেখানে user_id foreign key
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    
    // Foreign Key সহ
    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade')  // User delete হলে Posts ও delete হবে
        ->onUpdate('cascade');  // User update হলে Posts ও update হবে
    
    // Shorthand Method
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    
    $table->timestamps();
});
```

## Schema Blueprint - সব Column Types

```php
Schema::create('products', function (Blueprint $table) {
    // Numeric
    $table->integer('quantity');
    $table->bigInteger('large_number');
    $table->decimal('price', 8, 2);  // 999999.99 পর্যন্ত
    $table->float('rating', 3, 2);   // 9.99 পর্যন্ত
    
    // String
    $table->string('name', 100);       // VARCHAR(100)
    $table->text('description');       // TEXT
    $table->longText('content');       // LONGTEXT
    $table->char('code', 6);           // CHAR(6)
    $table->enum('status', ['active', 'inactive', 'pending']);
    
    // Date & Time
    $table->date('birthdate');
    $table->dateTime('published_at');
    $table->timestamp('created_at')->useCurrent();
    $table->time('start_time');
    $table->year('founded_year');
    
    // Boolean
    $table->boolean('is_published')->default(false);
    
    // JSON
    $table->json('settings');
    
    // File
    $table->binary('image_data');
    
    // Others
    $table->uuid('reference_id')->unique();
    $table->ipAddress('ip_address');
    $table->macAddress('mac_address');
    $table->point('location');  // GIS data
});
```

## Nullable এবং Default Value

```php
Schema::create('articles', function (Blueprint $table) {
    $table->string('title');
    $table->text('content')->nullable();  // NULL হতে পারে
    $table->string('status')->default('draft');  // ডিফল্ট মান
    $table->timestamp('published_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();  // created_at এবং updated_at তৈরি করে
});
```

## Migration চালান এবং Rollback করুন

```bash
# সব migrations চালান
php artisan migrate

# সর্বশেষ migration rollback করুন
php artisan migrate:rollback

# সব migrations rollback করুন
php artisan migrate:reset

# Rollback এবং পুনরায় চালান
php artisan migrate:refresh

# Refresh এবং seed data যোগ করুন
php artisan migrate:refresh --seed

# নির্দিষ্ট migration চালান
php artisan migrate --path=/database/migrations/2024_01_01_create_users_table.php
```

## Migration Modification

```php
// Migration: modify_users_table
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Column পরিবর্তন করুন
        $table->string('name', 100)->change();  // name column size পরিবর্তন
        
        // Column যোগ করুন
        $table->string('phone')->nullable()->after('email');
        
        // Column drop করুন
        $table->dropColumn('temp_field');
        
        // Index যোগ করুন
        $table->index('email');
        $table->unique('phone');
        
        // Timestamp যোগ করুন
        $table->softDeletes();  // deleted_at কলাম যোগ করে
    });
}
```

---

# 2. LOGGING & SESSION

## Logging - প্রয়োজনীয় ইনফরমেশন রেকর্ড করুন

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store()
    {
        try {
            // বিভিন্ন Log Levels
            Log::debug('Debug message');           // Debugging information
            Log::info('User created');              // Informational messages
            Log::notice('User logged in');
            Log::warning('Low stock warning');      // Warning messages
            Log::error('Database connection failed'); // Error messages
            Log::critical('Critical error');        // Critical messages
            Log::alert('Server down');              // Alert messages
            Log::emergency('System failure');       // Emergency - সর্বোচ্চ
            
            // Array সহ log করুন
            Log::info('User created', [
                'user_id' => 123,
                'email' => 'user@example.com',
                'timestamp' => now()
            ]);
            
            // Channel-specific logging
            Log::channel('slack')->error('Payment failed');
            Log::channel('database')->info('Backup completed');
            
        } catch (\Exception $e) {
            Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
```

### Log File Location
```
storage/logs/laravel.log
storage/logs/laravel-2024-05-21.log  // Daily log
```

## Session - ইউজার ডেটা সংরক্ষণ করুন

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login($credentials)
    {
        // Session এ ডেটা সেট করুন
        Session::put('user_id', auth()->id());
        Session::put('user', [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email
        ]);
        
        // বা shorthand ব্যবহার করুন
        session(['user_id' => auth()->id()]);
        session(['user_role' => 'admin']);
        
        // একাধিক ডেটা একবারে
        session([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'is_verified' => true
        ]);
    }
    
    public function getSessionData()
    {
        // Session থেকে ডেটা পান
        $userId = Session::get('user_id');
        $user = session('user');
        
        // Default value সহ
        $role = Session::get('user_role', 'guest');
        
        // সব Session ডেটা
        $all = Session::all();
        
        // Check করুন session key exist করে কিনা
        if (Session::has('user_id')) {
            echo 'User ID exists in session';
        }
    }
    
    public function logout()
    {
        // Session থেকে ডেটা মুছুন
        Session::forget('user_id');
        Session::forget('user');
        
        // বা একাধিক keys
        Session::forget(['user_id', 'user_role']);
        
        // সব session ডেটা মুছুন
        Session::flush();
        
        // সব ডেটা মুছুন এবং সাথে regenerate করুন (security এর জন্য)
        auth()->logout();
        Session::flush();
        session()->regenerate();
    }
}
```

### Blade এ Session ডেটা ব্যবহার করুন

```blade
<!-- সরাসরি session() helper ব্যবহার করুন -->
<p>User: {{ session('user_name') }}</p>

<!-- if দিয়ে চেক করুন -->
@if(session('user_role') === 'admin')
    <p>Admin Panel</p>
@endif

<!-- Flash message (একবার দেখা যায়) -->
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
```

---

# 3. QUERY BUILDER

## প্রশ্ন: Query Builder কি?
**উত্তর:** Query Builder হল Laravel এর একটি সরঞ্জাম যা সরাসরি SQL না লিখে ডাটাবেসে query করতে সাহায্য করে।

## Basic Query Methods

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class QueryController extends Controller
{
    public function basicQueries()
    {
        // SELECT * FROM users
        $users = DB::table('users')->get();
        
        // সমস্ত columns
        $users = User::all();
        
        // নির্দিষ্ট columns select করুন
        $users = User::select('id', 'name', 'email')->get();
        
        // WHERE clause
        $user = User::where('email', 'john@example.com')->first();
        
        // একাধিক WHERE
        $users = User::where('status', 'active')
                    ->where('verified', true)
                    ->get();
        
        // OR condition
        $users = User::where('status', 'active')
                    ->orWhere('status', 'pending')
                    ->get();
        
        // IN clause
        $users = User::whereIn('status', ['active', 'pending'])->get();
        
        // NOT IN
        $users = User::whereNotIn('status', ['deleted', 'banned'])->get();
        
        // BETWEEN
        $users = User::whereBetween('age', [18, 65])->get();
        
        // NULL check
        $users = User::whereNull('deleted_at')->get();
        $users = User::whereNotNull('verified_at')->get();
        
        // LIKE search
        $users = User::where('name', 'like', '%John%')->get();
        
        // ORDER BY
        $users = User::orderBy('created_at', 'desc')->get();
        $users = User::latest('created_at')->get();  // desc
        $users = User::oldest('created_at')->get();  // asc
        
        // LIMIT এবং OFFSET
        $users = User::limit(10)->offset(5)->get();
        $users = User::skip(5)->take(10)->get();
        
        // PAGINATION
        $users = User::paginate(15);  // প্রতি পৃষ্ঠায় 15 রেকর্ড
    }
    
    public function aggregateFunctions()
    {
        // COUNT
        $count = User::count();
        $activeCount = User::where('status', 'active')->count();
        
        // SUM
        $totalSales = DB::table('orders')->sum('amount');
        
        // AVG
        $avgPrice = DB::table('products')->avg('price');
        
        // MIN এবং MAX
        $minPrice = DB::table('products')->min('price');
        $maxPrice = DB::table('products')->max('price');
        
        // GROUP BY
        $groupedUsers = User::groupBy('status')->get();
    }
    
    public function conditionalQueries()
    {
        $query = User::query();
        
        // Conditional WHERE
        if ($status = request('status')) {
            $query->where('status', $status);
        }
        
        if ($email = request('email')) {
            $query->where('email', 'like', "%$email%");
        }
        
        $users = $query->get();
        
        // when() method ব্যবহার করুন
        $users = User::when($status = request('status'), function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();
    }
}
```

## Collection সহ Foreach, Forelse, If

```blade
<!-- foreach দিয়ে Collection এবং Array দেখান -->
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($user->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- forelse - যদি collection খালি থাকে -->
@forelse($users as $user)
    <div class="user-card">
        <h3>{{ $user->name }}</h3>
        <p>{{ $user->email }}</p>
    </div>
@empty
    <div class="alert alert-info">
        কোন ব্যবহারকারী পাওয়া যায়নি।
    </div>
@endforelse

<!-- PHP array সহ -->
@php
    $statuses = ['active', 'pending', 'inactive'];
    $userData = [
        ['id' => 1, 'name' => 'John', 'status' => 'active'],
        ['id' => 2, 'name' => 'Jane', 'status' => 'pending'],
        ['id' => 3, 'name' => 'Bob', 'status' => 'inactive']
    ];
@endphp

@foreach($userData as $user)
    <div>
        <p>নাম: {{ $user['name'] }}</p>
        <p>স্ট্যাটাস: {{ $user['status'] }}</p>
    </div>
@endforeach

<!-- nested loop -->
@foreach($departments as $dept)
    <h3>{{ $dept->name }}</h3>
    @forelse($dept->employees as $employee)
        <p>- {{ $employee->name }}</p>
    @empty
        <p>কোন কর্মচারী নেই</p>
    @endforelse
@endforeach
```

## Advanced Query Operations

```php
<?php

class AdvancedQueryController extends Controller
{
    public function joinQueries()
    {
        // INNER JOIN
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name')
            ->get();
        
        // LEFT JOIN
        $users = DB::table('users')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();
        
        // Multiple JOIN
        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select('orders.*', 'users.name as user_name', 'products.title as product_name')
            ->get();
    }
    
    public function insertUpdate()
    {
        // INSERT
        DB::table('users')->insert([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'created_at' => now()
        ]);
        
        // একাধিক rows insert করুন
        DB::table('users')->insert([
            ['name' => 'John', 'email' => 'john@example.com'],
            ['name' => 'Jane', 'email' => 'jane@example.com'],
        ]);
        
        // INSERT এবং auto-increment ID ফেরত দিন
        $id = DB::table('users')->insertGetId([
            'name' => 'Bob',
            'email' => 'bob@example.com'
        ]);
        
        // UPDATE
        DB::table('users')
            ->where('id', 1)
            ->update(['status' => 'active']);
        
        // Increment/Decrement
        DB::table('products')->where('id', 1)->increment('stock', 5);
        DB::table('products')->where('id', 1)->decrement('stock', 2);
        
        // DELETE
        DB::table('users')->where('id', 1)->delete();
    }
}
```

---

# 4. ELOQUENT RELATIONSHIPS

## প্রশ্ন: Laravel Relationship কি এবং কেন শিখবেন?
**উত্তর:** Relationship হল ডাটাবেসের টেবিলগুলোর মধ্যে সম্পর্ক সংজ্ঞায়িত করা। এটি ডেটা সংগঠিত রাখতে এবং জটিল queries সহজ করতে সাহায্য করে।

## One-to-Many Relationship

```php
// Model: User.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    // একজন ব্যবহারকারী অনেক posts লিখতে পারে
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

// Model: Post.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    // একটি post শুধুমাত্র একজন user এর নিজস্ব
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### One-to-Many ব্যবহার করুন

```php
// User এর সব posts পান
$user = User::find(1);
$posts = $user->posts;  // সব posts
$posts = $user->posts()->where('published', true)->get();

// Post এর owner User পান
$post = Post::find(1);
$user = $post->user;  // মালিক

// সরাসরি relationship সহ তৈরি করুন
$user = User::find(1);
$user->posts()->create([
    'title' => 'My First Post',
    'content' => 'Content here...'
]);

// Blade এ ব্যবহার করুন
@foreach($user->posts as $post)
    <div>
        <h3>{{ $post->title }}</h3>
        <p>{{ $post->content }}</p>
    </div>
@endforeach
```

## Many-to-Many Relationship

```php
// Migration: create_role_user_table
Schema::create('role_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('role_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    // Composite unique key
    $table->unique(['user_id', 'role_id']);
});

// Model: User.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
        // বা কাস্টম table নাম
        // return $this->belongsToMany(Role::class, 'role_user_table');
    }
}

// Model: Role.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
```

### Many-to-Many ব্যবহার করুন

```php
// User এর সব roles পান
$user = User::find(1);
$roles = $user->roles;

// Role যুক্ত করুন
$user->roles()->attach($roleId);
// বা একাধিক
$user->roles()->attach([1, 2, 3]);

// Extra attributes সহ attach করুন
$user->roles()->attach(1, ['assigned_at' => now()]);

// Role পরিবর্তন করুন (পুরাতন সব মুছে দিয়ে নতুন যুক্ত করুন)
$user->roles()->sync([1, 2, 3]);

// Role সরিয়ে দিন
$user->roles()->detach($roleId);
$user->roles()->detach();  // সব সরিয়ে দিন

// Pivot data পান (Middle table এ extra attributes)
@foreach($user->roles as $role)
    <p>{{ $role->name }} - Assigned at: {{ $role->pivot->assigned_at }}</p>
@endforeach
```

## Has-One Relationship

```php
// একজন User এর একটি profile আছে
class User extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}

class Profile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// ব্যবহার
$user = User::find(1);
$profile = $user->profile;  // এক্ষণ single model, না collection
```

## Relationship Queries

```php
// With eager loading (N+1 problem সমাধান)
$users = User::with('posts')->get();  // সব users সহ তাদের posts

// Multiple relationships
$users = User::with(['posts', 'roles'])->get();

// Nested relationships
$posts = Post::with('user.roles')->get();

// Conditional eager loading
$users = User::with(['posts' => function ($query) {
    $query->where('published', true)->orderBy('created_at', 'desc');
}])->get();

// Counting relationships
$users = User::withCount('posts')->get();
echo $user->posts_count;  // posts এর সংখ্যা

// Has (relationship exists check)
$usersWithPosts = User::has('posts')->get();  // যাদের কমপক্ষে একটি post আছে

// Doesn't have
$usersWithoutPosts = User::doesntHave('posts')->get();

// whereHas - relationship condition সহ
$publishedPostAuthors = User::whereHas('posts', function ($query) {
    $query->where('published', true);
})->get();
```

---

# 5. FLASH MESSAGES

## Flash Messages কি?
Flash messages হল একবার দেখানোর মত বার্তা যা পরবর্তী request এর পর মুছে যায়।

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function store()
    {
        // ডেটা সংরক্ষণ করুন...
        
        // Flash message যোগ করুন
        return redirect()->route('users.index')
            ->with('success', 'ব্যবহারকারী সফলভাবে তৈরি হয়েছে!');
        
        // বা session() ব্যবহার করুন
        session()->flash('success', 'ব্যবহারকারী তৈরি হয়েছে!');
        
        // একাধিক flash messages
        return redirect()->back()->with([
            'success' => 'Operation successful',
            'message' => 'Additional message'
        ]);
    }
    
    public function destroy($id)
    {
        // Delete করুন...
        
        return redirect()->route('users.index')
            ->with('danger', 'ব্যবহারকারী সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
```

### Blade এ Flash Messages দেখান

```blade
<!-- সাফল্যের বার্তা -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ত্রুটির বার্তা -->
@if(session('danger'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- সব flash messages একসাথে -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

# 6. MIDDLEWARE

## Middleware কি?
Middleware হল একটি ফিল্টার যা HTTP request এবং response এর মধ্য দিয়ে যায়।

```bash
# Middleware তৈরি করুন
php artisan make:middleware CheckAge
```

### Middleware তৈরি করুন

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
{
    public function handle(Request $request, Closure $next): Response
    {
        // Request এর আগে
        if ($request->age < 18) {
            return response('নূন্যতম বয়স ১৮ হতে হবে', 403);
        }
        
        $response = $next($request);
        
        // Response এর আগে
        $response->header('X-Check-Age', 'true');
        
        return $response;
    }
}
```

### Middleware নিবন্ধন করুন

```php
// app/Http/Kernel.php

protected $routeMiddleware = [
    'checkAge' => \App\Http\Middleware\CheckAge::class,
    'auth' => \App\Http\Middleware\Authenticate::class,
    'admin' => \App\Http\Middleware\IsAdmin::class,
];
```

### Route এ Middleware ব্যবহার করুন

```php
// routes/web.php

Route::get('/profile', function () {
    return 'Profile';
})->middleware('checkAge');

// একাধিক middleware
Route::get('/admin', function () {
    return 'Admin Panel';
})->middleware(['auth', 'admin']);

// Middleware group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

### Custom Middleware Example - Role Check

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        // User logged in কিনা
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        // Admin কিনা
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }
        
        return $next($request);
    }
}
```

---

# 7. VALIDATION

## Validation Rules

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Form validation
        $validated = $request->validate([
            // প্রয়োজনীয় ক্ষেত্র
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',  // confirmed মানে password_confirmation field থাকতে হবে
            
            // String rules
            'title' => 'required|string',
            'description' => 'string|nullable',
            'slug' => 'required|regex:/^[a-z0-9-]*$/',  // শুধু lowercase, numbers এবং dash
            
            // Numeric rules
            'age' => 'required|integer|min:18|max:120',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            
            // Array rules
            'tags' => 'array|min:1|max:5',
            'tags.*' => 'string',
            
            // File rules
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  // 2MB
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',  // 5MB
            
            // Date rules
            'birth_date' => 'required|date|before:today',
            'start_date' => 'required|date_format:Y-m-d',
            'appointment_date' => 'required|date|after:today',
            
            // Enum/Select rules
            'status' => 'required|in:active,inactive,pending',
            'role' => 'required|in:admin,user,moderator',
            
            // Conditional rules
            'country' => 'required',
            'state' => 'required_if:country,USA',  // Country USA হলে state প্রয়োজনীয়
            'zip' => 'exclude_if:country,UK',  // UK হলে zip required নয়
        ]);
        
        // validation এর আগে manual check
        $request->validate([
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);
        
        // Create user
        $user = User::create($validated);
        
        return redirect()->route('users.show', $user->id)
            ->with('success', 'ব্যবহারকারী সফলভাবে তৈরি হয়েছে!');
    }
}
```

### Custom Validation Messages

```php
$validated = $request->validate(
    [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ],
    [
        'name.required' => 'নাম ক্ষেত্র প্রয়োজনীয়।',
        'name.max' => 'নাম ২৫৫ অক্ষরের বেশি হতে পারে না।',
        'email.required' => 'ইমেইল ঠিকানা প্রয়োজনীয়।',
        'email.email' => 'একটি বৈধ ইমেইল ঠিকানা প্রদান করুন।',
        'email.unique' => 'এই ইমেইল ঠিকানা ইতিমধ্যে ব্যবহার করা হয়েছে।',
        'password.required' => 'পাসওয়ার্ড প্রয়োজনীয়।',
        'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে।',
        'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মেলে না।',
    ]
);
```

### Form Request Class

```bash
php artisan make:request StoreUserRequest
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    // ব্যবহারকারী এই request করতে পারে কিনা
    public function authorize(): bool
    {
        return true;  // auth()->check() && auth()->user()->isAdmin();
    }
    
    // Validation rules
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ];
    }
    
    // Custom messages
    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজনীয়।',
            'email.unique' => 'এই ইমেইল ইতিমধ্যে ব্যবহৃত।',
        ];
    }
}

// Controller এ ব্যবহার করুন
public function store(StoreUserRequest $request)
{
    $validated = $request->validated();  // সব validation pass হয়েছে
    
    User::create($validated);
    
    return redirect()->route('users.index')->with('success', 'তৈরি হয়েছে!');
}
```

---

# 8. SEEDER & FAKER

## Seeder দিয়ে Database জনপ্রিয় করুন

```bash
# Seeder তৈরি করুন
php artisan make:seeder UserSeeder

# Model এর সাথে factory এবং seeder তৈরি করুন
php artisan make:model Product -s  # -s মানে seeder তৈরি করুন
```

### Seeder তৈরি করুন

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fake ডেটা দিয়ে ১০ জন user তৈরি করুন
        User::factory()
            ->count(10)
            ->create();
        
        // Manual ডেটা insert করুন
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
    }
}

// DatabaseSeeder.php - সব seeder চালান
public function run(): void
{
    $this->call([
        UserSeeder::class,
        PostSeeder::class,
        ProductSeeder::class,
    ]);
}
```

### Factory দিয়ে Fake ডেটা তৈরি করুন

```bash
# Factory তৈরি করুন
php artisan make:factory UserFactory
```

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => \Illuminate\Support\Str::random(10),
            'phone' => $this->faker->phoneNumber(),
            'bio' => $this->faker->sentence(),
            'age' => $this->faker->numberBetween(18, 80),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

// Factory এ custom states তৈরি করুন
public function admin(): Factory
{
    return $this->state(function (array $attributes) {
        return [
            'role' => 'admin',
            'email_verified_at' => now(),
        ];
    });
}

// Factory ব্যবহার করুন
$user = User::factory()->create();  // একটি user
$users = User::factory()->count(50)->create();  // ৫০টি users
$admin = User::factory()->admin()->create();  // Admin user
```

### Faker দিয়ে Fake ডেটা Generate করুন

```php
<?php

use Faker\Factory as FakerFactory;

$faker = FakerFactory::create('bn_BD');  // Bengali locale

// প্রায়োজনীয় ডেটা
$faker->name();                    // নাম: "আহমেদ আলী"
$faker->email();                   // ইমেইল: "email@example.com"
$faker->phoneNumber();             // ফোন: "01612345678"
$faker->address();                 // ঠিকানা
$faker->city();                    // শহর
$faker->country();                 // দেশ
$faker->postcode();                // পোস্টাল কোড

// Text ডেটা
$faker->word();                    // শব্দ
$faker->words(3);                  // ৩টি শব্দ
$faker->sentence();                // বাক্য
$faker->paragraph();               // অনুচ্ছেদ
$faker->text(200);                 // ২০০ অক্ষরের টেক্সট

// Date/Time
$faker->date();                    // তারিখ: "1995-05-21"
$faker->time();                    // সময়: "13:45:30"
$faker->dateTime();                // তারিখ এবং সময়
$faker->dateTimeBetween('-1 year', 'now');

// Numeric
$faker->numberBetween(1, 100);     // ১ থেকে ১০০ এর মধ্যে সংখ্যা
$faker->randomDigit();             // ০ থেকে ৯ এর মধ্যে
$faker->randomFloat(2, 0, 1000);   // ০ থেকে ১০০০ এর মধ্যে দশমিক

// URL এবং File
$faker->url();                     // URL
$faker->imageUrl();                // ইমেজ URL
$faker->fileExtension();           // File extension: "pdf", "jpg"

// Boolean এবং Random
$faker->boolean();                 // true/false
$faker->randomElement(['active', 'inactive']);  // Array থেকে random এলিমেন্ট
```

### Seeder চালান

```bash
# সব seeders চালান
php artisan db:seed

# নির্দিষ্ট seeder চালান
php artisan db:seed --class=UserSeeder

# Database fresh করুন এবং seed করুন
php artisan migrate:fresh --seed
```

---

# 9. MASS ASSIGNMENT SECURITY

## Fillable vs Guarded - সুরক্ষা নীতি

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Method 1: Fillable - শুধুমাত্র এই ক্ষেত্রগুলি mass assign করা যাবে
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
    ];
    
    // কোন ক্ষেত্রগুলি mass assign করা যাবে না তা সংজ্ঞায়িত করুন
    protected $guarded = [
        'id',
        'role',           // সাধারণ user 'admin' role নিতে পারবে না
        'is_verified',    // প্রশাসক দ্বারা শুধুমাত্র যাচাই করা হবে
        'created_at',
        'updated_at',
    ];
    
    // সব ক্ষেত্র সুরক্ষিত করুন (সবকিছু guarded)
    // protected $guarded = [];  // সব ক্ষেত্র guarded
    
    // সব ক্ষেত্র উন্মুক্ত করুন (বিপজ্জনক!)
    // protected $fillable = ['*'];  // বিপজ্জনক!
}

// Controller এ ব্যবহার
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'phone' => 'nullable|string',
        'bio' => 'nullable|string',
    ]);
    
    // Mass assignment - শুধুমাত্র fillable ক্ষেত্রগুলি যোগ হয়
    $user = User::create($validated);
    
    // কেউ যদি role পাঠায় তবে ignored হয়
    // $request->role এবং $request->is_verified গুলি অন্তর্ভুক্ত করা হবে না
    
    // Manual assignment প্রয়োজনের সময়
    $user->role = 'admin';  // সরাসরি assign
    $user->save();
    
    return redirect()->route('users.show', $user->id);
}
```

## সুরক্ষা উদাহরণ

```php
// ❌ অনিরাপদ
class Product extends Model
{
    protected $fillable = ['*'];  // বিপজ্জনক
}

// ✅ নিরাপদ
class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
    ];
    
    protected $guarded = [
        'id',
        'admin_notes',
        'cost',  // লাভ মার্জিন প্রকাশ করতে পারে
        'supplier_id',
    ];
}

// Controller এ
$product = Product::create($request->validated());  // নিরাপদ

// admin_notes, cost ইত্যাদি direct হবে না যদি fillable এ না থাকে
// কেউ যদি hidden্র admin_notes পাঠায়, তবুও তা যুক্ত হবে না
```

---

# 10. ALERT NOTIFICATIONS

## SweetAlert2, Toastr, Notyf ইত্যাদি

### SweetAlert2 ব্যবহার করুন

```blade
<!-- HTML Head এ CDN যুক্ত করুন -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- সাফল্যের বার্তা -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'সফল!',
            text: '{{ session('success') }}',
            confirmButtonText: 'ঠিক আছে'
        });
    </script>
@endif

<!-- ত্রুটির বার্তা -->
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ত্রুটি!',
            text: '{{ session('error') }}',
            confirmButtonText: 'ঠিক আছে'
        });
    </script>
@endif

<!-- Delete Confirmation -->
<form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger" onclick="confirmDelete(event)">
        Delete
    </button>
</form>

<script>
function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        icon: 'warning',
        title: 'নিশ্চিত?',
        text: 'এই ব্যবহারকারী মুছে ফেলা হবে!',
        showCancelButton: true,
        confirmButtonText: 'হ্যাঁ, মুছুন',
        cancelButtonText: 'বাতিল করুন'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.closest('form').submit();
        }
    });
}
</script>
```

### Toastr ব্যবহার করুন

```blade
<!-- HTML Head এ CDN যুক্ত করুন -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Flash message সহ -->
@if(session('success'))
    <script>
        toastr.success("{{ session('success') }}", "সফল");
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error("{{ session('error') }}", "ত্রুটি");
    </script>
@endif

@if(session('info'))
    <script>
        toastr.info("{{ session('info') }}", "তথ্য");
    </script>
@endif

<!-- Manual trigger -->
<script>
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "timeOut": 3000
};

toastr.success("সাফল্যের বার্তা!");
toastr.warning("সতর্কতা!");
toastr.error("ত্রুটির বার্তা!");
</script>
```

### Notyf ব্যবহার করুন

```blade
<!-- HTML Head এ CDN যুক্ত করুন -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

<script>
const notyf = new Notyf();

// সাফল্যের বার্তা
notyf.success('সাফল্য!');

// ত্রুটির বার্তা
notyf.error('ত্রুটি!');

// Custom বার্তা
notyf.open({
    type: 'success',
    message: 'কাস্টম বার্তা',
    duration: 3000
});
</script>
```

---

# 11. LOCALIZATION

## Multi-Language সাপোর্ট

```bash
# Language files দেখুন
resources/lang/en/
resources/lang/bn/
```

### Language ফাইল তৈরি করুন

```php
// resources/lang/en/messages.php
<?php

return [
    'welcome' => 'Welcome to our site',
    'login' => 'Login',
    'logout' => 'Logout',
    'email' => 'Email Address',
    'password' => 'Password',
    'remember_me' => 'Remember Me',
    'forgot_password' => 'Forgot Your Password?',
];

// resources/lang/bn/messages.php
<?php

return [
    'welcome' => 'আমাদের সাইটে স্বাগতম',
    'login' => 'লগইন করুন',
    'logout' => 'লগ আউট করুন',
    'email' => 'ইমেইল ঠিকানা',
    'password' => 'পাসওয়ার্ড',
    'remember_me' => 'আমাকে মনে রাখুন',
    'forgot_password' => 'পাসওয়ার্ড ভুলে গেছেন?',
];
```

### Blade এ Localization ব্যবহার করুন

```blade
<!-- ডিফল্ট language -->
<h1>{{ __('messages.welcome') }}</h1>

<!-- বিভিন্ন language -->
<label>{{ __('messages.email') }}</label>
<label>{{ trans('messages.password') }}</label>

<!-- Pluralization -->
{{ __('messages.apples', ['count' => 5]) }}

<!-- Fallback -->
{{ __('messages.missing_key', [], 'fallback message') }}
```

### Middleware দিয়ে Language সেট করুন

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // URL থেকে language detect করুন
        if ($lang = $request->segment(1)) {
            if (in_array($lang, ['en', 'bn', 'fr'])) {
                App::setLocale($lang);
            }
        }
        
        // Session থেকে language নিন
        if (auth()->check() && auth()->user()->language) {
            App::setLocale(auth()->user()->language);
        }
        
        // Default language
        App::setLocale(config('app.locale'));
        
        return $next($request);
    }
}

// Route এ ব্যবহার করুন
Route::group(['middleware' => 'setlocale'], function () {
    Route::get('/', [HomeController::class, 'index']);
});
```

---

# 12. SOCIALITE - GOOGLE LOGIN

```bash
# Socialite install করুন
composer require laravel/socialite
```

### Google OAuth সেটআপ

```php
// .env ফাইলে
GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret

// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('APP_URL') . '/auth/google/callback',
],
```

### Controller এ Google Login

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // Google Login Page এ redirect করুন
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    // Google callback থেকে
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        
        // User এর ডেটা পান
        $id = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();
        $avatar = $googleUser->getAvatar();
        
        // User খুঁজুন বা তৈরি করুন
        $user = User::firstOrCreate(
            ['google_id' => $id],
            [
                'name' => $name,
                'email' => $email,
                'avatar' => $avatar,
                'password' => bcrypt(Str::random(16)),
            ]
        );
        
        // Login করান
        auth()->login($user, true);
        
        return redirect('/dashboard');
    }
}

// routes/web.php
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
```

### Blade এ Google Login Button

```blade
<a href="{{ route('google.login') }}" class="btn btn-primary">
    <i class="fab fa-google"></i> Google দিয়ে লগইন করুন
</a>
```

---

# 13. CUSTOM AUTH & ROLE MANAGEMENT

## User Model এ Role

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // roles এর সাথে relationship
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    // User এর role আছে কিনা চেক করুন
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
    
    // User এর permission আছে কিনা
    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) {
            $query->where('name', $permission);
        })->exists();
    }
    
    // Role দিন
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        $this->roles()->attach($role);
    }
}

// Role Model
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

// Permission Model
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

### Middleware দিয়ে Role Check করুন

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        $user = auth()->user();
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }
        
        abort(403, 'অননুমোদিত অ্যাক্সেস');
    }
}

// routes/web.php
Route::middleware('role:admin,moderator')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/users/{user}', [AdminController::class, 'update']);
});
```

### Controller এ Role Check করুন

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'এই পৃষ্ঠা শুধুমাত্র অ্যাডমিনের জন্য।');
        }
        
        return view('admin.dashboard');
    }
    
    public function moderatorOnly()
    {
        if (!auth()->user()->hasRole('moderator')) {
            abort(403);
        }
        
        return view('moderator.dashboard');
    }
}
```

---

# 14. BLADE TEMPLATES

## Blade কি এবং কেন ব্যবহার করি?
Blade হল Laravel এর template engine যা PHP কোড simple এবং পাঠযোগ্য করে তোলে।

### Basic Syntax

```blade
<!-- Variables -->
<h1>{{ $title }}</h1>

<!-- Method call -->
<p>{{ $user->getName() }}</p>

<!-- Array access -->
<p>{{ $data['name'] }}</p>

<!-- Default value -->
<p>{{ $user->name ?? 'অপরিচিত' }}</p>

<!-- Echo যদি variable set থাকে -->
<p>{{ isset($name) ? $name : 'নাম নেই' }}</p>

<!-- Method chaining -->
<p>{{ $user->profile->bio ?? 'কোন বায়ো নেই' }}</p>

<!-- HTML escape করুন (নিরাপত্তার জন্য) -->
<p>{!! $content !!}</p>  <!-- HTML tag render করুন -->
<p>{{ $content }}</p>    <!-- HTML escape করুন -->
```

### Conditional Statements

```blade
<!-- if, else, elseif -->
@if($user->status === 'active')
    <span>সক্রিয়</span>
@elseif($user->status === 'pending')
    <span>অপেক্ষারত</span>
@else
    <span>নিষ্ক্রিয়</span>
@endif

<!-- unless (if না) -->
@unless($user->verified)
    <p>আপনার অ্যাকাউন্ট যাচাই করুন।</p>
@endunless

<!-- isset/empty -->
@isset($user)
    <p>ব্যবহারকারী আছে</p>
@endisset

@empty($posts)
    <p>কোন পোস্ট পাওয়া যায়নি</p>
@endempty

<!-- switch case -->
@switch($user->role)
    @case('admin')
        <p>Admin Panel</p>
        @break
    @case('user')
        <p>User Dashboard</p>
        @break
    @default
        <p>Guest</p>
@endswitch
```

### Loops

```blade
<!-- foreach -->
@foreach($users as $user)
    <div>
        <p>{{ $user->name }}</p>
        <p>{{ $user->email }}</p>
    </div>
@endforeach

<!-- forelse - যদি collection খালি থাকে -->
@forelse($posts as $post)
    <div class="post">
        <h3>{{ $post->title }}</h3>
        <p>{{ $post->content }}</p>
    </div>
@empty
    <p>কোন পোস্ট পাওয়া যায়নি</p>
@endforelse

<!-- while -->
@while($count++ < 10)
    <p>{{ $count }}</p>
@endwhile

<!-- $loop variable (foreach এর মধ্যে) -->
@foreach($items as $item)
    <p>
        Item #{{ $loop->iteration }}
        @if($loop->first) (প্রথম) @endif
        @if($loop->last) (শেষ) @endif
        @if($loop->even) (জোড়) @else (বিজোড়) @endif
    </p>
@endforeach
```

### Comments

```blade
{{-- এটি Blade comment যা rendered হয় না --}}

<!-- এটি HTML comment যা page source এ দৃশ্যমান থাকে -->
```

### Include এবং Components

```blade
<!-- অন্য template include করুন -->
@include('partials.header')

<!-- Data pass করুন -->
@include('partials.sidebar', ['position' => 'left'])

<!-- Foreach সহ include -->
@each('partials.post', $posts, 'post')

<!-- Component use করুন -->
<x-alert type="success" message="সাফল্য!" />

<!-- Named slot সহ -->
<x-card>
    <x-slot:title>
        কার্ড শিরোনাম
    </x-slot:title>
    
    <p>কার্ড বিষয়বস্তু</p>
</x-card>
```

### Form Handling

```blade
<!-- CSRF token (নিরাপত্তার জন্য প্রয়োজনীয়) -->
<form method="POST" action="/users">
    @csrf
    
    <!-- Method spoofing (PUT, PATCH, DELETE) -->
    @method('PUT')
    
    <!-- Form fields -->
    <input type="text" name="name" value="{{ old('name') }}" />
    
    <!-- Validation errors -->
    @error('name')
        <span>{{ $message }}</span>
    @enderror
    
    <!-- Checked select/radio -->
    <input type="checkbox" name="subscribe" @checked(old('subscribe', $user->subscribed)) />
    
    <!-- Disabled -->
    <button @disabled(!$user->verified)>Submit</button>
    
    <button type="submit">জমা দিন</button>
</form>

<!-- অন্য form method -->
<form method="POST" action="/users/{{ $user->id }}">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

### Extends এবং Sections (Layouts)

```blade
<!-- layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
</head>
<body>
    <header>@include('partials.header')</header>
    
    <main>
        @yield('content')
    </main>
    
    <footer>@include('partials.footer')</footer>
</body>
</html>

<!-- pages/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <h1>স্বাগতম!</h1>
    <p>এটি আমাদের হোমপেজ</p>
@endsection
```

---

# 15. PUSH NOTIFICATIONS

## বিজ্ঞপ্তি সিস্টেম তৈরি করুন

```bash
# Notification তৈরি করুন
php artisan make:notification UserRegistered
```

### Notification Class

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification implements ShouldQueue
{
    use Queueable;
    
    private $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }
    
    // যে channels এ পাঠাবেন
    public function via($notifiable)
    {
        return ['mail', 'database'];  // ইমেইল এবং database এ সংরক্ষণ করুন
    }
    
    // ইমেইল notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('স্বাগতম!')
            ->line('আপনি সফলভাবে নিবন্ধিত হয়েছেন।')
            ->action('প্রোফাইল দেখুন', url('/profile'))
            ->line('ধন্যবাদ!');
    }
    
    // Database notification
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'নতুন ব্যবহারকারী নিবন্ধিত',
            'message' => $this->user->name . ' নিবন্ধিত হয়েছেন।',
            'user_id' => $this->user->id,
        ];
    }
}
```

### Notification পাঠান

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserRegistered;
use Illuminate\Notifications\Notification;

class UserController extends Controller
{
    public function store()
    {
        $user = User::create([...]);
        
        // একজন user কে notification পাঠান
        $user->notify(new UserRegistered($user));
        
        // একাধিক users কে
        $users = User::where('role', 'admin')->get();
        Notification::send($users, new UserRegistered($user));
    }
}
```

### Blade এ Notification দেখান

```blade
<!-- Unread notifications -->
<div class="notifications">
    @forelse(auth()->user()->unreadNotifications as $notification)
        <div class="notification-item">
            <p>{{ $notification->data['message'] }}</p>
            <form method="POST" action="/notifications/{{ $notification->id }}/read" style="display:inline;">
                @csrf
                <button>চিহ্নিত পড়া</button>
            </form>
        </div>
    @empty
        <p>কোন নতুন বিজ্ঞপ্তি নেই</p>
    @endforelse
</div>

<!-- সব notifications -->
@foreach(auth()->user()->notifications as $notification)
    <p>{{ $notification->created_at->diffForHumans() }} - {{ $notification->data['message'] }}</p>
@endforeach
```

---

## সংক্ষিপ্ত রেফারেন্স

| বিষয় | কমান্ড/সিনট্যাক্স |
|------|---------|
| Migration তৈরি | `php artisan make:migration create_table` |
| Model তৈরি | `php artisan make:model User` |
| Controller তৈরি | `php artisan make:controller UserController` |
| Seeder তৈরি | `php artisan make:seeder UserSeeder` |
| Middleware তৈরি | `php artisan make:middleware CheckAge` |
| Log করুন | `Log::info('message')` |
| Session | `session(['key' => 'value'])` |
| Query | `User::where(...)->get()` |
| Validate | `$request->validate([...])` |
| Redirect সহ message | `redirect()->with('success', 'msg')` |

---

## শেখা শুরু করুন 🚀

1. **প্রথমে Migration শিখুন** - Database structure বুঝতে
2. **তারপর Model এবং Relationships** - ডেটা সম্পর্ক বুঝতে
3. **Query Builder এবং Eloquent** - ডেটা খুঁজে বের করতে
4. **Controller এবং Routing** - Logic handle করতে
5. **Blade Templates** - UI তৈরি করতে
6. **Validation এবং Security** - নিরাপত্তার জন্য
7. **Authentication এবং Authorization** - ব্যবহারকারী ম্যানেজমেন্টের জন্য

---

**Happy Learning! 🎉**
