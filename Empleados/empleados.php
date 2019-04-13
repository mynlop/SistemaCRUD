<?php
    $txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
    $txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
    $txtApellidoP = (isset($_POST['txtApellidoP'])) ? $_POST['txtApellidoP'] : "";
    $txtApellidoM = (isset($_POST['txtApellidoM'])) ? $_POST['txtApellidoM'] : "";
    $txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
    $txtFoto = (isset($_FILES["txtFoto"]["name"])) ? $_FILES["txtFoto"]["name"] : "";

    $accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

    $error = array();

    $accionAgregar = "";
    $accionModificar = $accionEliminar = $accionCancelar = "disabled";
    $mostrarModal = false;

    include("../Conexion/conexion.php");

    switch($accion){
        case "btnAgregar":

            if($txtNombre==""){
                $error['Nombre']="Escribe el nombre";
            }
            if($txtApellidoP==""){
                $error['ApellidoP'] = "Escribe tu apellido paterno";
            }
            if($txtApellidoP==""){
                $error['ApellidoM'] = "Escribe tu apellido materno";
            }
            if($txtCorreo==""){
                $error['Correo'] = "Escribe tu correo";
            }

            if(count($error)>0){
                $mostrarModal = true;
                break;
            }

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
            $sentencia = $pdo->prepare("UPDATE empleados SET Nombre=:Nombre, ApellidoP=:ApellidoP , ApellidoM=:ApellidoM ,Correo=:Correo  WHERE Id=:id");
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
                $sentencia = $pdo->prepare("SELECT Foto FROM empleados WHERE Id=:id");
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
                $sentencia = $pdo->prepare("UPDATE empleados SET Foto=:Foto  WHERE Id=:id");
                $sentencia->bindParam(':Foto', $nombreArchivo);
                $sentencia->bindParam(':id', $txtId);
                $sentencia->execute();
                header('Location: index.php');
            }

            
        break;

        case "btnEliminar":
            $sentencia = $pdo->prepare("SELECT Foto FROM empleados WHERE Id=:id");
            $sentencia->bindParam(':id', $txtId);
            $sentencia->execute();
            $empleado = $sentencia->fetch(PDO::FETCH_LAZY);

            if(isset($empleado["Foto"])&&($empleado["Foto"]!="imagen.png")){
                if(file_exists("../Imagenes/".$empleado["Foto"])){
                    unlink("../Imagenes/".$empleado["Foto"]);
                }
            }

            $sentencia = $pdo->prepare("DELETE FROM empleados WHERE Id=:id");
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

            $sentencia = $pdo->prepare("SELECT * FROM empleados WHERE Id=:id");
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