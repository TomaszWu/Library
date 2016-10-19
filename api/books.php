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
        $newBook->addANewBookToTheDB($conn);
        $newBookAdded = Book::loadFromDB($conn, 'lastBookAdded');
    }
    echo json_encode($newBookAdded);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    if (isset($put_vars['id'])  &&
            (isset($put_vars['newAuthor']) && strlen($put_vars['newAuthor']) > 0 && strlen($put_vars['newAuthor']) < 100) 
            || (isset($put_vars['newDescription']) && strlen($put_vars['newDescription']) > 0 && strlen($put_vars['newDescription']) < 100 )){


        $id = $put_vars['id'];
        $newAuthor = $put_vars['newAuthor'];
        $newDescription = $put_vars['newDescription'];
        Book::changeTheBook($conn, $put_vars['id'], $put_vars['newAuthor'], $put_vars['newDescription']);

        $changedBook = Book::loadFromDB($conn, $put_vars['id']);
        echo json_encode($changedBook);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $put_vars);
    if (isset($put_vars['id'])) {
        $book = Book::loadFromDB($conn, $put_vars['id']);
        $bookToDelete = json_decode($book[0], true);
        Book::deleteTheBook($conn, $bookToDelete['id']);
    }
    $confirmationDelete = ['statusToConfirm' => 'Ksiazka skasowana'];
    echo json_encode($confirmationDelete);
}



