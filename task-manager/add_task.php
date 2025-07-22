<?php
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['task'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    if (empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Task title is required']);
        exit;
    }

    // Get the highest position value
    $result = $conn->query("SELECT MAX(position) as max_pos FROM tasks");
    $row = $result->fetch_assoc();
    $position = ($row['max_pos'] ?? 0) + 1;

    if (addTask($conn, $title, $description, $status, $position)) {
        echo json_encode([
            'status' => 'success',
            'id' => $conn->insert_id,
            'title' => htmlspecialchars($title),
            'description' => htmlspecialchars($description),
            'status' => $status,
            'position' => $position
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add task']);
    }
}
?>