<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'Unauthorized', 401);
        }
        
        $data = Credit::all();

        if ($data->isEmpty()) {
            return $this->success([], 'No Credits Found', 200);
        }

        return $this->success($data, 'Credits List', 200);
    }
}
