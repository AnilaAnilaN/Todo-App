<?php
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $positions = json_decode($_POST['positions'], true);

    if ($positions && is_array($positions)) {
        $stmt = $conn->prepare("UPDATE tasks SET position = ? WHERE id = ?");
        foreach ($positions as $pos => $id) {
            $stmt->bind_param("ii", $pos, $id);
            $stmt->execute();
        }
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>