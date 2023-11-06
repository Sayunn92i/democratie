<?php
// Verifie si l'utilisateur est connecté
function check_login($con)
{
	//Si l'id de l'utilisateur existe
	if(isset($_SESSION['id_uti']))
	{
		//On verifie qu'il est dans la base de donnée
		$id = $_SESSION['id_uti'];
		$query ="SELECT * FROM t_utilisateur_uti WHERE id_uti = '$id' LIMIT 1;";
		$result = mysqli_query($con,$query);
		//Si il y a un resultat on retourne les données
		if($result && mysqli_num_rows($result) > 0)
		{
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}
	else
	{
		header("Location: connexion.php");
		die;
	}
	//Redirection à login 
	
}