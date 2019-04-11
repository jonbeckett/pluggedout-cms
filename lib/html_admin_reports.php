<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_reports.php
#= Version: 0.4.9 (2005-05-10)
#= Author : Jonathan Beckett
#= Email  : jonbeckett@pluggedout.com
#= Website: http://www.pluggedout.com/index.php?pk=dev_cms
#= Support: http://www.pluggedout.com/development/forums/viewforum.php?f=14
#===========================================================================
#= Copyright (c) 2005 Jonathan Beckett
#= You are free to use and modify this script as long as this header
#= section stays intact. This file is part of CMS.
#=
#= This program is free software; you can redistribute it and/or modify
#= it under the terms of the GNU General Public License as published by
#= the Free Software Foundation; either version 2 of the License, or
#= (at your option) any later version.
#=
#= This program is distributed in the hope that it will be useful,
#= but WITHOUT ANY WARRANTY; without even the implied warranty of
#= MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#= GNU General Public License for more details.
#=
#= You should have received a copy of the GNU General Public License
#= along with CMS files; if not, write to the Free Software
#= Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#===========================================================================
*/


function html_report_page_updates(){
	
	global $db_tableprefix;
	
	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_reports.png' title='CMS Reports'></td>\n"
		."<td class='cms_huge'>Page Update Report</td>\n"
		."</tr></table>\n";
	
	$html .= "<div class='cms_small' style='padding:20px'>This report shows the pages in the site, sorted in descending order of the last content change made on those pages.</div>\n";
	
	$con = db_connect();
	
	$sql = "SELECT pg.cPageKey,pg.cTitle,MAX(co.dEdited) AS dLastEdit FROM ".$db_tableprefix."Pages pg"
		." INNER JOIN ".$db_tableprefix."PageContent pc ON pg.nPageId=pc.nPageId"
		." INNER JOIN ".$db_tableprefix."Content co ON pc.cContentKey=co.cContentKey"
		." GROUP BY pg.cPageKey,pg.cTitle"
		." ORDER BY dLastEdit DESC";
	
	$result = mysql_query($sql,$con);
	
	$html .= "<div style='padding:20px;'>\n";
	
	$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>"
		."<tr>"
		."<td bgcolor='#ccccdd' class='cms_small'><b>Page Key</b></td>"
		."<td bgcolor='#ccccdd' class='cms_small'><b>Page Title</b></td>"
		."<td bgcolor='#ccccdd' class='cms_small'><b>Last Content Change</b></td>"
		."</tr>\n";
		
	if($result!=false){
		while ($row =@ mysql_fetch_array($result)){
			$html .= "<tr>"
				."<td bgcolor='#ffffff' class='cms_small'>".$row["cPageKey"]."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$row["cTitle"]."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$row["dLastEdit"]."</td>"
				."</tr>\n";
		}
	} else {
		$html .= "Problem with ".$sql;
	}
	$html .= "</table>\n";
	
	$html .= "</div>\n";
	
	return $html;
}

// show the audit trail
function html_report_audit_trail(){

	// Fields : nAuditTrailId,dAdded,nUserId,cPage,cData,cIPAddress,cSessionId
	
	global $db_tableprefix;
	global $results_per_page;
	
	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_reports.png' title='CMS Reports'></td>\n"
		."<td class='cms_huge'>Audit Trail Report</td>\n"
		."</tr></table>\n";
	
	$con = db_connect();

	// Build content list to limit search result
	$sql = 	"SELECT COUNT(nAuditTrailId) AS nCount"
		." FROM ".$db_tableprefix."AuditTrail";
		
	$result = mysql_query($sql,$con);
	
	if ($_GET["list_from"]!=""){
		$list_from = $_GET["list_from"];
	} else {
		$list_from = "0";
	}
	
	if ($result!=false){
		$row = mysql_fetch_array($result);
		$count = 100; //$row["nCount"];
		$html_pagelinks = "List Results : ";
		for($i=0;$i<$count;$i+=$results_per_page){
			$start = $i;
			if ($i>=($count-$results_per_page)){
				$start = $i;
				$end = $count-1;
			} else {
				$start = $i;
				$end = $i+$results_per_page-1;
			}
			$html_link = "<a href='admin.php?action=report_audit_trail&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
			if ($i==$list_from){
				$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
			} else {
				$html_pagelinks .= $html_link."&nbsp;";
			}
		}
	}

	
	$sql = "SELECT at.nAuditTrailId,at.dAdded,at.nUserId,at.cPage,at.cIPAddress,at.cSessionId,at.cData,usr.cUsername"
		." FROM ".$db_tableprefix."AuditTrail at"
		." INNER JOIN ".$db_tableprefix."Users usr ON at.nUserId=usr.nUserId"
		." ORDER BY at.nAuditTrailId DESC"
		." LIMIT ".$list_from.",".$results_per_page;

	$result = mysql_query($sql,$con);
	if ($result!=false){

		$html .= "<div style='padding:20px;'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='6' bgcolor='#aaaabb' class='cms_small'><b>Audit Trail</b></td></tr>\n"
			."<tr><td colspan='6' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
			."<tr>"
			."<td bgcolor='#bbbbcc' class='cms_small' rowspan='2'>ID</td>"
			."<td bgcolor='#bbbbcc' class='cms_small'>Date</td>"
			."<td bgcolor='#bbbbcc' class='cms_small'>User</td>"
			."<td bgcolor='#bbbbcc' class='cms_small'>Page</td>"
			."<td bgcolor='#bbbbcc' class='cms_small'>IP Address</td>"
			."<td bgcolor='#bbbbcc' class='cms_small'>Session ID</td>"
			."</tr><tr><td colspan='5' bgcolor='#bbbbcc' class='cms_small'>Data</td></tr>\n";

		while ($row =@ mysql_fetch_array($result)){
		
			// if data is too long, represent it with something different
			if(strlen($row["cData"])>1024){
				$data = "Data too big to show...";
			} else {
				$data = $row["cData"];
			}
			
			$html .= "<tr>"
				."<td rowspan='2' bgcolor='#ffffff' class='cms_normal'><b>".stripslashes($row["nAuditTrailId"])."</b></td>"
				."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["dAdded"])."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cUsername"])."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cPage"])."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cIPAddress"])."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cSessionId"])."</td>"
				."</tr>\n"
				."<tr><td bgcolor='#eeeeee' class='cms_small' colspan='5'>".ereg_replace("\n","<br>",htmlspecialchars(stripslashes($data)))."</td></tr>\n";
		}

		$html .= "</table>\n"
			."</div>\n";
	} else {
		$html .= $sql;
	}

	return $html;
}

// Statistics Report
function html_report_statistics(){
	// Fields : nStatisticsId,nUserId,cPageKey,dView,cIPAddress,cSessionId
	
	global $db_tableprefix;
	global $results_per_page;

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_reports.png' title='CMS Reports'></td>\n"
		."<td class='cms_huge'>Statistics Report</td>\n"
		."</tr></table>\n";
		
	$html .= "<ul>\n"
		."<li class='cms_normal'><a href='admin.php?action=report_statistics&report=site_hits'>Site Hit Report</a></li>\n"
		."<li class='cms_normal'><a href='admin.php?action=report_statistics&report=page_hits'>Page Hit Report</a></li>\n"
		."<li class='cms_normal'><a href='admin.php?action=report_statistics&report=visitors'>Visitor Report</a></li>\n"
		."</ul>\n";
	
	$con = db_connect();

	if ($_GET["report"]=="site_hits"){
		$sql = "SELECT COUNT(nStatisticId) AS nHits,DAYOFMONTH(dView) AS nDay,MONTH(dView) AS nMonth,YEAR(dView) AS nYear"
			." FROM ".$db_tableprefix."Statistics"
			." GROUP BY DAYOFMONTH(dView),MONTH(dView),YEAR(dView)"
			." ORDER BY YEAR(dView) DESC,MONTH(dView) DESC,DAYOFMONTH(dView) DESC"
			." LIMIT 30";
		$result = mysql_query($sql,$con);
		
		// quickly go through the results and put them in an array
		// (so we can figure out which is the biggest to do the bar graphs)
		if ($result!=false){
			$i = 0;
			$top_hits = 0;
			while ($row =@ mysql_fetch_array($result)){
				$i++;
				$hit_row[$i]["nYear"]=$row["nYear"];
				$hit_row[$i]["nMonth"]=$row["nMonth"];
				$hit_row[$i]["nDay"]=$row["nDay"];
				$hit_row[$i]["nHits"]=$row["nHits"];
				if ($row["nHits"]>$top_hits){
					$top_hits=$row["nHits"];
				}
			}
		}
		
		if ($result!=false){
			$html .= "<div style='padding:20px;'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='3' bgcolor='#aaaabb' class='cms_small'><b>Site Hits (Last 30 days)</b></td></tr>\n"
				."<tr>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Date</td>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Hits</td>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Bar</td>"
				."</tr>\n";
			for ($i=1;$i<=count($hit_row);$i++){
				
				$size = ($hit_row[$i]["nHits"] / $top_hits) * 100;
				
				$bar = "<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>"
					."<td height='12' width='".$size."' bgcolor='#aaaabb'><img src='images/pix1.gif'></td>"
					."<td height='12' width='".(100-$size)."'><img src='images/pix1.gif'></td>"
					."</tr></table>\n";
				
				$html .= "<tr>"
					."<td bgcolor='#ffffff' class='cms_small' align='left'>".stripslashes($hit_row[$i]["nYear"])."-".stripslashes($hit_row[$i]["nMonth"])."-".stripslashes($hit_row[$i]["nDay"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small' align='right'>".stripslashes($hit_row[$i]["nHits"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small' align='left'>".$bar."</td>"
					."</tr>\n";
					
			}
			$html .= "</table>\n";
		}
	}
	
	if ($_GET["report"]=="page_hits"){
		$sql = "SELECT COUNT(nStatisticId) AS nHits,cPageKey"
			." FROM ".$db_tableprefix."Statistics"
			." GROUP BY cPageKey"
			." ORDER BY cPageKey";
			
		$result = mysql_query($sql,$con);
		
		// quickly go through the results and put them in an array
		// (so we can figure out which is the biggest to do the bar graphs)
		if ($result!=false){
			$i = 0;
			$top_hits = 0;
			while ($row =@ mysql_fetch_array($result)){
				$i++;
				$hit_row[$i]["cPageKey"]=$row["cPageKey"];
				$hit_row[$i]["nHits"]=$row["nHits"];
				if ($row["nHits"]>$top_hits){
					$top_hits=$row["nHits"];
				}
			}
		}
		
		if ($result!=false){
			$html .= "<div style='padding:20px;'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='3' bgcolor='#aaaabb' class='cms_small'><b>Site Hits (Last 30 days)</b></td></tr>\n"
				."<tr>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Page</td>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Hits</td>"
				."<td bgcolor='#bbbbcc' class='cms_small'>Bar</td>"
				."</tr>\n";
			for ($i=1;$i<=count($hit_row);$i++){
				
				$size = ($hit_row[$i]["nHits"] / $top_hits) * 100;
				
				$bar = "<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>"
					."<td height='12' width='".$size."'%' bgcolor='#aaaabb'><img src='images/pix1.gif'></td>"
					."<td height='12' width='".(100-$size)."%'><img src='images/pix1.gif'></td>"
					."</tr></table>\n";
				
				$html .= "<tr>"
					."<td bgcolor='#ffffff' class='cms_small' align='left'>".stripslashes($hit_row[$i]["cPageKey"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small' align='right'>".stripslashes($hit_row[$i]["nHits"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small' align='left'>".$bar."</td>"
					."</tr>\n";
					
			}
			$html .= "</table>\n";
		}
	}
	
	
	if ($_GET["report"]=="visitors"){
	
		if ($_GET["s"]!=""){

			$sql = "SELECT * FROM ".$db_tableprefix."Statistics WHERE cSessionId='".mysql_escape_string($_GET["s"])."' ORDER BY dView LIMIT 50";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				$html .= "<div style='padding:20px;'>\n"
					."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
					."<tr><td colspan='3' bgcolor='#aaaabb' class='cms_small'><b>Pages visited by ".stripslashes($_GET["s"])."</b></td></tr>\n"
					."<tr>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Date/Time</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Page Key</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>IP Address</td>"
					."</tr>\n";
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["dView"])."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cPageKey"])."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cIPAddress"])."</td>"
						."</tr>\n";
				}
				$html .= "</table>\n";

			} else {
				header("Location: admin.php?action=problem");
			}

		}

		if ($_GET["i"]!=""){

			$sql = "SELECT * FROM ".$db_tableprefix."Statistics WHERE cIPAddress='".mysql_escape_string($_GET["i"])."' ORDER BY dView";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				$html .= "<div style='padding:20px;'>\n"
					."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
					."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small'><b>Pages visited by ".stripslashes($_GET["i"])."</b></td></tr>\n"
					."<tr>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Date/Time</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Page Key</td>"
					."</tr>\n";
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["dView"])."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cPageKey"])."</td>"
						."</tr>\n";
				}
				$html .= "</table>\n";

			} else {
				header("Location: admin.php?action=problem");
			}

		}

		if (!isset($_GET["s"]) && !isset($_GET["i"])){
			// initial view - last 50 pages per sessionid

			$sql = "SELECT COUNT(nStatisticId) AS nCount,MAX(dView) AS dLastView,cSessionId,cIPAddress FROM ".$db_tableprefix."Statistics GROUP BY cSessionId,cIPAddress DESC";
			$result = mysql_query($sql,$con);

			if ($_GET["list_from"]!=""){
				$list_from = $_GET["list_from"];
			} else {
				$list_from = "0";
			}

			if ($result!=false){
				$row = mysql_fetch_array($result);
				$count = 100; //mysql_num_rows($result);
				$html_pagelinks = "List Results : ";

				for($i=0;$i<=$count;$i+=$results_per_page){
					$start = $i;
					if ($i>=($count-$results_per_page)){
						$start = $i;
						$end = $count-1;
					} else {
						$start = $i;
						$end = $i+$results_per_page-1;
					}
					$html_link = "<a href='admin.php?action=report_statistics&report=visitors&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
					if ($i==$list_from){
						$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
					} else {
						$html_pagelinks .= $html_link."&nbsp;";
					}
				}
			}

			$sql = "SELECT COUNT(nStatisticId) AS nCount,MAX(dView) AS dLastView,cSessionId,cIPAddress FROM ".$db_tableprefix."Statistics GROUP BY cSessionId,cIPAddress ORDER BY dLastView DESC"
				." LIMIT ".$list_from.",".$results_per_page;

			$result = mysql_query($sql,$con);
			if ($result!=false){

				$html .= "<div style='padding:20px;'>\n"
					."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
					."<tr><td colspan='4' bgcolor='#aaaabb' class='cms_small'><b>Session Hit Statistics</b></td></tr>\n"
					."<tr><td colspan='4' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
					."<tr>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Session ID</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>IP Address</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Hits</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Last Hit</td>"
					."</tr>\n";

				while ($row =@ mysql_fetch_array($result)){
					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=report_statistics&report=visitors&&s=".stripslashes($row["cSessionId"])."'>".stripslashes($row["cSessionId"])."</a></td>"
						."<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=report_statistics&report=visitors&&i=".stripslashes($row["cIPAddress"])."'>".stripslashes($row["cIPAddress"])."</a></td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$row["nCount"]."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$row["dLastView"]."</td>"
						."</tr>\n";
				}

				$html .= "</table>\n"
					."</div>\n";
			} else {
				$html .= $sql;
			}
		}

	}
	
	return $html;
	
}

?>