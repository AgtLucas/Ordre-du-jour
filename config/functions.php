<?php

require 'db.php';

/**
 * Connect to database using PDO - PHP Data Object
 *
 * @param $config
 */
function connect($config)
{
	try {
		$conn = new PDO("mysql:host=localhost;dbname=agenda2",
								$config['DB_USER'],
								$config['DB_PASSWORD']);

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	} catch (PDOException $e) {
		return false;
	}
}

function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);
	return $stmt;
}

function getForgotKey($tableName, $key, $conn)
{
	return $conn->query("SELECT * FROM $tableName WHERE reset = $key");
}

function get($tableName, $conn)
{
	return $conn->query("SELECT * FROM $tableName WHERE user_id = " . $_SESSION['users']['id'] . " ORDER BY name");
}

function search($tableName, $params, $conn)
{
	return $conn->query("SELECT * FROM $tableName WHERE (name LIKE '%" . $params['search'] . "%'
						OR lastname LIKE '%" . $params['search'] . "%'
						OR qth LIKE '%" . $params['search'] . "%')
						AND user_id = " . $_SESSION['users']['id']);
}

function delete($tableName, $id, $conn)
{
	return $conn->query("DELETE FROM $tableName WHERE id = " . $conn->quote($id)
						. "AND user_id = " . $_SESSION['users']['id']);
}

function profile($tableName, $id, $conn)
{
	return $conn->query("SELECT * FROM $tableName WHERE id = $id AND user_id = " . $_SESSION['users']['id']);
}

function user($tableName, $id, $conn)
{
	return $conn->query("SELECT * FROM $tableName WHERE id = $id");
}

function is_logged_in($conn)
{
	if ( isset($_COOKIE['remember-me']) && !isset($_SESSION['users']) ) {
		$result = $conn->query("SELECT * FROM users WHERE id = " . $_COOKIE['remember-me']);
		$user = $result->fetch(PDO::FETCH_ASSOC);
		$_SESSION['users'] = $user;
		header('Location: ' . $_SERVER['REQUEST_URI']);
	}

	return isset($_SESSION['users']);
}