<html>
    <head>
        <title>Books Shelf</title>
        <meta charset="utf-8">  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="js/scripts.js"></script>
    </head>
    <body>
        <form>
            <label>Tytuł</label>
            <input type="text" id="title">
            <label>Autor</label>
            <input type="text" id="author">
            <label>Opis</label>
            <input type="text" id="description">
            <br>
            <button id="addANewBook">Dodaj nową książkę</button>
        </form>
        
        
        <div id="bookList">
            <!-- tutaj będizemy dodawać ksiazki z bazy ajaxem -->
        </div>
    </body>
</html>