<?php

class MainController extends Controller
{

	function index()
	{	
		$this->view('index.php', 'template.php');
	}
}