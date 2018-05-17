<?php

include "config/connection.php";

class Model
{
    private $db;
    private $table;
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Db::getInstance();
        $table = get_class($this);
        $this->table = mb_strtolower($table) . 's';
    }

    public function get($limit1 = null, $limit2 = null)
    {
        $list = [];
        $query = 'SELECT * FROM ' . $this->table;
        if($limit1 != null && $limit2 != null)$query = $query.' LIMIT '.$limit1.', '.$limit2;
        else if($limit1 != null) $query = $query.' LIMIT '.$limit1;
        $request = $this->db->query($query);
        foreach ($request->fetchAll() as $record) {
            $model = new Model();
            foreach ($this->fillable as $item) {
                $model->{$item} = $record[$item];
            }
            array_push($list, $model);
        }
        return $list;
    }

    public function create(array $data)
    {
        $fields = '';
        $values = '';
        $lastIndex = end(array_keys($data));
        $lastItem = array_pop($data);

        foreach ($data as $k => $item) {
            $fields = $fields . $k . ', ';
            $values = $values . '"' . $item . '", ';
        }
        $values = $values . '"' . $lastItem . '"';
        $fields = $fields . $lastIndex;
        $request = $this->db->query('INSERT INTO ' . $this->table . ' (' . $fields . ') VALUES (' . $values . ')');
    }

    public function where(array $data)
    {
        $list = [];
        if (count($data) > 0) {
            $search = '';
            $firstIndex = array_shift(array_keys($data));
            $firstItem = array_shift($data);
            if (count($data) > 0) {
                $search = "WHERE " . $firstIndex . " = '" . $firstItem . "' ";
                foreach ($data as $k => $item) {
                    $search = $search . "AND " . $k . " = '" . $item . "' ";
                }
            } else $search = 'WHERE ' . $firstIndex . ' = ' . $firstItem;

            $request = $this->db->query('SELECT * FROM ' . $this->table . ' ' . $search);
            $request = $request->fetchAll();
            if (count($request) > 1) {
                foreach ($request as $record) {
                    $model = new Model();
                    foreach ($this->fillable as $item) {
                        $model->{$item} = $record[$item];
                    }
                    array_push($list, $model);
                }
            }
            else if(count($request) == 1){
                $model = new Model();
                foreach ($this->fillable as $item) {
                    $model->{$item} = $request[0][$item];
                }
                return $model;
            }

        }
        return $list;
    }

    public function update(array $data, $id)
    {
        if (count($data) > 0) {
            $search = '';
            $firstIndex = array_shift(array_keys($data));
            $firstItem = array_shift($data);
            if (count($data) > 0) {
                $search = "SET " . $firstIndex . " = '" . $firstItem . "' ";
                foreach ($data as $k => $item) {
                    $search = $search . ", " . $k . " = '" . $item . "' ";
                }
            } else $search = 'SET ' . $firstIndex . ' = ' . $firstItem;

            $this->db->query('UPDATE ' . $this->table . ' ' . $search . ' WHERE id = '.$id);

        }
    }


}