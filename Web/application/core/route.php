<?php
class Route
{
	static function start()
	{
		$controllerName = (!isset($_GET['p1'])?'statistics':$_GET['p1']);
		$actName = 'action_'.(!isset($_GET['p2'])?'index':$_GET['p2']);
		$modelPath = "application/models/$controllerName.model.php";
		if(file_exists($modelPath))
		include_once($modelPath);
		$controllerPath = "application/controllers/$controllerName.controller.php";
		if(!file_exists($controllerPath))
		{
			header('Location: ?p1=error');
			exit;
		}
		include_once($controllerPath);
		$className = "Controller_$controllerName";
		$controller = new $className;
		if(method_exists($controller, $actName)) $controller->$actName();
		else
		{
			header('Location: ?p1=error');
			exit;
		}
	}
}
?>