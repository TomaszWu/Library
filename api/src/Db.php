<?php

class Db {

    static private $conn = null; // wlasnosc przechowujaca polaczenie

    static public function connect() {
        if (!is_null(self::$conn)) {
            // połączenie istnieje
            return self::$conn;
        } else {
            self::$conn = new mysqli('localhost', 'root', 'coderslab', 'Warsztat3');

            self::$conn->set_charset('utf8');
            if (self::$conn->connect_error) {
                die('Connection error: ' . self::$conn->connection_error);
            }
        }
        // zwracam połączenie
        return self::$conn;
    }

    static public function disconnect() {
        self::$conn->close();
        self::$conn = null;

        return true;
    }

}
