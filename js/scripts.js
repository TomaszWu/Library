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
                bookDiv.append(addDeleteButton());
                //wpinamy książke na stronę
                $('#bookList').append(bookDiv);
            }
        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    });
    var allBooks = $('#bookList');
    $(document).on('click', 'h3:first-child', function (event) {
        for (var i = 0; i < allBooks.length; i++) {
            var dataToGet = ($(this).data('id'));
            var divToAddData = $(this).siblings('.description');
            console.log(divToAddData);
            var divToadd = $(this).parent();
            var form = $('<form>').addClass('formToMakeChange');
            var labelTitle = $('<label>');
            labelTitle.html('Zmień tytuł');
            var changeTitle = $('<input>').attr('id', 'changeTitle');
            var br = $('<br>');
            var br2 = $('<br>');
            var labelDescription = $('<label>');
            labelDescription.html('Zmień opis');
            var changeDescription = $('<input>').attr('id', 'changeDescription');
            var submitChangesHref = $('<a href>').addClass('.submitChanges').html('wprowadź zmiany');
            var submitChangesHref = $('<a href>').addClass('.submitChanges').html('wprowadź zmiany');
            $.ajax({
                url: 'api/books.php',
                type: 'GET',
                data: {id: dataToGet},
                dataType: 'json',
                success: function (result) {
                    var book = JSON.parse(result);
                    var content = divToAddData.html();
                    if (content != book.description) {
                        divToAddData.html(book.description);
                        form.append(labelTitle);
                        form.append(changeTitle);
                        form.append(br);
                        form.append(labelDescription);
                        form.append(changeDescription);
                        form.append(br2);
                        form.append(submitChangesHref);
                        form.insertAfter(divToAddData);
                    }
                },
                error: function () {
                    console.log('Wystąpił błąd');
                }
            })
        }


        $(document).on('click', '.singleBook form a', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var changeTitle = $(this).parent().find('#changeTitle').val();
            var changeDescription = $(this).parent().find('#changeDescription').val();
            if (changeTitle.lenght == 0 || changeDescription == 0) {
                alert('Proszę wprowadzić poprawne dane!');
                event.preventDefault();
                event.stopPropagation();
            }
            var idToChange = $(this).parent().siblings('h3').data('id');
            var titleToBeChanged = $(this).parent().siblings('h3');
            var descriptionToBeChanged = $(this).parent().siblings('.description');
            var form = $(this).parent();
            console.log(form);
            $.ajax({
                url: 'api/books.php',
                type: 'PUT',
                data: {id: idToChange, newAuthor: changeTitle, newDescription: changeDescription},
                dataType: 'json'
            })
                    .done(function (result) {
                        var newestBook = JSON.parse(result[0]);
                        titleToBeChanged.html(newestBook.title);
                        descriptionToBeChanged.html(newestBook.description);
                        form.remove();
                    })
                    .fail(function () {
                        console.log('Wystąpił błąd123');
                    });


        });
        
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
                    newBook.append(addDeleteButton());
                    $('#bookList').prepend(newBook);
                    newTitle.val = '';
                    newAuthor.val = '';
                    newDescription.val = '';
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });


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
                    console.log(result['statusToConfirm']);
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });
    });



    function addDeleteButton() {
        var newLink = $('<a href>');
        newLink.html('Skasuj książkę');
        newLink.addClass('delete');
        return newLink;

    }



});