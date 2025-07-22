# Task Manager Web App

A simple, responsive Task Manager web application built with PHP, MySQL, HTML, CSS, and JavaScript. Users can create, edit, delete, and filter tasks by status ("Pending", "In Progress", "Done") with drag-and-drop sorting.

## Features
- Create, edit, and delete tasks with title, description, and status.
- Filter tasks by status.
- Drag-and-drop to reorder tasks.
- Responsive design using Bootstrap.
- Status-based styling (Pending: purple, In Progress: yellow, Done: blue).

- 
## Setup Instructions
1. **Prerequisites**:
   - PHP 8.0+
   - MySQL
   - Web server (e.g., Apache)
   - Git

2. **Clone the Repository**:
   git clone https://github.com/AnilaAnilaN/Todo-App.git
   cd task-manager

3. **Set Up the Database**:
Create a MySQL database named todo_list.
Run the SQL script to create the tasks table and insert sample data
mysql -u [username] -p todo_list < create_todo_list.sql

4. **Configure Database Connection**:
Edit includes/functions.php to set your MySQL credentials:
$conn = new mysqli('localhost', '[username]', '[password]', 'todo_list');
