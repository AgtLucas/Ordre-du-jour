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