<?php
    $txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
    $txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
    $txtApellidoP = (isset($_POST['txtApellidoP'])) ? $_POST['txtApellidoP'] : "";
    $txtApellidoM = (isset($_POST['txtApellidoM'])) ? $_POST['txtApellidoM'] : "";
    $txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
    $txtFoto = (isset($_POST['txtFoto'])) ? $_POST['txtFoto'] : "";

    $accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

    include("../Conexion/conexion.php");

    switch($accion){
        case "btnAgregar":
            echo "btnAgregar";
            $sentencia = $pdo->prepare("INSERT INTO empleados(Nombre,ApellidoP,ApellidoM,Correo,Foto) VALUES (:Nombre, :ApellidoP, :ApellidoM, :Correo, :Foto)");
            $sentencia->bindParam(':Nombre', $txtNombre);
            $sentencia->bindParam(':ApellidoP', $txtApellidoP);
            $sentencia->bindParam(':ApellidoM', $txtApellidoM);
            $sentencia->bindParam(':Correo', $txtCorreo);
            $sentencia->bindParam(':Foto', $txtFoto);
            $sentencia->execute();
            header('Location: index.php');
        break;

        case "btnModificar":
            echo "btnModificar";
            $sentencia = $pdo->prepare("UPDATE empleados SET Nombre=:Nombre, ApellidoP=:ApellidoP , ApellidoM=:ApellidoM ,Correo=:Correo ,Foto=:Foto WHERE ID=:id");
            $sentencia->bindParam(':Nombre', $txtNombre);
            $sentencia->bindParam(':ApellidoP', $txtApellidoP);
            $sentencia->bindParam(':ApellidoM', $txtApellidoM);
            $sentencia->bindParam(':Correo', $txtCorreo);
            $sentencia->bindParam(':Foto', $txtFoto);
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            header('Location: index.php');
        break;

        case "btnEliminar":
            echo "btnEliminar";
            $sentencia = $pdo->prepare("DELETE FROM empleados WHERE ID=:id");
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            header('Location: index.php');
        break;

        case "btnCancelar":
            echo "btnCancelar";
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
        <form action="" method="post" ectype="multipart/form-data">
        <label for="txtId">Id:</label>
            <input type="text" name="txtId" placeholder="" id="txtId" require="" value="<?php echo $txtId; ?>">
            <br>
            <label for="txtNombre">Nombre:</label>
            <input type="text" name="txtNombre" placeholder="" id="txtNombre" require="" value="<?php echo $txtNombre; ?>">
            <br>
            <label for="txtApellidoP">Apellido:</label>
            <input type="text" name="txtApellidoP" placeholder="" id="txtApellidoP" require="" value="<?php echo $txtApellidoP; ?>">
            <br>
            <label for="txtApellidoM">Apellido:</label>
            <input type="text" name="txtApellidoM" placeholder="" id="txtApellidoM" require="" value="<?php echo $txtApellidoM; ?>">
            <br>
            <label for="txtCorreo">Correo:</label>
            <input type="text" name="txtCorreo" placeholder="" id="txtCorreo" require="" value="<?php echo $txtCorreo; ?>">
            <br>
            <label for="txtFoto">Foto:</label>
            <input type="text" name="txtFoto" placeholder="" id="txtFoto" require="" value="<?php echo $txtFoto; ?>">
            <br>
            
            <button value="btnAgregar" type="submit" name="accion">Agregar</button>
            <button value="btnModificar" type="submit" name="accion">Modificar</button>
            <button value="btnEliminar" type="submit" name="accion">Eliminar</button>
            <button value="btnCancelar" type="submit" name="accion">Cancelar</button>
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
                            <td><?php echo $empleado['Foto']; ?></td>
                            <td><?php echo $empleado['Nombre']; ?> <?php echo $empleado['ApellidoP']; ?> <?php echo $empleado['ApellidoM']; ?></td>
                            <td><?php echo $empleado['Correo']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="txtId" value="<?php echo $empleado['ID']; ?>">
                                    <input type="hidden" name="txtNombre" value="<?php echo $empleado['Nombre']; ?>">
                                    <input type="hidden" name="txtApellidoP" value="<?php echo $empleado['ApellidoP']; ?>">
                                    <input type="hidden" name="txtApellidoM" value="<?php echo $empleado['ApellidoM']; ?>">
                                    <input type="hidden" name="txtCorreo" value="<?php echo $empleado['Correo']; ?>">
                                    <input type="hidden" name="txtFoto" value="<?php echo $empleado['Foto']; ?>">
                                    <input type="submit" value="Seleccionar" name="accion">
                                    <button value="btnEliminar" type="submit" name="accion">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <!-- </tbody> -->
            </table>
        </div>
    </div>
</body>
</html>