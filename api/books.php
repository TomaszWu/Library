<?php

$dir = dirname(__FILE__); //zwraca aktualny katalog

include($dir . '/src/Db.php');
include($dir . '/src/Book.php');

// połączenie
$conn = DB::connect();

// plik zawsze zwraca JSONa
header('Content-Type: application/json');

//sprawdzamy w jaki sposób (typ) łączył się JS




if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && intval($_GET['id']) > 0) {
        // sprawdzamy czy przesłane jest id pojedyńczej książki
        $books = Book::loadFromDB($conn, $_GET['id']);
        // tablia tablicę 1 element
    } else {
        // pobieramy wszystkie książki
        $books = Book::loadFromDB($conn);
    }
    echo json_encode($books);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // zgodnie z rest POST dodaje dane
    if (isset($_POST['title']) && strlen($_POST['title']) > 0 && strlen($_POST['title']) < 100 &&
            isset($_POST['author']) && strlen($_POST['author']) > 0 && strlen($_POST['author']) < 100 &&
            isset($_POST['description']) && strlen($_POST['description']) > 0 && strlen($_POST['description']) < 10000) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $description = $_POST['description'];
        $newBook = new Book();
        $newBook->setTitle($title);
        $newBook->setAuthor($author);
        $newBook->setDescription($description);
        $newBook->addABookToTheDB($conn);

        $newBookAdded = Book::loadFromDB($conn, -10);
        echo json_encode($newBookAdded);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parset_str(file_get_contents('php://input'), $put_vars);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $put_vars);
    if (isset($put_vars['id'])) {
        $book = Book::loadFromDB($conn, $put_vars['id']);
        $bookToDelete = json_decode($book[0], true);
        var_dump($bookToDelete['id']);
        Book::deleteTheBook($conn, $bookToDelete['id']);
    }
    $confirmationDelete = ['status' => 'Książka skasowana'];
    echo json_encode($confirmationDelete);
}



