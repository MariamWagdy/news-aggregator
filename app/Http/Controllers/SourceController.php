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
        try {
            $sources = NewsArticle::select('source')
                ->whereNotNull('source')
                ->where('source', '!=', '')
                ->distinct()->get();

            if ($sources->isEmpty()) {
                return $this->successResponse('No sources found.', []);
            }

            return $this->successResponse('Sources retrieved successfully.', $sources);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
