<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}


// in servers Copy function index to errors on  C:\wamp2\www\najdaapp\vendor\rap2hpoutre\laravel-log-viewer\src\controllers\LogViewerController  to make /errors link working