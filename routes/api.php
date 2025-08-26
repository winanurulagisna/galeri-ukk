<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GalleryController;

#route user
Route::get('/user', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

#route pages
Route::get('/pages', [PageController::class, 'index']);
Route::post('/pages', [PageController::class, 'store']);
Route::get('/pages/{id}', [PageController::class, 'show']);
Route::put('/pages/{id}', [PageController::class, 'update']);
Route::delete('/pages/{id}', [PageController::class, 'destroy']);
Route::get('/pages/slug/{slug}', [PageController::class, 'getBySlug']);
Route::get('/pages/status/{status}', [PageController::class, 'getByStatus']);

#route staff
Route::get('/staff', [StaffController::class, 'index']);
Route::post('/staff', [StaffController::class, 'store']);
Route::get('/staff/{id}', [StaffController::class, 'show']);
Route::put('/staff/{id}', [StaffController::class, 'update']);
Route::delete('/staff/{id}', [StaffController::class, 'destroy']);
Route::get('/staff/position/{position}', [StaffController::class, 'getByPosition']);
Route::get('/staff/search/{keyword}', [StaffController::class, 'search']);

#route facilities
Route::get('/facilities', [FacilityController::class, 'index']);
Route::post('/facilities', [FacilityController::class, 'store']);
Route::get('/facilities/{id}', [FacilityController::class, 'show']);
Route::put('/facilities/{id}', [FacilityController::class, 'update']);
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy']);
Route::get('/facilities/search/{keyword}', [FacilityController::class, 'search']);

#route categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
Route::get('/categories/slug/{slug}', [CategoryController::class, 'getBySlug']);
Route::get('/categories/search/{keyword}', [CategoryController::class, 'search']);

#route posts
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::get('/posts/category/{categoryId}', [PostController::class, 'getByCategory']);
Route::get('/posts/date/{date}', [PostController::class, 'getByDate']);
Route::get('/posts/search/{keyword}', [PostController::class, 'search']);

#route galleries
Route::get('/galleries', [GalleryController::class, 'index']);
Route::post('/galleries', [GalleryController::class, 'store']);
Route::get('/galleries/{id}', [GalleryController::class, 'show']);
Route::put('/galleries/{id}', [GalleryController::class, 'update']);
Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
Route::post('/galleries/upload', [GalleryController::class, 'upload']);
Route::get('/galleries/post/{postId}', [GalleryController::class, 'getByPost']);
Route::get('/galleries/standalone', [GalleryController::class, 'getStandalone']);
Route::get('/galleries/search/{keyword}', [GalleryController::class, 'search']);