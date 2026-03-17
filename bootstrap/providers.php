<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    Laravel\Boost\BoostServiceProvider::class, // <--- Add this exact line

];