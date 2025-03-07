<?php

namespace App\Interface\Service\Post;

use Illuminate\Http\Request;

interface PostServiceInterface
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function validatePostData(Request $request): array;

    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function storePost(Request $request): void;

    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPosts(array $filters);

    /**
     * @param int $id
     * @return mixed
     */
    public function edit($id);

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     */
    public function confirmEdit(Request $request, $id);

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     */
    public function updatePost(Request $request, $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function deletePost($id);

    /**
     * @param mixed $file
     * @return void
     */
    public function processCSVUpload($file);

    /**
     * @return string
     */
    public function generatePostsCsv(): string;

    /**
     * @param int $id
     * @return string
     */
    public function generateSinglePostCsv($id): string;
}
