<?php

class Book implements JsonSerializable {

    private $id;
    private $title;
    private $author;
    private $description;

    public function __construct() {
        $this->id = -1;
        $this->title = '';
        $this->author = '';
        $this->description = '';
    }

    public function addANewBookToTheDB(mysqli $conn) {
        if ($this -> id == -1) {
            $query = "INSERT INTO books (title, author, description)
                      VALUES ('$this->title', 
                    '$this->author', '$this->description')";
        }
        if($conn->query($query)){
            $this->id = $conn->insert_id;
            return true;
         } else {
            return false;
        }
    }

    // id nie podane zwróci allBooks a podane pojedyńczą książkę
    public static function loadFromDB(mysqli $conn, $id = null) {
        if (is_null($id)) {
            //pobieramy wszystkei książki 
            $result = $conn->query('SELECT * FROM books ORDER BY id DESC');
        } elseif ($id == 'lastBookAdded') {

            $result = $conn->query('SELECT * FROM books ORDER BY id DESC LIMIT 1');
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

    public static function deleteTheBook(mysqli $conn, $id) {
        if ($result = $conn->query("DELETE FROM books WHERE id='" . $id . "'")) {
            return true;
        } else {
            return false;
        }
    }

    public static function changeTheBook(mysqli $conn, $id, $title = null, $description = null) {
        
            if(!is_null($title) && !is_null($description)){
            $query = "UPDATE books SET title='" . $title . "', description='" . $description . "' WHERE id='" . $id . "'";
            } elseif ($title){
                $query = "UPDATE books SET title='" . $title . "'  WHERE id='" . $id . "'";
            } else {
                $query = "UPDATE books SET description='" . $description . "'  WHERE id='" . $id . "'";
            }
            
            if ($conn->query($query)) {
                return true;
            } else {
                return false;
            }
//        if ($description != 0) {
//            $query = "UPDATE books SET title='" . $description . "' WHERE id='" . $id . "'";
//           if ($conn->query($query)) {
//                return true;
//            } else {
//                return false;
//            }
//        }
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

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

}
