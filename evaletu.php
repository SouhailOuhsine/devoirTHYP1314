<html>
	<head>
		<title>Trombino - Evaluation Ã©tudiant</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="public/js/jquery.min.js"></script>
		<script type="text/javascript" src="public/js/d3.v2.js"></script>
		<script type="text/javascript">
		//tableau des exo
		var arrexo = {0:"ajouter", 1:"rÃ©cuperer", 2:"moyenne"};

		//enregistrement
		function saveexo(exo, note, etu) {
			//rÃ©cupÃ¨re les informations de l'Ã©tudiant
			var nom = $("#etu_nom_"+etu).text();
			var r = arrexo[exo];
			var s = arrexo[note];
			var url = $("#etu_tof_"+etu).attr("src");
			var p = {"exo":r,"note":s, "etu":nom, "url":url};
			
			$.get("ecrire.php", p,
					 function(data){
						alert(data);
					 });
			 
			
		}
		
		//rÃ©cupÃ©ration
		function getnote(etu) {
			//rÃ©cupÃ¨re les informations de l'Ã©tudiant
			var nom = $("#etu_nom_"+etu).text();
			var p = {"etu":nom,"note":s};
		$.get("lire.php", p,
					 function(data){
						dessineGraph(data, etu);
					 }, "json");
				
		}

		//dessine le graphe svg
		function dessineGraph(data, etu){

			d3.select("#svg_"+etu).remove();

			var m = [40, 60, 40, 10],
		    w = 1000 - m[1] - m[3],
		    h = 300 - m[0] - m[2],
		    data.forEach(function(d) {
		        d.maj = fmtDateBD.parse(d.maj);
		      });
		    
			var svg = d3.select("#etu_svg_"+etu).append("svg:svg")
		      .attr("id", "svg_"+etu)
		      .attr("width", w + m[1] + m[3])
		      .attr("height", h + m[0] + m[2])
		    .append("svg:g")
		      .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

			//spÃ©cification des Ã©chelles
			//https://github.com/mbostock/d3/wiki/Time-Formatting
			var dateDeb = fmtDate.parse("2012-10-10"),
			dateFin = fmtDate.parse("2013-01-31"),
			heureDeb = fmtHeure.parse("9:00"),
			heureFin = fmtHeure.parse("17:00"),
			x = d3.time.scale().range([0, w]).domain([dateDeb, dateFin]),
			y = d3.time.scale().range([h, 0]).domain([heureDeb, heureFin]),
			xAxis = d3.svg.axis().scale(x).ticks(6).tickSize(-h).tickSubdivide(true).tickFormat(fmtDateY),
		    yAxis = d3.svg.axis().scale(y).ticks(6).orient("right").tickFormat(fmtHeure);	
		    //xAxis.tickValues([dateDeb, fmtDate.parse("2012-10-29"), fmtDate.parse("2013-01-07")]);				
			//affichage des Ã©chelles
			// Add the x-axis.
			  svg.append("svg:g")
			      .attr("class", "x axis")
			      .attr("transform", "translate(0," + h + ")")
			      .call(xAxis);

			  // Add the y-axis.
			  svg.append("svg:g")
			      .attr("class", "y axis")
			      .attr("transform", "translate(" + w + ", 0)")
			      .call(yAxis);		
  		    
			svg.selectAll(".bar")
		      .data(data)
		    .enter().append("rect")
		      .attr("class", "bar")
		      .attr("x", function(d) { 
			      var t = x(d.maj);
			      return t; 
			      })
		      .attr("width", 10)
			  .attr("fill",function(d) { 
				  var note = "";
				  if(d.code=="ajouter");
				  if(d.code=="recuperer");
				  if(d.code=="lister");
			      return note; 
		      })
		      .attr("height", function(d) { 
			      return 3; 
			      });		    
		}
		</script>
		
		<style>
		.btn {
			cursor:pointer; 
		}
		.tof {
			width:200px; 
		}
		.good {
			color:green;
		}
		.bad {
			color:red;
		}
		
		div.conteneur { 
		  	text-align:left; /* centrage horizontal */
		  	clear:both;
			border-style:solid;
			border-width:2px;	
		}
		
		div.bloc {
			float:left;
			margin:3px 3px 3px 3px; 	 
		}

		.axis {
		  shape-rendering: crispEdges;
		}
		
		.x.axis line {
		  stroke: #fff;
		}
		
		.x.axis .minor {
		  stroke-opacity: .5;
		}
		
		.x.axis path {
		  display: none;
		}
		
		.y.axis line, .y.axis path {
		  fill: none;
		  stroke: #000;
		}
				
		</style>
		
	</head>
	<body >	
	<?php 
    $xml = simplexml_load_file("https://picasaweb.google.com/data/feed/base/user/113848708930851956597/albumid/5795380358275112865?alt=rss&kind=photo&hl=fr");
	//$xml = simplexml_load_file("http://localhost/trombino/trombino/file.xml");

	$i=0;

	foreach ($xml->channel->item as $item){
		if($i % 2 == 0) $c = 'good'; else $c = 'bad'; 
		echo "<div id='etu_".$i."' class='conteneur' >";
		echo "<div class='bloc' >";
		//affichage icons
		echo "<img src='public/img/ajouter.jpg' class='btn' onclick='savenote(1,".$i.")' />";
		echo "<img src='public/img/recuperer.jpg' class='btn' onclick='savenote(2,".$i.")' />";
		echo "<img src='public/img/lister.jpg' class='btn' onclick='savenote(3,".$i.")' />";
		
		echo "</div>";
		echo "<div class='bloc' >";
		//affichage des images
	
		echo "<img id='etu_tof_".$i."' src='".$item->enclosure['url']."' class='tof' onclick='getRaison(".$i.")' />";
		echo "<div class='$c' id='etu_nom_".$i."'>".$item->title."</div>";
		echo "</div>";
		echo "<div id='etu_svg_".$i."' class='bloc' ></div>";
		echo "</div>";
	
		$i++;
		}
		

	?>
	</body>
</html>