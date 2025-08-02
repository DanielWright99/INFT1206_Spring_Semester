<?php

header('Content-Type: application/json');
require_once '../includes/db_connect.php';

//API Key for Authentication
$valid_api_key = 'your_secure_api_key_1234';

if(!isset($_GET['api_key']) || $_GET['api_key'] !== $valid_api_key){
    http_response_code(401); //Unauthorized Request
    echo json_encode(['error' => 'Invalid or missing API key']);
    exit;
}

//Initialize response
$response = [];

$conn = get_db_connection();
$query = "SELECT id, name, feedback, submitted_at, user_id FROM feedback ORDER BY submitted_at DESC";
$queryResult = pg_query($conn, $query);

if(!$queryResult){
    $feedbackData = [];
    while($row = pg_fetch_assoc($queryResult)){
        $feedbackData = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'feedback' => $row['feedback'],
            'submitted_at' => $row['submitted_at'],
            'user_id' => $row['user_id'] ? (int)$row['user_id'] : null
        ];
    }
    $response = ['status' => 'success', 'data' => $feedbackData];
    http_response_code(200);
    pg_free_result($queryResult);
}else{
    $response = ['status' => 'error', 'message' => 'Falied to fetch feedback'];
    http_response_code(500);

}

pg_close($conn);
echo json_encode($response);
