<?php

use App\Http\Controllers\ScrabingController;
use Illuminate\Support\Facades\Route;


Route::get('/',[ScrabingController::class,'scrab'])->name('scrabpost');