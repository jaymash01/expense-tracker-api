<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\ResponseHandler;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseHandler;

    public function __invoke(Request $request)
    {

        return $this->createResponse(null, 200, 'Success');
    }
}
