<?php
    require 'empleados.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD con PHP, MySQL</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Modal -->
            <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Empleado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <input type="hidden" required name="txtId" placeholder="" id="txtId" require="" value="<?php echo $txtId; ?>">

                            <div class="form-group col-md-4">
                                <label for="txtNombre">Nombre:</label>
                                <input type="text" class="form-control <?php echo (isset($error['Nombre']))?"is-invalid":"";?>"  required name="txtNombre" placeholder="" id="txtNombre" require="" value="<?php echo $txtNombre; ?>">
                                <div class="invalid-feedback">
                                    <?php echo (isset($error['Nombre']))?$error['Nombre']:"";?>"
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtApellidoP">Apellido:</label>
                                <input type="text" class="form-control <?php echo (isset($error['ApellidoP']))?"is-invalid":"";?>" required  name="txtApellidoP" placeholder="" id="txtApellidoP" require="" value="<?php echo $txtApellidoP; ?>">
                                <div class="invalid-feedback">
                                    <?php echo (isset($error['ApellidoP']))?$error['ApellidoP']:"";?>
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtApellidoM">Apellido:</label>
                                <input type="text" class="form-control <?php echo (isset($error['ApellidoM']))?"is-invalid":"";?>" required  name="txtApellidoM" placeholder="" id="txtApellidoM" require="" value="<?php echo $txtApellidoM; ?>">
                                <div class="invalid-feedback">
                                    <?php echo (isset($error['ApellidoM']))?$error['ApellidoM']:"";?>
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="txtCorreo">Correo:</label>
                                <input type="email" class="form-control <?php echo (isset($error['Correo']))?"is-invalid":"";?>" required  name="txtCorreo" placeholder="" id="txtCorreo" require="" value="<?php echo $txtCorreo; ?>">
                                <div class="invalid-feedback">
                                    <?php echo (isset($error['Correo']))?$error['Correo']:"";?>
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="txtFoto">Foto:</label>
                                <?php if($txtFoto != ""){  ?>
                                <br>
                                <img class="img-thumbnail rounded mx-auto d-block" width="100px" src="../Imagenes/<?php echo $txtFoto; ?>" alt="">
                                <?php }?>
                                <input type="file" class="form-control" accept="image/*" name="txtFoto" placeholder="" id="txtFoto" require="" value="<?php echo $txtFoto; ?>">
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button value="btnAgregar" <?php echo $accionAgregar; ?> class="btn btn-success" type="submit" name="accion">Agregar</button>
                        <button value="btnModificar" <?php echo $accionModificar; ?> class="btn btn-warning" type="submit" name="accion">Modificar</button>
                        <button value="btnEliminar" onClick="return Confirmar('Desea eliminar el empleado?');" <?php echo $accionEliminar; ?> class="btn btn-danger" type="submit" name="accion">Eliminar</button>
                        <button value="btnCancelar" <?php echo $accionCancelar; ?> class="btn btn-primary" type="submit" name="accion">Cancelar</button>
                    </div>
                    </div>
                </div>
            </div>
            <br/><br/>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#miModal">
            Agregar Registro +
            </button>
        </form>
        <br/><br/>                                    
        <div class="row">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- <tbody> -->
                    <?php foreach($listaEmpleados as $empleado){ ?>
                        <tr>
                            <td><img class="img-thumbnail" width="100px" src="../Imagenes/<?php echo $empleado['Foto']; ?>" alt="<?php echo $empleado['Foto']; ?>"></td>
                            <td><?php echo $empleado['Nombre']; ?> <?php echo $empleado['ApellidoP']; ?> <?php echo $empleado['ApellidoM']; ?></td>
                            <td><?php echo $empleado['Correo']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="txtId" value="<?php echo $empleado['Id']; ?>">
                                    <input type="submit" class="btn btn-info" value="Seleccionar" name="accion">
                                    <button value="btnEliminar" onClick="return Confirmar('Desea eliminar el empleado?');" class="btn btn-danger" type="submit" name="accion">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <!-- </tbody> -->
            </table>
        </div>
        <?php if($mostrarModal){  ?>
            <script>
                $('#miModal').modal('show');
            </script>
        <?php } ?>
        <script>
            function Confirmar(Mensaje){
                return (confirm(Mensaje))?true:false;
            }
        </script>
    </div>
</body>
</html>