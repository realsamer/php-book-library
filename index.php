<?php
session_start();

// Project: PHP Book Library
// Phase 8: Add book deletion feature

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', false);
}

function generateNewBookId($books)
{
    $maxId = 0;

    foreach ($books as $book) {
        if ((int)$book["id"] > $maxId) {
            $maxId = (int)$book["id"];
        }
    }

    return $maxId + 1;
}

function validateBookData($data, $genres)
{
    $errors = [];
    $currentYear = (int)date("Y");

    if ($data["title"] === "") {
        $errors["title"] = "Title is required.";
    } elseif (strlen($data["title"]) < 3 || strlen($data["title"]) > 120) {
        $errors["title"] = "Title must be between 3 and 120 characters.";
    }

    if ($data["author"] === "") {
        $errors["author"] = "Author is required.";
    } else {
        $authorWords = preg_split('/\s+/', $data["author"], -1, PREG_SPLIT_NO_EMPTY);

        if (count($authorWords) < 2) {
            $errors["author"] = "Author must contain at least two words.";
        }
    }

    if ($data["genre"] === "") {
        $errors["genre"] = "Genre is required.";
    } elseif (!in_array($data["genre"], $genres)) {
        $errors["genre"] = "Please select a valid genre.";
    }

    if ($data["year"] === "") {
        $errors["year"] = "Year is required.";
    } elseif (!preg_match('/^\d{4}$/', $data["year"])) {
        $errors["year"] = "Year must be a 4-digit number.";
    } elseif ((int)$data["year"] < 1000 || (int)$data["year"] > $currentYear) {
        $errors["year"] = "Year must be between 1000 and " . $currentYear . ".";
    }

    if ($data["pages"] === "") {
        $errors["pages"] = "Pages is required.";
    } elseif (!ctype_digit($data["pages"]) || (int)$data["pages"] <= 0) {
        $errors["pages"] = "Pages must be a positive integer greater than 0.";
    }

    return $errors;
}

$genres = ["Fiction", "Non-Fiction", "Science", "History", "Biography", "Technology"];

$defaultBooks = [
    [
        "id" => 1,
        "title" => "The Silent Patient",
        "author" => "Alex Michaelides",
        "genre" => "Fiction",
        "year" => 2019,
        "pages" => 336
    ],
    [
        "id" => 2,
        "title" => "A Brief History of Time",
        "author" => "Stephen Hawking",
        "genre" => "Science",
        "year" => 1988,
        "pages" => 256
    ],
    [
        "id" => 3,
        "title" => "Clean Code",
        "author" => "Robert Martin",
        "genre" => "Technology",
        "year" => 2008,
        "pages" => 464
    ]
];

if (!isset($_SESSION["books"])) {
    $_SESSION["books"] = $defaultBooks;
}

$books = $_SESSION["books"];
$errors = [];

$submittedData = [
    "title" => "",
    "author" => "",
    "genre" => "",
    "year" => "",
    "pages" => ""
];

$isEditMode = false;
$editId = null;

// Detect edit mode using ?edit_id=
if (isset($_GET["edit_id"])) {
    $editId = (int)$_GET["edit_id"];

    foreach ($books as $book) {
        if ((int)$book["id"] === $editId) {
            $isEditMode = true;
            $submittedData = [
                "title" => $book["title"],
                "author" => $book["author"],
                "genre" => $book["genre"],
                "year" => $book["year"],
                "pages" => $book["pages"]
            ];
            break;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    // Delete book request
    if ($action === "delete_book") {
        $deleteId = (int)($_POST["book_id"] ?? 0);

        $books = array_filter($books, function ($book) use ($deleteId) {
            return (int)$book["id"] !== $deleteId;
        });

        $books = array_values($books);
        $_SESSION["books"] = $books;
        $_SESSION["success"] = "Book deleted successfully.";

        header("Location: index.php");
        exit;
    }

    // Add or update book request
    if ($action === "add_book" || $action === "update_book") {
        $submittedData = [
            "title" => htmlspecialchars(trim($_POST["title"] ?? ""), ENT_QUOTES, 'UTF-8'),
            "author" => htmlspecialchars(trim($_POST["author"] ?? ""), ENT_QUOTES, 'UTF-8'),
            "genre" => htmlspecialchars(trim($_POST["genre"] ?? ""), ENT_QUOTES, 'UTF-8'),
            "year" => htmlspecialchars(trim($_POST["year"] ?? ""), ENT_QUOTES, 'UTF-8'),
            "pages" => htmlspecialchars(trim($_POST["pages"] ?? ""), ENT_QUOTES, 'UTF-8')
        ];

        $errors = validateBookData($submittedData, $genres);

        if ($action === "update_book") {
            $isEditMode = true;
            $editId = (int)($_POST["book_id"] ?? 0);
        }

        if (empty($errors)) {
            if ($action === "add_book") {
                $newBook = [
                    "id" => generateNewBookId($books),
                    "title" => $submittedData["title"],
                    "author" => $submittedData["author"],
                    "genre" => $submittedData["genre"],
                    "year" => (int)$submittedData["year"],
                    "pages" => (int)$submittedData["pages"]
                ];

                $books[] = $newBook;
                $_SESSION["books"] = $books;
                $_SESSION["success"] = "Book added successfully.";

                header("Location: index.php");
                exit;
            }

            if ($action === "update_book") {
                foreach ($books as $index => $book) {
                    if ((int)$book["id"] === $editId) {
                        $books[$index] = [
                            "id" => $editId,
                            "title" => $submittedData["title"],
                            "author" => $submittedData["author"],
                            "genre" => $submittedData["genre"],
                            "year" => (int)$submittedData["year"],
                            "pages" => (int)$submittedData["pages"]
                        ];
                        break;
                    }
                }

                $_SESSION["books"] = $books;
                $_SESSION["success"] = "Book updated successfully.";

                header("Location: index.php");
                exit;
            }
        }
    }
}

$successMessage = "";

if (isset($_SESSION["success"])) {
    $successMessage = $_SESSION["success"];
    unset($_SESSION["success"]);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP Book Library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="mb-4 text-center">
            <h1 class="fw-bold">Personal Book Library</h1>
            <p class="text-muted">Browse and manage your book collection.</p>
        </div>

        <?php if ($successMessage !== ""): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= h($successMessage); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <?= $isEditMode ? "Edit Book" : "Add New Book"; ?>
                    </div>

                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            Please fix the errors below and try again.
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php<?= $isEditMode ? '?edit_id=' . h($editId) : ''; ?>">
                            <input type="hidden" name="action" value="<?= $isEditMode ? 'update_book' : 'add_book'; ?>">

                            <?php if ($isEditMode): ?>
                            <input type="hidden" name="book_id" value="<?= h($editId); ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control <?= isset($errors["title"]) ? 'is-invalid' : ''; ?>"
                                    value="<?= h($submittedData["title"]); ?>">
                                <?php if (isset($errors["title"])): ?>
                                <div class="invalid-feedback">
                                    <?= h($errors["title"]); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" name="author" id="author"
                                    class="form-control <?= isset($errors["author"]) ? 'is-invalid' : ''; ?>"
                                    value="<?= h($submittedData["author"]); ?>">
                                <?php if (isset($errors["author"])): ?>
                                <div class="invalid-feedback">
                                    <?= h($errors["author"]); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="genre" class="form-label">Genre</label>
                                <select name="genre" id="genre"
                                    class="form-select form-control <?= isset($errors["genre"]) ? 'is-invalid' : ''; ?>">
                                    <option value="">Select genre</option>

                                    <?php foreach ($genres as $genre): ?>
                                    <option value="<?= h($genre); ?>"
                                        <?= $submittedData["genre"] === $genre ? 'selected' : ''; ?>>
                                        <?= h($genre); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if (isset($errors["genre"])): ?>
                                <div class="invalid-feedback">
                                    <?= h($errors["genre"]); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="text" name="year" id="year"
                                    class="form-control <?= isset($errors["year"]) ? 'is-invalid' : ''; ?>"
                                    value="<?= h($submittedData["year"]); ?>">
                                <?php if (isset($errors["year"])): ?>
                                <div class="invalid-feedback">
                                    <?= h($errors["year"]); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="pages" class="form-label">Pages</label>
                                <input type="text" name="pages" id="pages"
                                    class="form-control <?= isset($errors["pages"]) ? 'is-invalid' : ''; ?>"
                                    value="<?= h($submittedData["pages"]); ?>">
                                <?php if (isset($errors["pages"])): ?>
                                <div class="invalid-feedback">
                                    <?= h($errors["pages"]); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <?= $isEditMode ? "Update Book" : "Add Book"; ?>
                            </button>

                            <?php if ($isEditMode): ?>
                            <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancel Edit</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        Book List
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Year</th>
                                        <th>Pages</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?= h($book["id"]); ?></td>
                                        <td><?= h($book["title"]); ?></td>
                                        <td><?= h($book["author"]); ?></td>
                                        <td><?= h($book["genre"]); ?></td>
                                        <td><?= h((int)$book["year"]); ?></td>
                                        <td><?= h($book["pages"]); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="index.php?edit_id=<?= h($book["id"]); ?>"
                                                    class="btn btn-sm btn-warning">
                                                    Edit
                                                </a>

                                                <form method="POST" action="index.php">
                                                    <input type="hidden" name="action" value="delete_book">
                                                    <input type="hidden" name="book_id" value="<?= h($book["id"]); ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="text-muted small mb-0">
                            Total books: <?= h(count($books)); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>