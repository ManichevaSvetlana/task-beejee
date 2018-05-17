<?php

class User extends Model
{
    protected $fillable = ['id', 'name', 'email', 'password'];

    public function auth($email, $password)
    {
        $model = $this->where([
            'email' => $email,
            'password' => $password
        ]);
        return $model;
    }

    public function store(array $data){
        $this->create([
            'name' => $data["name"],
            'email' => $data["email"],
            'password' => $data["password"]
        ]);
    }

}