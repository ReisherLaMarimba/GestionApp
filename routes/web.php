<?php

use App\Jobs\ProcessImagesJob;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Response;


Route::get('/', function () {
    return view('welcome');
});


//Route::get('/image', function () {
//    $image = Image::read(Storage::get('example.jpg'))
//        ->resize(800, 600)
//        ->scale(1280, 720);
//
//    $encodedImage = $image->toJpeg(quality: 70, progressive: false, strip: true);
//
//    // Send the encoded image as a response with appropriate headers
//    return Response::make($encodedImage, 200, [
//        'Content-Type' => 'image/jpeg',
//    ]);
//});

