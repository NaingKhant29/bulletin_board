<?php

namespace App\Dao\Post;

use App\Interface\Dao\Post\PostDaoInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class PostDao implements PostDaoInterface
{
    /**
     * Get filtered posts based on various filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredPosts(array $filters)
    {
        $query = Post::query()->whereNull('deleted_at');

        if (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $query->where(function ($query) use ($filters) {
                $query->where('title', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['created_at']) && $filters['created_at'] !== '') {
            $query->whereDate('created_at', '=', $filters['created_at']);
        }

        if (Auth::check() && Auth::user()->type == 0) {
            return $query->orderBy('created_at', 'desc')->paginate(12);
        }

        return $query->where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
    }

    /**
     * Create a new post in the database.
     *
     * @param array $data
     * @return void
     */
    public function createPost(array $data): void
    {
        Post::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'created_user_id' =>  Auth::id(),
            'updated_user_id' => Auth::id(),
        ]);
    }

    /**
     * Get a post by ID.
     */
    public function findPostById($id)
    {
        return Post::findOrFail($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Post
     */
    public function updatePost($id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePost($id): void
    {
        $post = Post::findOrFail($id);
        DB::transaction(function () use ($post) {
            $post->update(['deleted_user_id' => Auth::id()]);
            $post->delete();
        });
    }

    /**
     * Upload posts from a CSV file and insert them into the database in batches.
     *
     * @param array $postsData Array of post data to be inserted.
     * @return void
     */
    public function uploadPostsFromCSV(array $postsData): void
    {
        $batchSize = 500;
        $insertData = [];

        foreach ($postsData as $data) {
            $insertData[] = [
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => (int) $data['status'],
                'category_id' => $data['category_id'],
                'created_user_id' => $data['created_user_id'],
                'updated_user_id' => $data['updated_user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($insertData) >= $batchSize) {
                Post::insert($insertData);
                $insertData = [];
            }
        }

        if (!empty($insertData)) {
            Post::insert($insertData);
        }
    }

    /**
     * Retrieve all posts with their associated category.
     *
     * @return Collection
     */
    public function getAllPosts(): Collection
    {
        return Post::with('category')->get();
    }
}
