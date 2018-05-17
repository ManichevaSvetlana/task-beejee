<?php

class Task extends Model
{

    protected $fillable = ['id', 'user_name', 'user_email', 'description', 'image', 'status'];

    public function store(array $data){
        $this->create([
            'user_name' => $data["name"],
            'user_email' => $data["email"],
            'description' => $data["description"],
            'image' => $data["image"],
            'status' => 0
        ]);
    }

    public function updateStatus(array $data){
        $this->update([
            'status' => $data['status']
        ], $data['task_id']);
    }

}