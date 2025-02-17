<?php

namespace App\Http\Controllers;

use App\Models\NewsPlatform;
use App\Traits\ApiResponse;

class PlatformsController extends Controller
{
    use ApiResponse;

    /**
     * List all available authors.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function index()
    {
        try {
            $platforms = NewsPlatform::get();
            return $this->successResponse('Platforms retrieved successfully.', $platforms);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
