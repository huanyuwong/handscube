<?php

use Handscube\Kernel\Route;

Route::get("/admin/{user}/{option}", "admin.index@user")->name("admin");

Route::prefix('/admin', function () {
    Route::get('/test/:id/:name', 'admin.index@test');
    Route::get('/connect', 'admin.index@connect');
});

Route::get("/admin/article/{id}/edit", "admin.index@blog");
Route::get("/User/:id/opt/{optional}", "User.user@add");
Route::get("/user/:id/edit", "index@test");
Route::match(["get", "post"], "/testmatch", "index@match");
Route::match(["get", "post"], "/testmatch/:id", "index@match");

// Route::post("/user/{id}")
Route::get("/pdotest", "admin.index@pdo");

Route::any('/connect', 'Index@connect');
Route::get('/test', 'index@test')->name('test');

// Route::get('/queue', 'index@queue');
// Route::get('/getqueue', 'index@getQueue');
