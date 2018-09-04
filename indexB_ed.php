<!DOCTYPE html>
<html>
<head>
<title>KennisCrowd Visuals</title>
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.v2.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type='text/javascript' src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"> </script>
<style type="text/css">
html, body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  overflow:hidden;
}
.node text {
  font: 10px sans-serif;
}

.link {
  stroke: #999;
  stroke-opacity: .6;
}
#graph {
  width: 100%;
  height: 100%;
  top: 50px;
  position: absolute;
}
.ui-widget {
  margin-top: 60px;
  margin-left: 25px;	
  position: absolute;
  padding: 0;
  list-style: none;
  font-family: Verdana,Arial,sans-serif;
}
img {
    position: fixed;
    bottom: 40px;
    right: 40px;
    z-index: -2;
}
</style>
</head>
<body>
<img src="legenda2.jpg" alt="Legenda"
 style="top: 0px; left: 0px; position: fixed; z-index:1">
<svg id="graph"></svg>
<div class="ui-widget">
    <input id="search">
    <button type="button" onclick="searchNode()">Zoeken</button>
</div>
<img src="logo.png" alt="KennisCrowd" >
<?php
	$mysql_hostname = "localhost";
	$mysql_user = "deb78448_knc";
	$mysql_password = "knccnkknc";
	$mysql_database = "deb78448_knc";
	$prefix = "";
	$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("DBerror");
	$con = mysql_select_db($mysql_database, $bd) or die("QueryError");
	
	//$query = "SELECT * FROM current";
	//$result = mysql_query($query);
	$sql4 = "SELECT * FROM user";
	$result4 = mysql_query($sql4);
	$num_rows = mysql_num_rows($result4);
	$sql5 = "UPDATE current SET total='$num_rows'";
	mysql_query($sql5);
	
	
	//while($row = mysql_fetch_assoc($result))
	//{
	//	$temp = $row['current'];
	//}
	$key = $_GET["c"];
	//echo ($key);
	$sql1 = "SELECT id FROM events WHERE sleutel='$key'";
	$result1 = mysql_query($sql1);
	while($row1 = mysql_fetch_assoc($result1))
	{
		$temp = $row1['id'];
	}
	
	$currentid = $temp;
	
	
	$sql2 = "SELECT cats FROM events WHERE id='$currentid'";
	$result2 = mysql_query($sql2);
	while($row2 = mysql_fetch_assoc($result2))
	{
		$temp2 = $row2['cats'];
	}
	
	
	$currentevent = $temp;
	$bloop = explode( ',', $temp2 );
	$catar = array_filter($bloop, create_function('$a','return trim($a)!=="";'));
	$catar = array_filter($bloop);

	$currentcats = $catar;

	$sql3 = "SELECT * FROM user WHERE elink='$currentid'";
	
	$result3 = mysql_query($sql3);
	$pretags = array();
	
	$usernameArray = array();
	$photolinkArray = array();
	$tagsArray = array();
	
	
	$indexxkey = 0;
	
	while($row3 = mysql_fetch_assoc($result3))
	{	$usernameArray[$indexxkey] = $row3['naam'];
		$photolinkArray[$indexxkey] = $row3['afbeelding'];
		$pretags[$indexxkey] = $row3['tags'];
		$bloop2 = explode( ',', $pretags[$indexxkey] );
		$catar2 = array_filter($bloop2, create_function('$a','return trim($a)!=="";'));
		$catar2 = array_filter($bloop2);
		$tagsArray[$indexxkey] = $catar2;
     	$indexxkey++;
	}
	?>
  
<script type='text/javascript'>
		<?php
				//$php_array = array('abc','def','ghi');
				$js_array1 = json_encode($currentcats);
				echo "var catsArray = ". $js_array1 . ";\n";
				
				$js_array2 = json_encode($usernameArray);
				echo "var usernameArray = ". $js_array2 . ";\n";
				
				$js_array3 = json_encode($photolinkArray);
				echo "var photolinkArray = ". $js_array3 . ";\n";
				
				$js_array4 = json_encode($tagsArray);
				echo "var tagsArray = ". $js_array4 . ";\n";
				
				
		?>

navigator.sayswho = (function(){
    var ua= navigator.userAgent, tem,
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
    return M.join(' ');
})();

var browser = navigator.sayswho;
console.log(browser);


var	w = parseInt(d3.select("#graph").style("width")),
    h = parseInt(d3.select("#graph").style("height"))-50,
    r = 20
	;

var scaleAvatar = d3.scale.linear()
                    .domain([0, 2000])
                    .range([10, 50]);

var config = {
    "avatar_size" : scaleAvatar(w)
}

//Map the content according to the size of the screen
var scaleString = d3.scale.linear()
                    .domain([0, 2000])
                    .range([0, 250]);
			
var scaleForce = d3.scale.linear()
                    .domain([0, 2000])
                    .range([-20, -100]);
			
var scaleForceLink = d3.scale.linear()
                    .domain([0, 2000])
                    .range([.1, .01]);
					
var force = d3.layout.force()
    .gravity(.02)
    .charge(scaleForce(w))
    .linkDistance(scaleString(w))
	.linkStrength(.01)
    .size([w, h])
	;
	
var svg = d3.select("#graph").append("svg:svg")
			.attr("width", w)
    		.attr("height", h)
			;

var names = [], kennisGroep = [], kennisGroepColor = [], kennisGroepSize = [], linkColor = [], photoLink = [], nodesOut = [], linksOut = [];

	names = usernameArray.concat(catsArray);
	for(i=0; i<names.length; i++){
		if(i<usernameArray.length){
			photoLink[i] = photolinkArray[i];
			kennisGroep[i] = 0;
			for(j=0; j<tagsArray[i].length; j++){
				var catID = names.indexOf(tagsArray[i][j]);
				linksOut.push({source: i, target: catID});
			}
		}else{
			kennisGroep[i] = 1;
			kennisGroepSize[i] = 0;
			kennisGroepColor[i] = d3.rgb(Math.random()* 200,Math.random()* 255,Math.random()* 255);
		}
		 
		nodesOut[i] = {index: i, name: names[i]};
	}
	for(l=0; l<linksOut.length; l++){
		var catID = linksOut[l].target;
		console.log(catID);
		linkColor[l] = kennisGroepColor[catID];
		kennisGroepSize[catID]+=1;

	}

var biggest = 0;
for(k=0; k < nodesOut.length; k++){
	if(kennisGroep[k] ==1){
		if(kennisGroepSize[k]>biggest){
			biggest = kennisGroepSize[k];
		}
	}
}

//Map the amount of connections to radius of the kennisgroepnodes
var scale = d3.scale.linear()
                    .domain([0, biggest])
                    .range([2, config.avatar_size/2]);


//Highlight function
var toggle = 0;
//Create an array logging what is connected to what
var linkedByIndex = {};

for (i = 0; i < nodesOut.length; i++) {
    linkedByIndex[i + "," + i] = 1;
};
linksOut.forEach(function (d) {
    linkedByIndex[d.source + "," + d.target] = 1;
});
//This function looks up whether a pair are neighbours
function neighboring(a, b) {
    return linkedByIndex[a.index + "," + b.index];
}
function connectedNodes() {
    if (toggle == 0) {
        //Reduce the opacity of all but the neighbouring nodes
        d = d3.select(this).node().__data__;
        node.style("opacity", function (o) {
            return neighboring(d, o) | neighboring(o, d) ? 1 : 0.1;
        });
        link.style("opacity", function (o) {
            return d.index==o.source.index | d.index==o.target.index ? 1 : 0.1;
        });
        //Reduce the op
        toggle = 1;
    } else {
        //Put them back to opacity=1
        node.style("opacity", 1);
        link.style("opacity", 1);
        toggle = 0;
    }
}

force
      .nodes(nodesOut)
	  .links(linksOut)
      .start();
	  
//make links
  var link = svg.selectAll("line.link")
      .data(linksOut)
      .enter().append("svg:line")
      .attr("class", "link")
	  .style("stroke", function (d,i){ return linkColor[i]})
	  ;
  
//make nodes
  var node = svg.selectAll("circle.node")
      .data(nodesOut)
      //.call(force.drag)
	  .enter().append("g")
      .attr("class", "node")
      .call(force.drag)
	  .on('click', connectedNodes) //highlightfunctionality
	  .on('touchstart', connectedNodes)
	  ;	

/*function touchEvent(){

//d3.event.preventDefault();
d3.select(d3.event.target)
      .on("touchmove", force.drag(d3.select(this)))
      //.on("touchend", touchend)
	  ;	

connectedNodes();
}
*/

	
var browsercheck = browser.search("Firefox");
var browsercheck2 = browser.search("IE");
if(browsercheck == -1 && browsercheck2 == -1){
	
var defs = node.append('svg:defs');

//make avatar as pattern
  defs.append("svg:pattern")
    .attr("id", "avatar")
	.attr("x", config.avatar_size/2)
    .attr("y", config.avatar_size/2)
    .attr("width", config.avatar_size+"px")
    .attr("height", config.avatar_size+"px")
    .attr("patternUnits", "userSpaceOnUse")
	.append("svg:image")
    .attr("x", 0)
    .attr("y", 0)
	.attr("width", config.avatar_size+"px")
    .attr("height", config.avatar_size+"px")
    .attr("xlink:href", function(d, i) { 
		  if (kennisGroep[i] == 0) {return photoLink[i];}})
	;

//fill circle node with pattern
    node.append("circle")
		.attr("cx", 0)
        .attr("cy", 0)
        .attr("r", function (d, i){ if (kennisGroep[i] == 1) 
			{ return scale(kennisGroepSize[i])+"px"}
			else { return config.avatar_size/2+"px";
			}})
		.style("fill", function (d,i){
			if (kennisGroep[i] == 0) { return "url(#avatar)"}
			else { return kennisGroepColor[i]}})
			;
}else{
		
	node.append("circle")
		.attr("cx", 0)
        .attr("cy", 0)
        .attr("r", function (d, i){ if (kennisGroep[i] == 1) 
			{ return scale(kennisGroepSize[i])+"px"}
			else { return config.avatar_size/2+"px";
			}})
		.style("fill", function (d,i){
			if (kennisGroep[i] == 0) { return "none"}
			else { return kennisGroepColor[i]}})
			;
				
	node.append("svg:image")
    //.attr("x", 0)
    //.attr("y", 0)
	.attr("x", -config.avatar_size/2)
    .attr("y", -config.avatar_size/2)
	.attr("width", config.avatar_size+"px")
    .attr("height", config.avatar_size+"px")
    .attr("xlink:href", function(d, i) { 
		  return photoLink[i];} )
	;
}

	 node.append("svg:text")
	  .text(function(d) { return d.name; })
	  .attr("x", 22)
     // .attr("y", ".35em")
	  ;
	  
	force.on("tick", function() {
	
	node.attr("x", function(d) { return d.x = Math.max(r, Math.min(w - r, d.x)); })
		.attr("y", function(d) { return d.y = Math.max(r, Math.min(h - r, d.y)); });
	
	link.attr("x1", function(d) { return d.source.x; })
		.attr("y1", function(d) { return d.source.y; })
		.attr("x2", function(d) { return d.target.x; })
		.attr("y2", function(d) { return d.target.y; });
	
	node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
	});
	
	
//Search functionality
var optArray = [];
for (var i = 0; i < nodesOut.length; i++) {
	optArray.push(nodesOut[i].name);
}

optArray = optArray.sort();
$(function () {
	$("#search").autocomplete({
		source: optArray
	});
});


function searchNode() {
	//find the node
	console.log("searchfunctionnn");
	var selectedVal = document.getElementById('search').value;
	var node = svg.selectAll(".node");
	if (selectedVal == "none") {
		node.style("stroke", "white").style("stroke-width", "1");
	} else {
		var selected = node.filter(function (d, i) {
			return d.name != selectedVal;
		});
		selected.style("opacity", "0");
		var link = svg.selectAll(".link")
		link.style("opacity", "0");
		d3.selectAll(".node, .link").transition()
			.duration(5000)
			.style("opacity", 1);
	}
}




d3.select(window).on('resize', resize); 

function resize(){
	w = parseInt(d3.select("#graph").style("width")),
	h = parseInt(d3.select("#graph").style("height"))-50;}

resize();


		  
</script>

<script>
    var datat = <?php echo $num_rows ?>;
	var checkstring = "phpcode.php?" + datat;
	console.log(checkstring);
	
	setInterval(function(){
   	$.ajax({
    data: 'datat=' + datat,
    url: 'phpcode.php',
    method: 'POST', // or GET
    success: function(msg) {
        //alert(msg);
		if (msg != datat){
			location.reload(true);
		}
    }
});
   }, 5000);
</script>

</body>
</html>