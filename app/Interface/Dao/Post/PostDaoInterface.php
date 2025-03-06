<?php

namespace App\Interface\Dao\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostDaoInterface
{
    /**
     * Get filtered posts based on provided filters.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredPosts(array $filters);

    /**
     * Create a new post in the database.
     *
     * @param array $data
     * @return void
     */
    public function createPost(array $data): void;
    public function findPostById($id);
    public function updatePost($id, array $data);
    public function deletePost($id);
    public function uploadPostsFromCSV(array $postsData);
    public function getAllPosts(): Collection;
}
