<?php

class View
{

	function generate($content, $template, $data = null)
	{
		include 'app/views/'.$template;

	}
}
