<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <title>Product registration</title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scriptChamadas.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                {{-- <img src="{{ asset('img/catalogo.png') }}" width="48" height="48"
                    class="d-inline-block align-top" loading="lazy"> --}}
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href={{ route('getproduct') }}>
                            Product Catalog Management</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
    
    <div class="container">

        <div class="row">
            <div class="offset-3 col-6">

                <div class="card mt-5">
                    <div class="card-header bg-primary text-white">
                        <b>Product registration</b>
                    </div>

                    <div class="card-body">
                        <form id="formregister" name="formregister" action="">
                            <label for="name">Name:</label>
                            <input class="form-control mb-3" type="text" id="name" name="name" />

                            <label for="description">Description:</label>
                            <input class="form-control mb-3" type="text" id="description" name="description"  />

                            <label for="price">Price:</label>
                            <input class="form-control mb-3" type="text" id="price" name="price"/>

                            <label for="category">Category:</label>
                            <input class="form-control mb-3" type="text" id="category" name="category"/>

                            <label for="image">Image:</label>
                            <input class="form-control mb-3" type="text" id="image" name="image"/>

                        
                            <button class="btn btn-primary mb-3" id="enviar" name="enviar" type="submit" onclick="registerProduct()">Register</button><br> 
                        </form>

                    </div>
                </div>


            </div>
        </div>



    </div>
</body>

</html>