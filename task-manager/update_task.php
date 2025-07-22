<?php
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $title = $_POST['task'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    if (empty($id) || empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Task ID and title are required']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $status, $id);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'id' => $id,
            'title' => htmlspecialchars($title),
            'description' => htmlspecialchars($description),
            'status' => $status
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
    }
    $stmt->close();
}
?>