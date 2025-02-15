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
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid parameters.', $validator->errors(), 422);
        }

        try {
            $perPage = $request->query('per_page', 10);
            $categories = NewsCategory::select('id', 'name')->paginate($perPage);

            if ($categories->isEmpty()) {
                return $this->successResponse('No categories found.', []);
            }

            return $this->successResponse('Categories retrieved successfully.', $categories);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
