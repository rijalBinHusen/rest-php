<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header(
			'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS'
		);
	}
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header(
			"Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}"
		);
	}
	exit(0);
}

// <?php

// // Get the origin of the request
// $origin = $_SERVER['HTTP_ORIGIN'];

// // Check if the origin is allowed
// $allowed_origins = ['https://www.example.com', 'https://localhost'];
// if (!in_array($origin, $allowed_origins)) {
//   // Deny the request
//   header('Access-Control-Allow-Origin:');
//   exit();
// }

// // Allow the request
// header('Access-Control-Allow-Origin: ' . $origin);

// // Other CORS headers...
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// header('Access-Control-Allow-Headers: Content-Type, Authorization');
