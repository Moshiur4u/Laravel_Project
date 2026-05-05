<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

Route::get('/', function () {
    // return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// Routing Perametter Pass in url
// route::get("user/{id}",function($id){
// return "User,$id";
// })->where('id','[0-9]+');

// String Perameter Routing in routing perameter using (slug) for pass string value
// Route::get('user/{slug}',function($slug){
//     return "User $slug";
// })->where('slug','[A-Za-z/-]+');


// একাধিক পেরামিটার রাঊটে পাচ করার জন্য
// Whereএর পরের আংশ হলও ভেলিডেসন [A-Za-z/-]+ দ্বারা স্ট্রিং ভ্যালু করে ধরা হয়।

// route::get("user/{slug}/{id}",function($slug,$id){
//     return "User $slug User PIN -$id";
// })->Where([
//     'slug'=> '[A-Za-z/-]+',
//     'id'=> '[0-9]+'
// ]);
// ->where('slug',"[A-Za-z/-]+")->where('id','[0-9]+')



// রাউটে পেরামিটার অপশনাল ডিক্লারেশন করা

// Route::get("user/{slug}/profile/{id?}",function($slug, $id=null){
//     return "User :$slug PIN :$id";
// })->where('slug','[A-Za-z]+');

// রাউট প্রিফিক্স হলও একাধি  রাউটের গ্রুপ
// route::prefix('admin')->group(function(){
//     route::get('/dashboard',[AdminController::class,'index' ])->name('admin.index');
//     route::get('/profile',[AdminController::class,'userProfile' ])->name('user.profile');
//     route::get('/aprovals',[AdminController::class,'aprove' ])->name('admin.aprove');
// });


// রাউটে  মিডিলওয়্যারের মাধ্যমে আথিন্টিকেশন করা হয়।
// যেমন কে কি আক্সেস পারে তাই মিডিলওয়্যারের রাউটের মাধ্যমে করা যায়।
// route::Middleware('auth')->group(function(){
//     route::get('/dashboard',[AdminController::class,'index' ])->name('admin.index');
//     route::get('/profile',[AdminController::class,'userProfile' ])->name('user.profile');
//     route::get('/aprovals',[AdminController::class,'aprove' ])->name('admin.aprove');
// });
// require __DIR__.'/auth.php';