<?php
    $txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
    $txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
    $txtApellidoP = (isset($_POST['txtApellidoP'])) ? $_POST['txtApellidoP'] : "";
    $txtApellidoM = (isset($_POST['txtApellidoM'])) ? $_POST['txtApellidoM'] : "";
    $txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
    $txtFoto = (isset($_FILES["txtFoto"]["name"])) ? $_FILES["txtFoto"]["name"] : "";

    $accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

    $accionAgregar = "";
    $accionModificar = $accionEliminar = $accionCancelar = "disabled";
    $mostrarModal = false;

    include("../Conexion/conexion.php");

    switch($accion){
        case "btnAgregar":
            $sentencia = $pdo->prepare("INSERT INTO empleados(Nombre,ApellidoP,ApellidoM,Correo,Foto) VALUES (:Nombre, :ApellidoP, :ApellidoM, :Correo, :Foto)");
            $sentencia->bindParam(':Nombre', $txtNombre);
            $sentencia->bindParam(':ApellidoP', $txtApellidoP);
            $sentencia->bindParam(':ApellidoM', $txtApellidoM);
            $sentencia->bindParam(':Correo', $txtCorreo);
            $fecha = new DateTime();
            $nombreArchivo = ($txtFoto!="")?$fecha->getTimestamp()."_".$_FILES["txtFoto"]["name"]:"imagen.png";
            $tmpFoto = $_FILES["txtFoto"]["tmp_name"];
            if($tmpFoto!=""){
                move_uploaded_file($tmpFoto, "../Imagenes/" .$nombreArchivo);
            }
            $sentencia->bindParam(':Foto', $nombreArchivo);
            $sentencia->execute();
            header('Location: index.php');
        break;

        case "btnModificar":
            $sentencia = $pdo->prepare("UPDATE empleados SET Nombre=:Nombre, ApellidoP=:ApellidoP , ApellidoM=:ApellidoM ,Correo=:Correo  WHERE ID=:id");
            $sentencia->bindParam(':Nombre', $txtNombre);
            $sentencia->bindParam(':ApellidoP', $txtApellidoP);
            $sentencia->bindParam(':ApellidoM', $txtApellidoM);
            $sentencia->bindParam(':Correo', $txtCorreo);
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();

            $fecha = new DateTime();
            $nombreArchivo = ($txtFoto!="")?$fecha->getTimestamp()."_".$_FILES["txtFoto"]["name"]:"imagen.png";
            $tmpFoto = $_FILES["txtFoto"]["tmp_name"];
            if($tmpFoto!=""){
                // subimos la foto al servidor
                move_uploaded_file($tmpFoto, "../Imagenes/" .$nombreArchivo);
                // eliminamos la fotografia actual
                $sentencia = $pdo->prepare("SELECT Foto FROM empleados WHERE ID=:id");
                $sentencia->bindParam(':id', $txtId);
                $sentencia->execute();
                $empleado = $sentencia->fetch(PDO::FETCH_LAZY);

                if(isset($empleado["Foto"])){
                    if(file_exists("../Imagenes/".$empleado["Foto"])){
                        if($empleado['Foto'] != "imagen.png"){
                            unlink("../Imagenes/".$empleado["Foto"]);
                        }
                    }
                }
                // actualizamos el link de la foto subida
                $sentencia = $pdo->prepare("UPDATE empleados SET Foto=:Foto  WHERE ID=:id");
                $sentencia->bindParam(':Foto', $nombreArchivo);
                $sentencia->bindParam(':id', $txtId);
                $sentencia->execute();
                header('Location: index.php');
            }

            
        break;

        case "btnEliminar":
            $sentencia = $pdo->prepare("SELECT Foto FROM empleados WHERE ID=:id");
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            $empleado = $sentencia->fetch(PDO::FETCH_LAZY);

            if(isset($empleado["Foto"])&&($empleado["Foto"]!="imagen.png")){
                if(file_exists("../Imagenes/".$empleado["Foto"])){
                    unlink("../Imagenes/".$empleado["Foto"]);
                }
            }

            $sentencia = $pdo->prepare("DELETE FROM empleados WHERE ID=:id");
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            header('Location: index.php');
        break;

        case "btnCancelar":
            header('Location: index.php');
        break;
        case "Seleccionar":
            $accionAgregar = "disabled";
            $accionModificar = $accionEliminar = $accionCancelar = "";
            $mostrarModal = true;

            $sentencia = $pdo->prepare("SELECT * FROM empleados WHERE ID=:id");
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            $empleado = $sentencia->fetch(PDO::FETCH_LAZY);

            $txtNombre = $empleado['Nombre'];
            $txtApellidoP = $empleado['ApellidoP'];
            $txtApellidoM = $empleado['ApellidoM'];
            $txtCorreo = $empleado['Correo'];
            $txtFoto = $empleado['Foto'];

        break;
    }

    $sentencia = $pdo->prepare("SELECT * FROM empleados");
    $sentencia->execute();
    $listaEmpleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
                                <input type="text" class="form-control" required name="txtNombre" placeholder="" id="txtNombre" require="" value="<?php echo $txtNombre; ?>">
                                <br>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtApellidoP">Apellido:</label>
                                <input type="text" class="form-control" required name="txtApellidoP" placeholder="" id="txtApellidoP" require="" value="<?php echo $txtApellidoP; ?>">
                                <br>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtApellidoM">Apellido:</label>
                                <input type="text" class="form-control" required name="txtApellidoM" placeholder="" id="txtApellidoM" require="" value="<?php echo $txtApellidoM; ?>">
                                <br>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="txtCorreo">Correo:</label>
                                <input type="email" class="form-control" required name="txtCorreo" placeholder="" id="txtCorreo" require="" value="<?php echo $txtCorreo; ?>">
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
                        <button value="btnEliminar" <?php echo $accionEliminar; ?> class="btn btn-danger" type="submit" name="accion">Eliminar</button>
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

        <div class="row">
            <table class="table">
                <thead>
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
                                    <input type="hidden" name="txtId" value="<?php echo $empleado['ID']; ?>">
                                    <input type="submit" class="btn btn-info" value="Seleccionar" name="accion">
                                    <button value="btnEliminar" class="btn btn-danger" type="submit" name="accion">Eliminar</button>
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
    </div>
</body>
</html>