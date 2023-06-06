<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <title>Product Catalog Management</title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scriptChamadas.js') }}"></script>

    <script>
        function redirectRegister() {
            window.location.href = "{{ route('registerproduct') }}";
        }

        function reloadPage() {
        location.reload(true);
    }
    </script>

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
            <div class="offset-3 col-10">

                <div class="card mt-5">
                    <div class="card-header bg-primary text-white">
                        <b>Product Catalog</b>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <label for="name">Name:</label>
                                <input class="form-control mb-3" type="text" id="searchname" name="searchname" />

                                <label for="name">Category:</label>
                                <input class="form-control mb-3" type="text" id="searchcategory" name="searchcategory" />
                            </div>
                            <div class="col">
                                <button class="btn btn-primary mb-3" id="search" name="search"
                                    onclick="getProduct()">Search</button>

                                <button class="btn btn-primary mb-3" id="register" name="register"
                                    onclick="redirectRegister()">New Product</button><br>

                                    <button class="btn btn-primary mb-3" id="refresh" name="refresh"
                                    onclick="reloadPage()">Refresh</button><br>
                            </div>


                            <table class="table table-hover">
                                <thead>
                                    <tr>                                        
                                        <th scope="col">Id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Image_url</th>                                       

                                    </tr>
                                </thead>
                                <tbody id="resultfinal">
                                                         
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>


        </div>
    </div>

    </div>

</body>

</html>
