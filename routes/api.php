<?php

use App\Http\Controllers\api\FindFastItemController;
use App\Http\Resources\Item\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/search-item/{item_code}', [FindFastItemController::class, 'FastSearch'])->middleware('throttle:5,1')->name('search-item');
