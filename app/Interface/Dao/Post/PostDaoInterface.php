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
    /**
     * @param int $id
     * @return mixed
     */
    public function findPostById($id);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updatePost($id, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function deletePost($id);

    /**
     * @param array $postsData
     * @return void
     */
    public function uploadPostsFromCSV(array $postsData);

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllPosts(): Collection;
}
