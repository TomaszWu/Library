<?php

class Book implements JsonSerializable {

    private $id;
    private $title;
    private $author;
    private $descripion;

    public function __construct() {
        $this->id = -1;
        $this->title = '';
        $this->author = '';
        $this->description = '';
    }

//    public static function addANewBookToTheDB(mysqli $conn) {
//        if ($this - id == -1) {
//            $query = "INSERT INTO books (title, author, description)
//                      VALUES ('.$connection->real_escape_string($this->title)', 
//                    .$connection->real_escape_string($this->author)', '.$connection->real_escape_string($this->description)')";
//        }
//        if($this->query($query)){
//            $this->id = $connection->insert_id;
//            return true;
//        } else {
//            return false;
//        }
//    }

    
    
    // id nie podane zwróci allBooks a podane pojedyńczą książkę
    public static function loadFromDB(mysqli $conn, $id = null) {
        if (is_null($id)) {
            //pobieramy wszystkei książki 
            $result = $conn->query('SELECT * FROM books');
        } else {

            $result = $conn->query("SELECT * FROM books WHERE id='" . intval($id) . "'");
        }
        $bookList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbBook = new Book();
                $dbBook->id = $row['id'];
                $dbBook->author = $row['author'];
                $dbBook->title = $row['title'];
                $dbBook->description = $row['description'];
                // nie trzeba zmieniać zmennych, mogą być private bo jesteśmy w środku.
                $bookList[] = json_encode($dbBook); // bez interfejsu tak nie działa!!!!!
            }
        }

        return $bookList;
    }

    public function jsonSerialize() {
// funckja zwraca na dane z obiektu do json_encode
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description
        ];
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getTitle() {
        return $this->title;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function getAuthor() {
        return $this->author;
    }

    function setAuthor($author) {
        $this->author = $author;
    }

    function getDescripion() {
        return $this->descripion;
    }

    function setDescripion($descripion) {
        $this->descripion = $descripion;
    }

}
