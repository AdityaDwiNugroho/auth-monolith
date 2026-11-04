<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    Modules\Auth\Providers\AuthServiceProvider::class,
    Modules\Products\Providers\ProductServiceProvider::class,
    Modules\Library\Providers\LibraryServiceProvider::class,
];
