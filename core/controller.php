<?php



class Controller {
	
	private $model;
	private $view;
	
	function __construct()
	{
		$this->view = new View();
		$this->model = new Model();
	}

	public function index()
	{

	}

	public function view($contentView, $templateView, $data = null)
	{
        return $this->view->generate($contentView, $templateView, $data);
	}
}
