<html>
	<head>
		<title>Trombino - Evaluation étudiant</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
	</head>
	<body >	
	<?php
include_once 'Personne.php';

// lecture d'un flux RSS 
$handle = fopen("http://picasaweb.google.com/data/feed/base/user/112537161896190034287/albumid/5931538032377292977?alt=rss&kind=photo&authkey=Gv1sRgCJjJwc265LnxigE&hl=fr", "rb");

// buffer contenant les données du flux
$flux = '';

// si la lecture du flux RSS est ok
if (isset($handle) && !empty($handle)) {
    while (!feof($handle)) {
		
    // on charge les données de notre flux RSS par paquet
    $flux .= fread($handle, 4096);
    }

    // test avec la classe SimpleXML
    // on construit notre parser RSS avec notre flux RSS
    $RSS2Parser = simplexml_load_string($flux);

    // on se positionne sur la balise (racine de notre flux RSS)
    $racine = $RSS2Parser->channel;

    // pour chaque item
    foreach($racine ->item as $element) {
        
        /*var_dump($element->description);
        die();*/
        
        //retourne la position de la chaine en paramètre dans une chaine
        $linkPosition = stripos($element->description, "src");
        
        //couper la chaine de caractère à partir de la position indiqué
        $link = substr($element->description, $linkPosition);
        
        //on les découpe selon notre ...
        $trueLink = explode('</a>', $link);
        $personne[] = new personne($trueLink[0]);
    } 	
    
    /*echo "<h2>Liste des étudiants participants</h2>";
    var_dump($personne);
    die();*/
}
?>
<hr/>
<h2>Liste des étudiants participants</h2><a href="#" id="hideBlock"><img src="hide.png"></a>
<hr/>
<?php 
foreach($personne as $p){ ?>
    <div class="photo">
        <img <?php echo $p->img;?>
        <p> Nom :</p>
        <p> Prenom :</p>
        <div class="diagramme">
            <p id="headBlock">
                <em>Les notes de l'année</em>.
            </p>
            
        </div>
        
    </div>
    <hr/>
<?php }
?>


	</body>
</html>