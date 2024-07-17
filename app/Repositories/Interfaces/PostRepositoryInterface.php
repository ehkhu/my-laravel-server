<?php
namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    public function all($request);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getPostsAsValueLabel();

    public function countPosts();
}