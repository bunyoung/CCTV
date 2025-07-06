<?php
// save_schedule.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        !isset($data['start_time'], $data['end_time'], $data['mode']) ||
        empty($data['start_time']) || empty($data['end_time']) || empty($data['mode'])
    ) {
        http_response_code(400);
        echo json_encode(['status' => 'invalid', 'message' => 'Missing or empty parameters']);
        exit;
    }

    $data['saved_at'] = date('Y-m-d H:i:s');
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);

    $result = file_put_contents('storage/schedule_data.json', $jsonData);

    if ($result === false) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to write file']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Schedule saved successfully']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
