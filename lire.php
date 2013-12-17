<?php

if (isset ($_GET['etu'])) {

	$connexion = mysql_connect("localhost", "root", "");
	mysql_select_db("etunote", $connexion);
	
	extract($_GET);
	getDonnee($etu);
}else{
	echo "{}";
}
//fonction rÃ©cuperer
function getDonnee($etu) {
		$sql= "select * 
		from etudiant_note
		WHERE id = '".$etu."'";
	$rs = mysql_query($sql) or die('RequÃªte invalide : ' . mysql_error());
	$arrG = array();
	While ($arr = mysql_fetch_array($rs)){
		$arrG[] = $arr;
	}
	echo json_encode($arrG); //  transdorme le tableau php en  chaine de charactere json ,car javascript traduir la chaine de caractere en objet
	
}

?>
