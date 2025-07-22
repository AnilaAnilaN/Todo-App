<?php
include 'includes/functions.php';
include 'templates/header.php';

$tasks = getTasks($conn);
$taskCount = $tasks->num_rows;
?>

<div class="container py-4">
    <button id="addTaskButton" class="btn btn-primary btn-md mb-4 mx-auto d-block" data-bs-toggle="modal" data-bs-target="#taskModal">Add Task</button>

    <?php if ($taskCount === 0): ?>
        <div class="text-center text-muted">
            <p class="fs-4">No tasks yet! Click the button above to add a task.</p>
        </div>
    <?php else: ?>
        <div class="filter-container mb-4 text-center">
            <label for="filterStatus" class="form-label me-2">Filter by Status:</label>
            <select id="filterStatus" class="form-select d-inline-block w-auto" onchange="filterTasks()">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>
        <ul class="list-group" id="taskList">
            <?php while($task = $tasks->fetch_assoc()): ?>
                <?php
                $status = strtolower(trim($task['status']));
                $badgeClass = $status === 'pending' ? 'bg-purple' : ($status === 'in_progress' ? 'bg-warning' : 'bg-success');
                $displayStatus = ucfirst($status);
                ?>
                <li class="list-group-item task-item <?php echo $status; ?> rounded shadow-sm mb-3 mx-auto" id="task-<?php echo $task['id']; ?>" data-id="<?php echo $task['id']; ?>" data-status="<?php echo $status; ?>" draggable="true">
                    <div class="d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="task-title fs-5 fw-bold me-2"><?php echo htmlspecialchars($task['title']); ?></span>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $displayStatus; ?></span>
                            </div>
                            <div class="task-actions">
                                <a href="#" class="view-description me-2" data-task-id="<?php echo $task['id']; ?>">View Description</a>
                                <button class="btn btn-sm btn-warning btn-edit me-2" onclick="editTask(<?php echo $task['id']; ?>)">Edit</button>
                                <button class="btn btn-sm btn-danger btn-delete" onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
                            </div>
                        </div>
                        <p class="task-description text-muted mb-0" style="display: none;" id="description-<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['description'] ?? 'No description'); ?></p>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm" action="add_task.php" method="POST">
                        <input type="hidden" id="taskId" name="taskId">
                        <div class="mb-3">
                            <label for="task" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="task" name="task" placeholder="Enter task title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter task description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" selected>Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>