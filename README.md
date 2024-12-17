# Todo List Application

This is a simple Todo List application built with PHP and MySQL. It allows users to manage their tasks efficiently by adding, updating, and deleting tasks.

## Features

- **User Authentication**: Secure login and registration system to manage user access.
- **Add Task**: Users can add new tasks to their todo list with a task name, description, and status.
- **Update Task**: Users can update existing tasks to change the task name, description, and status.
- **Delete Task**: Users can delete tasks that are no longer needed.
- **Task Status**: Tasks can have different statuses such as Pending, In Progress, and Completed.
- **Responsive Design**: The application uses Bootstrap for a responsive and modern design.
- **Pagination**: Tasks are displayed with pagination to handle large lists efficiently.
- **Task Count Widgets**: Visual widgets to show the count of tasks by status (Pending, In Progress, Completed).
- **Session Management**: Secure session management to ensure user data is protected.

## Requirements

- PHP 7.0 or higher
- MySQL

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/Todo-list-with-php.git
    ```

2. Navigate to the project directory:
    ```sh
    cd Todo-list-with-php
    ```

3. Import the database:
    ```sh
    mysql -u username -p database_name < database.sql
    ```

4. Configure the database connection in `db.php`.

## Usage

1. Start the PHP server:
    ```sh
    php -S localhost:8000
    ```

2. Open your browser and go to `http://localhost:8000`.

## Seeding the Database

To seed the database with demo data, follow these steps:

1. Log in to the application.
2. Access the `db_seed.php` script by navigating to `http://localhost:8000/db_seed.php`.
3. The database will be seeded with demo data, and you will be redirected to the index page.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## Credits

This application was designed and developed by:
- Asmaa El-Naggar
- Loka Shafik
- Hesham Yehia

Special thanks to Dr. Reham for her guidance and support throughout the project.

## License

This project is licensed under the GPL-3.0 License. See the [LICENSE](LICENSE) file for details.