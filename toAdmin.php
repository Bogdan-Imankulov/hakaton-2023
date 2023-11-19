<?php
$tel = $_POST['phoneNumber'];
$name = $_POST['name'];
$question = $_POST['question'];

// Validate and sanitize input (you might want to add more specific validation)
$tel = filter_var($tel, FILTER_SANITIZE_STRING);
$name = filter_var($name, FILTER_SANITIZE_STRING);
$question = filter_var($question, FILTER_SANITIZE_STRING);

// Establish a database connection
$db = new mysqli('localhost', 'p-338798_admin', 'p-338798_admin', 'p-338798_admin');

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Use prepared statements to prevent SQL injection
$stmt = $db->query("INSERT INTO admin_question (tel, name, question) VALUES ($tel, $name, $question)");
$db->close();

header('Location: main.php');
?>
