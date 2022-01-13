<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Post Routes
Route::post('/uploadFile', [ProfileController::class, 'fileUpload']);
Route::post('/uploadVideoFile', [ProfileController::class, 'fileReceive']);
Route::post('/follow', [ProfileController::class, 'followUser']);
Route::post('/unfollow', [ProfileController::class, 'unfollowUser']);
Route::post('/uploadPost', [PostController::class, 'postUpload']);
Route::post('/postComment', [PostController::class, 'postComment']);
Route::post('/deletePost', [PostController::class, 'deletePost']);


//Get Routes
Route::get('/getUsers', [UserController::class, 'getAllUsers']);
Route::get('/getPosts', [PostController::class, 'getPosts']);
Route::get('/getComments', [PostController::class, 'getComments']);
Route::get('/getProfilePicture', [ProfileController::class, 'getProfilePicture']);
Route::get('/getFollowers', [ProfileController::class, 'getFollowers']);
Route::get('/getFollowings', [ProfileController::class, 'getFollowings']);
Route::get('/getPhotos', [ProfileController::class, 'getPhotos']);
Route::get('/getVideos', [ProfileController::class, 'getVideos']);











