function getProduct() {

    var start = $('#start').val();
    var length = $('#length').val();

    var searchname = $('#searchname').val();
    var searchcategory = $('#searchcategory').val()


    var imagetable1 = "../img/btn-update.png";
    var imagetable2 = "../img/btn-delete.png";
  //  var actionEdit = updateProductTable();
    //var actionEdit =  window.location = "http://127.0.0.1:8000/updateproduct";
   /// var actionDelete = deleteProduct();

    $.ajax({
        url: 'http://127.0.0.1:8000/api/getproduct',
        method: 'POST',
        data: {
            start: 0,
            length: 5,
            search: searchname,
            order: {
                fieldName: 'created_at',
                direction: 'DESC',
            },
            filters: {
                category: searchcategory
            }

        },
        dataType: 'json'

    }).done(function (response) {
      
        $.each(response.result, function (key, value) {
            console.log(value);

            $('#resultfinal').append("<tr>\
                         <td>"+ value.id + "</td>\
                        <td>"+ value.name + "</td>\
                        <td>"+ value.description + "</td>\
                        <td>"+ value.price + "</td>\
                        <td>"+ value.category + "</td>\
                        <td>"+ value.image_url + "</td>\
                        <td><img src=" + imagetable1 + " class='btnEdit' onclick='updateProductTable(" + value.id + ")'" + "></td>\
                        <td><img src=" + imagetable2 + " class='btnDelete' onclick='deleteProduct(" + value.id + ")'" + "></td>\
                        </tr>");

        });

    });

}

function updateProductTable(id) {

    window.location = "http://127.0.0.1:8000/updateproduct?id="+ id;
}


function registerProduct() {

    var formregister = document.getElementById('formregister');
    formregister.addEventListener('submit', function (event) {
        event.preventDefault();
    })

    var name = $('#name').val();
    var description = $('#description').val();
    var price = $('#price').val();
    var category = $('#category').val();
    var image = $('#image').val();


    $.ajax({
        url: 'http://127.0.0.1:8000/api/register',
        method: 'POST',
        data: {
            name: name,
            description: description,
            price: price,
            category: category,
            image_url: image,
        },
        dataType: 'json'

    }).done(function (response) {

        if (response.status == 200) {
            window.location = "http://127.0.0.1:8000/getproduct";
        }

    });

}

function updateProduct() {
    /*     var formupdate = document.getElementById('formupdate');
        formupdate.addEventListener('submit', function(event){
            event.preventDefault();
        }) */
    var id = $('#id').val();
    var name = $('#name').val();
    var description = $('#description').val();
    var price = $('#price').val();
    var category = $('#category').val();
    var imagem = $('#imagem').val();


    $.ajax({
        url: 'http://127.0.0.1:8000/api/update',
        method: 'PUT',
        data: {
            id: id,
            name: name,
            description: description,
            price: price,
            category: category,
            image_url: imagem,
        },
        dataType: 'json'

    }).done(function (response) {

        if (response.status == 200) {
            window.location = "http://127.0.0.1:8000/getproduct";
        }
    });

}

function deleteProduct(id) {

    $.ajax({
        url: 'http://127.0.0.1:8000/api/delete/' + id,
        method: 'DELETE',
        dataType: 'json'

    }).done(function (response) {

        if (response.status == 200) {
          //  getProduct();
        }
    });

}


function getByIdProduct(id) {

    $.ajax({
        url: 'http://127.0.0.1:8000/api/getbyid/' + id.toString(),
        method: 'GET',
        dataType: 'json'

    }).done(function (response) {
        console.log(response);
        $('#id').val(response.result.id);
        $('#name').val(response.result.name);
        $('#description').val(response.result.description);
        $('#price').val(response.result.price);
        $('#category').val(response.result.category);
        $('#image').val(response.result.image_url);

    });

}


