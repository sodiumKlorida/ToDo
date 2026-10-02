<?php

use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [IssueController::class, 'index'])->name('home');

Route::get('/issue-image/{path}', function (string $path) {
    $disk = config('filesystems.default', 'public');
    $normalizedPath = str_replace(['\\', '../', '..\\'], ['/', '', ''], $path);
    $storage = Storage::disk($disk);

    if (!$storage->exists($normalizedPath)) {
        abort(404);
    }

    if ($storage->getAdapter() instanceof \League\Flysystem\Local\LocalFilesystemAdapter) {
        return response()->file($storage->path($normalizedPath));
    }

    return $storage->response($normalizedPath);
})->where('path', '.*')->name('issue.image');

Route::resource('issues', IssueController::class);
