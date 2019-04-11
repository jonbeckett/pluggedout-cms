<?php

function html_headlines($pageid,$pagetypeid){
	
	global $db_tableprefix;
	
	$html = "<div class='menu_heading'>NEWS</div>\n";
	
	$con = db_connect();
	$sql = "SELECT * FROM ".$db_tableprefix."Content co"
		." INNER JOIN ".$db_tableprefix."ContentType ct ON co.nContentTypeId=ct.nContentTypeId"
		." WHERE co.nContentTypeId=9 AND co.cApproved='x' ORDER BY dAdded DESC LIMIT 3";
		
	$result = mysql_query($sql,$con);
	if ($result!=false){
	
		while ($row =@ mysql_fetch_array($result)){
		
			$contentid = stripslashes($row["nContentId"]);
			$title = stripslashes($row["cTitle"]);
			$body = strip_tags(stripslashes($row["cBody"]));
			
			$dadded = stripslashes($row["dAdded"]);
			$sysdate = strtotime($dadded);
			$dateadded = date("D jS M Y",$sysdate);
			
			// grab first few words of body
			$a_body = split("\n",$body);
			$tagline = "";
			$i=-1;
			while($tagline==""){
				$i++;
				if($a_body[$i]!=""){
					$tagline = $a_body[$i];
				}
			}
			
			$html .= "<ul class='menu'>\n"
			."<li class='menu'><a class='menu_link' href='index.php?pk=news&item=".$contentid."'><b>".strtoupper($title)."</b></a><br>".$tagline."<br><span style='color:#A9A1A4'>".$dateadded."</span></li>\n"
			."</ul>\n";
		
		}
		
	} else {
		$html .= "<div class='menu'>Problem returning news feed.</div>\n";
	}
	
	return $html;
}


function html_newsitems($pageid,$pagetypeid){
	
	global $db_tableprefix;
	$con = db_connect();
	
	
	if (isset($_GET["item"])){

		$html = "<h1>News Item</h1><br>\n";
		
		$sql = "SELECT * FROM ".$db_tableprefix."Content WHERE nContentId=".$_GET["item"];
		$result = mysql_query($sql,$con);
		if ($result!=false){

			$row = mysql_fetch_array($result);

			$contentid = stripslashes($row["nContentId"]);
			$title = stripslashes($row["cTitle"]);
			$body = stripslashes($row["cBody"]);

			$dadded = stripslashes($row["dAdded"]);
			$sysdate = strtotime($dadded);
			$dateadded = date("D jS M Y",$sysdate);

			$html .= "<h2>".$title."<br><p>".$dateadded."</p></h2>\n"
				.$body;
			

		} else {
			$html .= "<div class='menu'>Problem returning news feed.</div>\n";
		}

	} else {
	
		$html = "<h1>News and Events</h1><br>\n";
		
		$sql = "SELECT * FROM ".$db_tableprefix."Content co"
			." INNER JOIN ".$db_tableprefix."ContentType ct ON co.nContentTypeId=ct.nContentTypeId"
			." WHERE co.nContentTypeId=9 AND co.cApproved='x' ORDER BY dAdded DESC LIMIT 3";
		$result = mysql_query($sql,$con);
		if ($result!=false){

			while ($row =@ mysql_fetch_array($result)){

				$contentid = stripslashes($row["nContentId"]);
				$title = stripslashes($row["cTitle"]);
				$body = strip_tags(stripslashes($row["cBody"]));

				$dadded = stripslashes($row["dAdded"]);
				$sysdate = strtotime($dadded);
				$dateadded = date("D jS M Y",$sysdate);

				// grab first few words of body
				$a_body = split("\n",$body);
				$tagline = "";
				$i=-1;
				while($tagline==""){
					$i++;
					if($a_body[$i]!=""){
						$tagline = $a_body[$i];
					}
				}

				$html .= "<h2><a href='index.php?pk=news&item=".$contentid."'>".$title."</a></h2>\n"
					."<p>".$tagline."</p>\n";
				
			}

		} else {
			$html .= "<div class='menu'>Problem returning news feed.</div>\n";
		}

	}
	
	return $html;
}
?>