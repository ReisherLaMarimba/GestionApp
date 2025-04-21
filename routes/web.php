<?php

use App\Http\Controllers\AvailablesItemsApi;
use App\Http\Controllers\PrintController;
use App\Jobs\ProcessImagesJob;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Response;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/available-items', [AvailablesItemsApi::class, 'getAvailablesItems'])->name('api.available-items');

//RETURN VIEW

ROUTE::get('test', function () {

    $pdf = Pdf::loadView('Assignments.equipment_assignment',['imagePath' => 'images/cmaxlogo.png']);
    $pdf->setPaper('letter' );
    return $pdf->stream('equipment_assignment.pdf');
})->name('test');




Route::get('/print/equipment_assignment', [PrintController::class, 'PrintAssigments'])->name('print.equipment_assignment');

