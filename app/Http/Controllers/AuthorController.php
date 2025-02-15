<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    use ApiResponse;

    /**
     * List all available authors.
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
            $authors = NewsArticle::select('author')->distinct()->paginate($perPage);

            if ($authors->isEmpty()) {
                return $this->successResponse('No authors found.', []);
            }

            return $this->successResponse('Authors retrieved successfully.', $authors);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
