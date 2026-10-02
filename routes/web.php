<?php

use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/password', function () {
    return view('auth.password');
})->name('app.password');

Route::get('/user-manual', function () {
    return view('manual.user-manual');
})->name('user.manual');

Route::post('/password', function () {
    $expectedPassword = env('APP_PASSWORD', 'issueboard123');
    $password = request('password');

    if ($password === $expectedPassword) {
        session(['app_password_verified' => true]);

        return redirect()->route('home');
    }

    return back()->with('error', 'Password salah.');
})->name('app.password.submit');

Route::middleware(['app.password'])->group(function () {
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
});
