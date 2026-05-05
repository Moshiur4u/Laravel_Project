<?php

// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// require __DIR__.'/auth.php';
