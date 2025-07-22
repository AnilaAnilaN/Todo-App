window.updateEmptyState = function() {
    const addTaskButton = document.getElementById('addTaskButton');
    const taskList = document.getElementById('taskList');
    const emptyMessage = document.querySelector('.text-center.text-muted');
    const visibleTasks = taskList ? Array.from(taskList.getElementsByClassName('task-item')).filter(task => task.style.display !== 'none').length : 0;
    if (visibleTasks > 0) {
        if (emptyMessage) emptyMessage.style.display = 'none';
    } else {
        if (emptyMessage) emptyMessage.style.display = 'block';
        if (addTaskButton) addTaskButton.style.display = 'block';
    }
};

window.filterTasks = function() {
    const filterStatus = document.getElementById('filterStatus');
    const taskList = document.getElementById('taskList');
    const status = filterStatus.value;
    const tasks = taskList ? taskList.getElementsByClassName('task-item') : [];
    for (let task of tasks) {
        const taskStatus = task.dataset.status;
        task.style.display = status === 'all' || taskStatus === status ? 'block' : 'none';
    }
    window.updateEmptyState();
};

document.addEventListener('DOMContentLoaded', () => {
    const taskList = document.getElementById('taskList');
    const taskModal = document.getElementById('taskModal');
    const taskForm = document.getElementById('taskForm');
    const modalInstance = new bootstrap.Modal(taskModal);

    function initializeSortable() {
        if (taskList) {
            new Sortable(taskList, {
                animation: 150,
                handle: '.task-item',
                onEnd: async (evt) => {
                    const taskIds = Array.from(taskList.children).map(item => item.dataset.id);
                    try {
                        const response = await fetch('update_position.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `positions=${JSON.stringify(taskIds)}`
                        });
                        const result = await response.json();
                        if (result.status !== 'success') {
                            console.error('Failed to update task order');
                        }
                    } catch (error) {
                        console.error('Error updating task order:', error);
                    }
                }
            });
        }
    }

    initializeSortable();
    window.updateEmptyState();

    // Reset modal for adding new task
    taskModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const modalTitle = taskModal.querySelector('#taskModalLabel');
        const taskIdInput = taskForm.querySelector('#taskId');
        const taskInput = taskForm.querySelector('#task');
        const descriptionInput = taskForm.querySelector('#description');
        const statusSelect = taskForm.querySelector('#status');

        if (button.id === 'addTaskButton') {
            modalTitle.textContent = 'Add New Task';
            taskIdInput.value = '';
            taskInput.value = '';
            descriptionInput.value = '';
            statusSelect.value = 'pending';
            statusSelect.dispatchEvent(new Event('change')); // Trigger change to render selection
        }
    });

    document.querySelectorAll('.view-description').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const taskId = link.getAttribute('data-task-id');
            const description = document.getElementById(`description-${taskId}`);
            if (description.style.display === 'none' || description.style.display === '') {
                description.style.display = 'block';
                link.textContent = 'Hide Description';
            } else {
                description.style.display = 'none';
                link.textContent = 'View Description';
            }
        });
    });

    taskForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const taskId = document.getElementById('taskId').value;
        const formData = new FormData(taskForm);
        const url = taskId ? 'update_task.php' : 'add_task.php';
        formData.append('id', taskId || '');

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            if (response.ok) {
                modalInstance.hide();
                location.reload();
            } else {
                console.error('Failed to save task');
            }
        } catch (error) {
            console.error('Error saving task:', error);
        }
    });
});

async function deleteTask(id) {
    try {
        const response = await fetch('delete_task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
        const result = await response.json();
        if (result.status === 'success') {
            const task = document.getElementById(`task-${id}`);
            task.style.opacity = '0';
            setTimeout(() => {
                task.remove();
                window.updateEmptyState();
            }, 200);
        } else {
            console.error('Failed to delete task');
        }
    } catch (error) {
        console.error('Error deleting task:', error);
    }
}

function editTask(id) {
    const task = document.getElementById(`task-${id}`);
    const title = task.querySelector('.task-title').textContent;
    const description = task.querySelector('.task-description').textContent === 'No description' ? '' : task.querySelector('.task-description').textContent;
    const status = task.dataset.status;

    document.getElementById('taskId').value = id;
    document.getElementById('taskModalLabel').textContent = 'Edit Task';
    document.getElementById('task').value = title;
    document.getElementById('description').value = description;
    document.getElementById('status').value = status;
    document.getElementById('status').dispatchEvent(new Event('change')); // Trigger change to render selection
    new bootstrap.Modal(document.getElementById('taskModal')).show();
}