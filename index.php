<?php
session_start();

// Project: PHP Book Library
// Phase 3: Add the book submission form

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$genres = ["Fiction", "Non-Fiction", "Science", "History", "Biography", "Technology"];

$books = [
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

$submittedData = [
    "title" => "",
    "author" => "",
    "genre" => "",
    "year" => "",
    "pages" => ""
];

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

        <div class="row g-4">
            <!-- Book form column -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Add New Book
                    </div>

                    <div class="card-body">
                        <form method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="<?= h($submittedData["title"]); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" name="author" id="author" class="form-control"
                                    value="<?= h($submittedData["author"]); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="genre" class="form-label">Genre</label>
                                <select name="genre" id="genre" class="form-select form-control">
                                    <option value="">Select genre</option>

                                    <?php foreach ($genres as $genre): ?>
                                    <option value="<?= h($genre); ?>">
                                        <?= h($genre); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="text" name="year" id="year" class="form-control"
                                    value="<?= h($submittedData["year"]); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="pages" class="form-label">Pages</label>
                                <input type="text" name="pages" id="pages" class="form-control"
                                    value="<?= h($submittedData["pages"]); ?>">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Add Book
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Book table column -->
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