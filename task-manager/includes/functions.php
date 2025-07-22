<?php
include 'db.php';

function getTasks($conn) {
    $sql = "SELECT * FROM tasks ORDER BY position ASC";
    $result = $conn->query($sql);
    return $result;
}

function addTask($conn, $title, $description, $status = 'todo', $position) {
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, position) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $status, $position);
    return $stmt->execute();
}

function deleteTask($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function updateTask($conn, $id, $status = null, $title = null, $position = null, $description = null) {
    if ($position !== null) {
        $stmt = $conn->prepare("UPDATE tasks SET position = ? WHERE id = ?");
        $stmt->bind_param("ii", $position, $id);
    } elseif ($status !== null && $title !== null && $description !== null) {
        $stmt = $conn->prepare("UPDATE tasks SET status = ?, title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status, $title, $description, $id);
    } elseif ($status !== null) {
        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    } elseif ($title !== null && $description !== null) {
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    return $stmt->execute();
}
?>