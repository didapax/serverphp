<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
header('Content-Type: application/json');
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Conexión a la base de datos
$host = "localhost";
$username = "root";
$pass = "";
$dbname = "jfb";
$method = $_SERVER['REQUEST_METHOD'];
$conn = new mysqli($host, $username, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error: No se pudo conectar a MySQL. " . $conn->connect_error);
}

// Métodos de comunicación con el front
if ($method == "OPTIONS") {
    die();
}

if ($method == "POST") {
    try {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (isset($data['example'])) {
            $stmt = $conn->prepare("SELECT * FROM xxx WHERE user_name=? AND isBlocked=0");
            $stmt->bind_param("s", $data['example']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response = array('exists' => true, 'pass' => true);
            } else {
                $response = array('exists' => false, 'pass' => false);
            }
            echo json_encode($response);
            $stmt->close();
        }
    } catch (Exception $e) {
        $response = array('Error' => $e->getMessage());
        echo json_encode($response);
    }
}

if ($method == "GET") {
    if (isset($_GET['Example'])) {
        $obj = array('current_period' => true, 'time_period' => false);
        echo json_encode($obj);
    }
}

// Cerrar la conexión
$conn->close();
?>