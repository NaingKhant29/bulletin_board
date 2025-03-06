<?php

namespace App\Interface\Service\Post;

use Illuminate\Http\Request;

interface PostServiceInterface
{
    /**
     * Validate the data for creating a post.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function validatePostData(Request $request): array;

    /**
     * Store the post in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function storePost(Request $request): void;

    /**
     * Get the filtered posts based on the provided filters.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPosts(array $filters);
    public function edit($id);
    public function confirmEdit(Request $request, $id);
    public function updatePost(Request $request, $id);
    public function deletePost($id);
    public function processCSVUpload($file);
    public function generatePostsCsv(): string;
    public function generateSinglePostCsv($id): string;
}
