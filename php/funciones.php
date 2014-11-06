<?php
function conectar(){
	$conecta=mysqli_connect("localhost", "red_itq", "10141461", "red_itq");
	if(!$conecta) {echo "Error".mysqli_connect_error($conecta). "no.".mysqli_connect_errno($conecta);}
	else {
		return $conecta;
	}
	
}

function mostrar_mensajes_inicio($con){

	$query="select * from mensajes where id_padre='0' order by id_mensaje desc limit 5";
	$query2="select nombre_corto from  usuarios u ,  mensajes m  where m.id_usuario=u.id_usuario";
	
	if (!($resultado=mysqli_query($con,$query))) {echo "Error". mysqli_error($con);}
	else{
	
	while($muestra=mysqli_fetch_array($resultado)){
		$resultado2=mysqli_query($con,$query2);
		$muestra2=mysqli_fetch_array($resultado2);
		echo $muestra2['nombre_corto'].":  ";
		echo "<span class='marker'>Tema :".$muestra['asunto']."<br/></span>";
		echo "".$muestra['descripcion']."<a href='php/contesta_mensaje.php?id_padre=".$muestra['id_mensaje']."'> Contestar</a><br>";
		//mensajes_respuesta($muestra['id_mensaje'],$con);
		
	}

	}}

function mostrar_mensajes($con){
	$query="select * from mensajes where id_padre='0' order by id_mensaje desc";
	$query2="select nombre_corto from  usuarios u ,  mensajes m  where m.id_usuario=u.id_usuario";
	if (!$resultado=mysqli_query($con,$query)) {echo "Error". mysqli_error($con);}
	else{
	
	while($muestra=mysqli_fetch_array($resultado)){
		$resultado2=mysqli_query($con,$query2);
		$muestra2=mysqli_fetch_array($resultado2);
		echo $muestra2['nombre_corto'].":  ";
		echo "<span class='marker'>Tema:".$muestra['asunto']."<br/></span>";
		echo "".utf8_encode($muestra['descripcion'])."<a href='contesta_mensaje.php?id_padre=".$muestra['id_mensaje']."'> 
		Contestar</a><br>
		<a href='ver_respuesta_mensaje.php?id=".$muestra['id_mensaje']."'> Ver respuestas</a><br>";
		
		if($_SESSION['tipo_usuario']=='1'|| $_SESSION['id_usuario']==$muestra['id_usuario'] ){
		
			echo "<a href='eliminar_mensaje.php?id_eliminar=".$muestra['id_usuario']."'> Eliminar </a>"." </br> </span>"; 
			echo "<a href='editar_mensaje.php?id_editar=".$muestra['id_mensaje']."'> Editar </a>"." </br> </span>"; 
			//echo "<a href='contesta_mensaje.php?id_padre=".$muestra['id_mensaje']."?id_cat=".$muestra['id_categoria']."'> Contestar</a><br>";
		}
		
		//mensajes_respuesta($muestra['id_mensaje'],$con);
	}

	}
	
	
}
function mensajes_respuesta($padre,$link){
	
	$query="select * from mensajes where id_padre='".$padre."'";
	
	if (!$resultado=mysqli_query($link,$query)) {echo "Error". mysqli_error($link);}
	else{
	
	while($muestra=mysqli_fetch_array($resultado)){
		echo "".utf8_encode($muestra['descripcion'])."</br></span>";
	}

	}
	
}

function ver_catalogo($con,$tabla, $id, $agregar, $eliminar, $modificar){
	if($_SESSION['tipo_usuario']=='1'){
	$query="select * from ".$tabla;
	
	if (!$resultado=mysqli_query($con,$query)) {echo "Error". mysqli_error($con);}
	else{
	
	while($muestra=mysqli_fetch_array($resultado)){
		echo "".utf8_encode($muestra['descripcion']);
		if($_SESSION['tipo_usuario']=='1'){
			
			echo "<a href='".$agregar."'> Agregar </a>"." </br> </span>"; 
			echo "<a href='".$eliminar."?id_eliminar=".$muestra[$id]."'> Eliminar </a>"." </br> </span>"; 
			echo "<a href='".$modificar."?id_modificar=".$muestra[$id]."'> Modificar </a>"." </br> </span>"; 
			
		}
	}

	}
	}
	else{
		echo "<h1>Acceso no autorizado</h1>";
	}
}




function eliminar_catalogo($con, $tabla, $id_tabla, $form){
	//if (validar()){
    if($_SESSION['tipo_usuario']=='1'){
    echo $query="delete from ".$tabla." where ".$id_tabla."=".$_GET['id_eliminar'];
	if (!$resultado=mysqli_query($con,$query)) {
		echo "Error". mysqli_error($con);
	}
   else{
    header("location: ".$form);

   }
//}
	}
	else echo "<h1>Acceso no autorizado</h1>";
}
function modificar_catalogo($con, $tabla, $id, $form){
	if($_SESSION['tipo_usuario']=='1'){
	if(!isset($_POST['form_enviar'])){
	echo "<form name='agrega_status' method='post' action='".$form."?id_mod=".$_GET['id_modificar']."' >
	<input type='text' name='form_status_descripcion'>
	<input type='submit' name='form_enviar' value='Modificar'>
	</form>";
	}
		else {
				//echo $_GET['id_modificar'];
				//print_r ($_POST);	
				echo $query="UPDATE ".$tabla." set descripcion = ('".$_POST['form_status_descripcion']."') where ".$id."=".$_GET['id_mod'];
				if(!$resultado=mysqli_query($con,$query))
				mysqli_error($con);
	}
	}
	  else echo "<h1>Acceso no autorizado</h1>";
	
}

function agregar_catalogo($con, $tabla, $form){
	if(!isset($_POST['form_enviar'])){
	echo "<form name='agrega_status' method='post' action='".$form."' >
	<input type='text' name='form_status_descripcion'>
	<input type='submit' name='form_enviar' value='Registrar'>
	</form>";}
		else {
			echo $query="INSERT INTO ".$tabla." VALUES (null,'".$_POST['form_status_descripcion']."')";
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
	}
}

function agregar_usuario($con){
	$query= "select * from tipo_usuarios";
	$resultado=mysqli_query($con,$query);
	if($_SESSION['tipo_usuario']=='1'){
	if(!isset($_POST['form_enviar'])){
	echo "<form name='agrega_status' method='post' action='agregar_usuario.php'>
	 Nick       <input type='text' name='form_nombrecorto'> <br><br>
	 Nombre     <input type='text' name='form__nombrelargo'> <br><br>
	 Contraseña <input type='text' name='form__contrasena'><br><br>
	 Correo     <input type='text' name='form__correo'><br><br>
	 Tipo Usuario <select name='id_tipo'>";
	while($muestra=mysqli_fetch_array($resultado)){
	 echo"<option value='".$muestra['id_tipo_usuario']."'>".$muestra['descripcion']."</option>";
		//ECHO $muestra['descripcion'];
	}
	echo"</select>";
	
	echo"<input type='submit' name='form_enviar' value='Registrar'>
	</form>";}
		else {
			echo $query="INSERT INTO usuarios VALUES (null,'".$_POST['form_nombrecorto']."','".$_POST['form__nombrelargo']."', '".$_POST['form__contrasena']."',
														'".$_POST['id_tipo']."','1','".$_POST['form__correo']."')";
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
		}
	}
	
	if($_SESSION['tipo_usuario']=='3'){
	if(!isset($_POST['form_enviar'])){
	echo "<form name='agrega_status' method='post' action='agregar_usuario.php'>
	 Nombre     <input type='text' name='form_nombrecorto'> <br><br>
	 Apellido   <input type='text' name='form__nombrelargo'> <br><br>
	 Contraseña <input type='text' name='form__contrasena'><br><br>
	 Correo     <input type='text' name='form__correo'><br><br>
	
	<input type='submit' name='form_enviar' value='Registrar'>
	</form>";}
		else {
			echo $query="INSERT INTO usuarios VALUES (null,'".$_POST['form_nombrecorto']."','".$_POST['form__nombrelargo']."', '".$_POST['form__contrasena']."',
														'2','1','".$_POST['form__correo']."')";
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
		}
	}
	if($_SESSION['tipo_usuario']=='2'){
		echo "<h1>No puedes agregar usuario</h1>";
	}
}

function ver_usuarios($con){

	if($_SESSION['tipo_usuario']=='1'||$_SESSION['tipo_usuario']=='3'){
		echo "<a href='agregar_usuario.php'> Agregar </a>"." </br> </span>"; 
	}
		
	if($_SESSION['tipo_usuario']=='1'|| $_SESSION['tipo_usuario']=='2'){
	$query="select * from usuarios";
	
	
	if (!$resultado=mysqli_query($con,$query)) {echo "Error". mysqli_error($con);}
	else{
	
	while($muestra=mysqli_fetch_array($resultado)){
		
		if($_SESSION['tipo_usuario']=='1'|| $_SESSION['id_usuario']==$muestra['id_usuario']){
			
			echo "<br>".utf8_encode($muestra['nombre_corto'])." ".utf8_encode($muestra['nombre_largo'])."<br>";
			echo "<a href='modificar_usuario.php?id_modificar=".$muestra['id_usuario']."'> Modificar </a>"."</span>"; 
			if($muestra['id_status']!='4')
			{echo "<a href='eliminar_usuario.php?id_eliminar=".$muestra['id_usuario']."'> Eliminar </a>"." </br> </span>"; }
			
			
		}
	}

	}
	}	
	
}

function modificar_usuario($con){
	$query= "select * from tipo_usuarios";
	$resultado=mysqli_query($con,$query);
	echo $query2="select * from usuarios where id_usuario=".$_GET['id_modificar'];
	$id2=$_GET['id_modificar'];
	$resultado2=mysqli_query($con,$query2);
	
	if($_SESSION['tipo_usuario']=='1'){
		if(!isset($_POST['form_enviar'])){
	while($muestra=mysqli_fetch_array($resultado2)){
	echo "<form name='agrega_status' method='post' action='modificar_usuario.php?id_modificar=".$id2."'>
	 Nombre     <input type='text' name='form_nombrecorto' value='".$muestra['nombre_corto']."'> nombre<br><br>
	 Apellido   <input type='text' name='form__nombrelargo' value='".$muestra['nombre_largo']."'> <br><br>
	 Contraseña <input type='text' name='form__contrasena' value='".$muestra['contrasena']."'><br><br>
	 Correo     <input type='text' name='form__correo' value='".$muestra['correo_electronico']."'><br><br>";
	 }
	 echo "Tipo Usuario <select name='id_tipo'>";
	while($muestra=mysqli_fetch_array($resultado)){
	 echo"<option value='".$muestra['id_tipo_usuario']."'>".$muestra['descripcion']."</option>";
		//ECHO $muestra['descripcion'];
	}
	echo"</select>";
	
	echo"<input type='submit' name='form_enviar' value='Registrar'>
	</form>";}
	
	else {
			echo $query="Update usuarios set nombre_corto='".$_POST['form_nombrecorto']."',nombre_largo='".$_POST['form__nombrelargo']."',
											contrasena='".$_POST['form__contrasena']."', correo_electronico='".$_POST['form__correo']."', 
											id_tipo_usuario='".$_POST['id_tipo']."' where id_usuario=".$id2;
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
		}
	}
	
	else{
		if(!isset($_POST['form_enviar'])){
	while($muestra=mysqli_fetch_array($resultado2)){
	echo "<form name='agrega_status' method='post' action='agregar_usuario.php'>
	 Nombre     <input type='text' name='form_nombrecorto' value='".$muestra['nombre_corto']."'> nombre<br><br>
	 Apellido   <input type='text' name='form__nombrelargo' value='".$muestra['nombre_largo']."'> <br><br>
	 Contraseña <input type='text' name='form__contrasena' value='".$muestra['contrasena']."'><br><br>
	 Correo     <input type='text' name='form__correo' value='".$muestra['correo_electronico']."'><br><br>";
	 }
	 
	echo"<input type='submit' name='form_enviar' value='Registrar'>
	</form>";}
	
	else {
			$query="Update usuarios set nombre_corto='".$_POST['form_nombrecorto']."',nombre_largo='".$_POST['form__nombrelargo']."',
											contrasena='".$_POST['form__contrasena']."', correo_electronico='".$_POST['form__correo']."', 
											id_tipo_usuario='2'where id_usuario=".$id2;
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
		}
	}
}

function eliminar_usuario($con){
		
	
	
			$query="UPDATE usuarios set id_status = '4' where id_usuario=".$_GET['id_eliminar'];
			if(!$resultado=mysqli_query($con,$query))
			mysqli_error($con);
			header("location:ver_usuarios.php");
	
	}
	


function validar(){

	if(!isset($_SESSION['login'])){
	
		header("location:sinacceso.php");
	}
	
	else{
		return true;
	}
}

function validar_index(){

	if(!isset($_SESSION['login'])){
	
		header("location:php/sinacceso.php");
	}
	
	else{
		return true;
	}
}

function salir(){

	session_destroy();
}
