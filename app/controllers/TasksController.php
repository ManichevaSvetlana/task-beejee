<?php

include "app/models/Task.php";

class TasksController extends Controller
{
    public function index()
    {
        $this->view('tasks.php', 'template.php');
    }

    public function show()
    {
        $task = new Task();
        $data = $_REQUEST;
        $limit1 = $data['limit_1'];
        if($data['limit_2'] != 0) $data = $task->get($limit1, $data['limit_2']);
        else  $data = $task->get($limit1);
        echo json_encode($data);
    }

    public function store()
    {
        $data = $_REQUEST;
        $task = new Task();
        $targetFile = $this->imageUpload();
        if ($targetFile != false) $data["image"] = $targetFile;
        else  $data["image"] = '';
        $task->store($data);
    }

    public function status()
    {
        $data = $_REQUEST;
        if($data['user_email'] == 'admin' && $data['user_id'] == '1'){
            $task = new Task();
            $task->updateStatus($data);
        }
        else echo 'forbidden';

    }

    public function imageUpload()
    {
        if (basename($_FILES["image"]["name"]) != '') {
            $targetDir = "resources/images/";
            $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
            $file = time() . '.' . $imageFileType;
            $targetFile = $targetDir . $file;
            $uploadOk = true;
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) $uploadOk = true;
            else $uploadOk = false;
            if (file_exists($targetFile)) $uploadOk = false;
            if ($uploadOk == true && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) return $file;
            return false;
        }
    }


}