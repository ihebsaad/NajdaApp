<?php
/**
 * Created by PhpStorm.
 * User: admin1
 * Date: 28/03/2019
 * Time: 13:40
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocol {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && App::environment() === 'production') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}

