<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

// connect to db
function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="slim";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}


// list all
$app->get('/tasks', function () {
	$sql = "select * FROM tasks ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$results = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($results);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

// get one
$app->get('/tasks/:id', function ($id) use ($app) {
	$sql = "select * FROM tasks WHERE id=".$id." ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$results = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($results);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

// create new one
$app->post('/add', function() use ($app) {
	$request = $app->request();
	$task = json_decode($request->getBody());
	$sql = "INSERT INTO tasks (name, description) VALUES (:name, :description)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $task->name);
		$stmt->bindParam("description", $task->description);
		$stmt->execute();
		$task->id = $db->lastInsertId();
		$db = null;
		echo json_encode($task); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

// update one
$app->put('/tasks/:id', function ($id) use ($app) {
	$request = $app->request();
	$task = json_decode($request->getBody());
	$sql = "UPDATE tasks SET name=:name, description=:description WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $task->name);
		$stmt->bindParam("description", $task->description);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($task); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

// delete one
$app->delete('/tasks/:id', function ($id) {
	$sql = "DELETE FROM tasks WHERE id=".$id;
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$results = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($results);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

$app->run();

?>