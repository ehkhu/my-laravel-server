<?php
namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function all($request)
    {
        return $this->model->filter(request(['search']))
        ->orderBy($request['sortBy'] ? $request['sortBy'] : 'id', $request['direction'] ? $request['direction'] : "desc")
        ->paginate($request['pageSize'] ? $request['pageSize'] : 10);
    }

    public function find($id)
    {
        $patient = $this->model->with([
            // Add relation here
        ])
        ->find($id);
        return $patient;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->model->findOrFail($id);
        $record->delete();
    }

    public function getPostsAsValueLabel()
    {
        $data = $this->model->select('id as value', 'name as label')->get();
        // Convert 'value' to string for each item in the collection
        return $formattedData = $data->map(function ($item) {
            return [
                'value' => (string) $item['value'],
                'label' => $item['label'],
            ];
        });
    }

    public function countPosts()
    {
        $numberOfPosts = Post::count();
        return ["post_count" => $numberOfPosts];
    }

    public function getAll()
    {
        return $this->model->all();
    }
    
    public function exportFilter($request)
    {
        return $this->model->filter(request(['search']))->get();
    }
}