<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=> 'auth'], function() {
    Route::get("/", \App\Http\Livewire\Index::class);
    Route::get("/stock", \App\Http\Livewire\Stock::class);
    Route::get("/item", \App\Http\Livewire\Item::class);
    Route::get("/customer", \App\Http\Livewire\Customer::class);
    Route::get("/purchase", \App\Http\Livewire\Purchase::class);
    Route::get("/sell", \App\Http\Livewire\Sell::class);
    Route::get("/return", \App\Http\Livewire\ReturnOperation::class);
    Route::get("/stockItem", \App\Http\Livewire\LStockItem::class);
    Route::get("/transfer", \App\Http\Livewire\Transfer::class);
    Route::get("/report", \App\Http\Livewire\Report::class);
    Route::get("/user", \App\Http\Livewire\User::class);
    Route::get("/logout", [\App\Http\Livewire\Login::class, 'logout']);
    Route::get("/backupList", \App\Http\Livewire\BackupList::class);
    Route::get('/backup',
        function () {
            $projectDir = substr(getcwd(), 0, strpos(getcwd(), '\public'));
            $command = "cd ".$projectDir . ' && php artisan backup:run --only-db';
            exec($command);
            return redirect()->back();
    });
});


Route::get("/login", \App\Http\Livewire\Login::class)->middleware('guest')->name('login');
