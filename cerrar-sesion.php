<?php
if (!isset($_SESSION)) {
  session_start();
} 
 ?>
<?php
//session_name($usuarios_sesion);
session_unset();
session_destroy();
echo "<META HTTP-EQUIV=Refresh CONTENT='0.5;URL=index.php'>";  

?>
<html>
	<head>
				
	</head>
	<body >
		
		<center>
          <p><img src="img/logo.png" width="110" height="113"></p>
          <p>
            <img src="img/preloader-01.gif" width="32" height="32"></p>
    </center>
</body>
</html>