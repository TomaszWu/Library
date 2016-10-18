<?php

$dir = dirname(__FILE__); //zwraca aktualny katalog

include($dir . '/src/Db.php');
include($dir . '/src/Book.php');

// połączenie
$conn = DB::connect();

// plik zawsze zwraca JSONa
//header('Content-Type: application/json');

//
$id = 4;
$newAuthor = 'rower12345;';
$newDescription = 'rower12345';

$changedBook = Book::loadFromDB($conn, 'lastBookAdded');
var_dump(Book::changeTheBook($conn, $id, $newAuthor, $newDescription));
var_dump($changedBook);


//$newBook = new Book();
//$newBook->setTitle('123321');
//$newBook->setAuthor('123321');
//$newBook->setDescription('123312');
//var_dump($newBook);
//var_dump($newBook->addANewBookToTheDB($conn));
