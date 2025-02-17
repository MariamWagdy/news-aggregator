<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsCategory;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * List all available categories.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $categories = NewsCategory::select('id', 'name')->get();

            if ($categories->isEmpty()) {
                return $this->successResponse('No categories found.', []);
            }

            return $this->successResponse('Categories retrieved successfully.', $categories);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
