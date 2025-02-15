<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\Validator;

class SourceController extends Controller
{
    use ApiResponse;

    /**
     * List all available sources.
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
            $sources = NewsArticle::select('source')->distinct()->paginate($perPage);

            if ($sources->isEmpty()) {
                return $this->successResponse('No sources found.', []);
            }

            return $this->successResponse('Sources retrieved successfully.', $sources);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
