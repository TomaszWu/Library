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
                //wpinamy książke na stronę
                $('#bookList').append(bookDiv);
            }
        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    });


    var allBooks = $('#bookList');

    allBooks.on('click', '.singleBook', function () {
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
                    console.log('Wystąpił błąd2');
                    }
                })
        }
    });

//    $('.addABook').on('click', function() {
//        
//        
//        console.log('tak');
//    });

});

