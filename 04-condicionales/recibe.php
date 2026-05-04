<?php
$sexo = $_POST['sexo'];
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
if ($edad>=18){
    echo "Eres mayor de edad y tienes $edad años,  tu nombre es: $nombre y de sexo $sexo" ;
}else{
    echo "Eres menor de edad";
}

