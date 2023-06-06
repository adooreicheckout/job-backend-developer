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
    <title>Update/Delete Product</title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scriptChamadas.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    
</head>

<body>

    <div class="container">

        <div class="row">
            <div class="offset-3 col-6">

                <div class="card mt-5">
                    <div class="card-header bg-success text-white">
                        <b>Update/Delete Product</b>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Informe</h5>
                        <p class="card-text"></p>

                        <form id="frmdeleteupdate" name="frmdeleteupdate" action="">
                            
                            <label for="id">Id:</label>
                            <input class="form-control mb-3" type="text" id="id" name="id" />

                            <button class="btn btn-success mb-3" id="enviar" name="enviar" onclick="getByIdProduct()"
                               >search product by id</button><br>
                        </form>

                        <div class="form-floating">
                            <textarea class="form-control" id="resultadofinal" style="overflow:hidden; height: 250px;"></textarea>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>

</body>

</html>
