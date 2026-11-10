<?php

define("SERVIDOR","127.0.0.1");
define("USUARIO","root");
define("CLAVE","");
define("BASE_DATOS","bdteso2025");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Conexion()
{
   if (!($cn=mysqli_connect(SERVIDOR,USUARIO,CLAVE,BASE_DATOS)))
   {
    echo "Error conectando a la base de datos.";
    exit();
   }
   if (!mysqli_select_db($cn,BASE_DATOS))
   {
    echo "Hay un error en la conexión a la base de datos.";
    exit();
   }
   
   return $cn;
   
}