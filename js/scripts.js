$(function () {
    // pobieramy WSZYSTKEI książki
    $.ajax({
        url: 'api/books.php',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            //w result jest lista ksiazek
            for (var i = 0; i < result.length; i++) {
                //obiekt js z pojed ksiązką
                var book = JSON.parse(result[i]);
                var bookDiv = $('<div>');
                bookDiv.addClass('singleBook');
                bookDiv.html('<h3 data-id="' + book.id + '">' + book.title + '</h3><div class="description"></div>');
                var newLink = $('<a href>');
                newLink.html('Skasuj książkę');
                newLink.addClass('delete');
                bookDiv.append(newLink);
                //wpinamy książke na stronę
                $('#bookList').append(bookDiv);
            }
        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    });
    var allBooks = $('#bookList');
    $(document).on('click', '.singleBook', function () {
        for (var i = 0; i < allBooks.length; i++) {
            var dataToGet = ($(this).find('h3').data('id'));
            var h3ToAddData = $(this).find('.description');
            $.ajax({
                url: 'api/books.php',
                type: 'GET',
                data: {id: dataToGet},
                dataType: 'json',
                success: function (result) {
                    var book = JSON.parse(result);
                    h3ToAddData.html(book.description);
                },
                error: function () {
                    console.log('Wystąpił błąd');
                }
            })
        }
    });

    $('#addANewBook').on('click', function () {
        var newTitle = $('#title').val();
        var newAuthor = $('#author').val();
        var newDescription = $('#description').val();
        $.ajax({
            url: 'api/books.php',
            type: 'POST',
            data: {title: newTitle, author: newAuthor, description: newDescription},
            dataType: 'json'
        })
                .done(function (result) {
                    var newestBook = JSON.parse(result[0]);
                    console.log(newestBook.id);
                    var newBook = $('<div>');
                    newBook.addClass('singleBook');
                    newBook.html('<h3 data-id="' + newestBook.id + '">' + newTitle + '</h3><div class="description"></div>');
                    $('#bookList').prepend(newBook);
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });
        event.preventDefault();
    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var idToDelete = $(this).siblings('h3').data('id');
        var divToDelete = $(this).parent().remove();
        $.ajax({
            url: 'api/books.php',
            type: 'DELETE',
            data: {id: idToDelete},
            dataType: 'json'
        })
        
            // tutaj to nie zwraca JSONa. Analogiczny zapis w POST działał. 
        
                .done(function (result) {
                    console.log(result['status']);
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });
    })

});

