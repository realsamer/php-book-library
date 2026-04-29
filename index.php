<?php
session_start();

// Project: PHP Book Library
// Phase 1: Add initial books and genres arrays

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP Book Library</title>
</head>

<body>

    <h1>PHP Book Library</h1>

    <p>Initial books and genres arrays have been created.</p>

</body>

</html>