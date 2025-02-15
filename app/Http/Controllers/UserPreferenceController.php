<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserPreferencesRequest;
use Illuminate\Http\JsonResponse;
use App\Models\UserPreferredAuthor;
use App\Models\UserPreferredSource;
use App\Traits\ApiResponse;

class UserPreferenceController extends Controller
{
    use ApiResponse;

    /**
     * Update user's preferred categories, authors, and sources.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreferences(UpdateUserPreferencesRequest $request)
    {
        $user = $request->user();

        DB::transaction(function () use ($user, $request) {
            if ($request->filled('categories')) {
                $categories = array_unique($request->categories);
                $user->preferredCategories()->sync($categories);
            }

            if ($request->filled('authors')) {
                $authors = array_unique($request->authors);
                $user->preferredAuthors()->delete();
                $authorData = collect($authors)->map(fn($author) => ['user_id' => $user->id, 'author_name' => $author, 'created_at' => now(),
                    'updated_at' => now()]);
                UserPreferredAuthor::insert($authorData->toArray());
            }

            if ($request->filled('sources')) {
                $sources = array_unique($request->sources);
                $user->preferredSources()->delete();
                $sourceData = collect($sources)->map(fn($source) => ['user_id' => $user->id, 'source_name' => $source, 'created_at' => now(),
                    'updated_at' => now()]);
                UserPreferredSource::insert($sourceData->toArray());
            }
        });

        return $this->successResponse('Preferences updated successfully');
    }


    /**
     * Get all user preferences (categories, authors, sources).
     */
    public function getUserPreferences(): JsonResponse
    {
        $user = auth()->user();

        return $this->successResponse('User Preferences retrieved successfully.', [
            'categories' => $user->preferredCategories()->select('news_categories.id', 'news_categories.name')
                ->get()
                ->makeHidden('pivot')
                ->toArray(),
            'authors' => $user->preferredAuthors()->pluck('author_name'),
            'sources' => $user->preferredSources()->pluck('source_name'),
        ]);
    }
}
