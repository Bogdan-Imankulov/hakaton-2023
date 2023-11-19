<?php
$receiver_tel = $_POST['receiver_tel'];
$servername = "localhost";
$username = "p-338798_admin";
$password = "p-338798_admin";
$dbname = "p-338798_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT first_name, last_name, tel FROM users WHERE tel = '$receiver_tel'";
$result = $conn->query($sql);

if ($result === false || $result->num_rows == 0) {
    // No results or an error occurred
    echo json_encode(array('error' => $conn->error));
} else {
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $receiver_first_name = $row['first_name'];
        $receiver_last_name = $row['last_name'];
        $tel = $row['tel'];

        $data[] = array(
            'first_name' => $receiver_first_name,
            'last_name' => $receiver_last_name,
            'tel' => $tel
        );
    }

    echo json_encode($data);
}

$conn->close();
exit();
?>
