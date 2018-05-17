<?php

include "app/models/User.php";

class AuthController extends Controller
{
    public function index()
    {
        $this->view('login.php', 'template.php');
    }

    public function login()
    {
        $user = new User();
        $user = $user->auth($_REQUEST['email'], $_REQUEST['password']);
        if(empty($user)) echo 0;
        else echo json_encode($user);

    }

    public function register()
    {
        $data = $_REQUEST;
        $task = new User();
        $task->store($data);

    }


}