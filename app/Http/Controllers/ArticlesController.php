<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateArticlesRequest;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class ArticlesController extends Controller
{
    use ApiResponse;

    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(ValidateArticlesRequest $request): JsonResponse
    {
        try {
            $articles = $this->articleService->getArticles($request->validated());
            return $this->successResponse("Articles retrieved successfully.", $articles);
        } catch (QueryException $e) {
            return $this->errorResponse("Database query error:" . $e->getMessage(), 500);
        } catch (Exception $e) {
            return $this->errorResponse("An unexpected error occurred: " . $e->getMessage(), 500);
        }
    }
}
