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
        try {
            $authors = NewsArticle::select('author')->distinct()->get();

            if ($authors->isEmpty()) {
                return $this->successResponse('No authors found.', []);
            }

            return $this->successResponse('Authors retrieved successfully.', $authors);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
