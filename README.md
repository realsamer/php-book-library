# PHP Book Library

A single-file PHP web application for managing a personal book library.

The application allows users to browse books, add new books through a validated form, edit existing books, delete books with confirmation, search by title or author, and sort the book table.

## Project Information

- **Course:** Web Programming 2 - Lab
- **Assignment:** Assignment 2 - PHP Arrays and Forms
- **Instructor:** Mohammed Zoqlam
- **Repository Name:** php-book-library

## Features

- Display books using a multi-dimensional PHP array
- Store each book as an associative array with the following keys:
  - `id`
  - `title`
  - `author`
  - `genre`
  - `year`
  - `pages`
- Add new books using a validated form
- Validate all form fields with field-specific error messages
- Re-populate form data after failed validation
- Generate new book IDs using the maximum existing ID plus one
- Show success messages using PHP sessions
- Prevent duplicate submissions using redirect after successful POST requests
- Edit books using the `edit_id` query parameter
- Delete books using a POST request
- Confirm delete actions with a Bootstrap modal
- Search books by title or author
- Sort the table by clicking column headers
- Escape user output using `htmlspecialchars()`

## Technologies Used

- PHP
- HTML5
- Bootstrap 5 CDN
- Git
- GitHub

## Validation Rules

| Field  | Validation Rule                                                       |
| ------ | --------------------------------------------------------------------- |
| Title  | Required, must be between 3 and 120 characters                        |
| Author | Required, must contain at least two words                             |
| Genre  | Required, must exist in the allowed genres array                      |
| Year   | Required, must be a 4-digit integer between 1000 and the current year |
| Pages  | Required, must be a positive integer greater than 0                   |

## Allowed Genres

```php
["Fiction", "Non-Fiction", "Science", "History", "Biography", "Technology"]
```

## Project Structure

```text
php-book-library/
├── index.php
├── repository-link.txt
└── README.md
```

## How to Run the Project

1. Clone the repository:

```bash
git clone https://github.com/realsamer/php-book-library.git
```

2. Open the project folder:

```bash
cd php-book-library
```

3. Start a local PHP development server:

```bash
php -S localhost:8000
```

4. Open the project in your browser:

```text
http://localhost:8000
```

## Git Workflow

This project was built phase by phase using Git commits to show the development process clearly.

Main development phases included:

1. Project initialization
2. Initial books and genres arrays
3. Bootstrap layout and book table
4. Book submission form
5. Form validation and error feedback
6. Successful book creation
7. Validation refactoring
8. Book editing feature
9. Book deletion feature
10. Bootstrap delete confirmation modal
11. Search and sorting features
12. Repository documentation

## Notes

- The application is written in a single PHP file named `index.php`.
- Bootstrap is loaded using CDN links.
- PHP sessions are used to keep book data available during the browser session.
- The final assignment submission should include only `index.php` and a plain text file containing the GitHub repository link.
