<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_page.php
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

// Function    : html_page_list()
// Description : Used in the admin page to show a list of pages
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_page_list(){

	global $db_tableprefix;
	global $site_root;
	global $results_per_page;
	
	$con = db_connect();

	$search = $_REQUEST["search"];

	if ($search==""){
		// Build page list to limit search result
		$sql = "SELECT COUNT(DISTINCT ".$db_tableprefix."Pages.nPageId) AS nCount"
			." FROM ".$db_tableprefix."Pages"
			." INNER JOIN ".$db_tableprefix."PageSecurity ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageSecurity.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."PageType ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageType.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."PageSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."PageSecurity.cView='x' OR ".$db_tableprefix."PageSecurity.cAdd='x' OR ".$db_tableprefix."PageSecurity.cEdit='x' OR ".$db_tableprefix."PageSecurity.cDelete='x' OR ".$db_tableprefix."PageSecurity.cApprove='x')";
	} else {
		// Build page list to limit search result
		$sql = "SELECT COUNT(DISTINCT ".$db_tableprefix."Pages.nPageId) AS nCount"
			." FROM ".$db_tableprefix."Pages"
			." INNER JOIN ".$db_tableprefix."PageSecurity ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageSecurity.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."PageType ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageType.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."PageSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."Pages.cPageKey LIKE '%".$search."%' OR ".$db_tableprefix."Pages.cTitle LIKE '%".$search."%')"
			." AND (".$db_tableprefix."PageSecurity.cView='x' OR ".$db_tableprefix."PageSecurity.cAdd='x' OR ".$db_tableprefix."PageSecurity.cEdit='x' OR ".$db_tableprefix."PageSecurity.cDelete='x' OR ".$db_tableprefix."PageSecurity.cApprove='x')";
	}
	

	if ($_GET["list_from"]!=""){
		$list_from = $_GET["list_from"];
	} else {
		$list_from = "0";
	}
	
	$result = mysql_query($sql,$con);
	if ($result!=false){
		$row = mysql_fetch_array($result);
		$count = $row["nCount"];
		$html_pagelinks = "List Results : ";
		
		if ($count<$list_from){
				$list_from = 0;
		}
		
		for($i=0;$i<$count;$i+=$results_per_page){
			$start = $i;
			if ($i>=($count-$results_per_page)){
				$start = $i;
				$end = $count-1;
			} else {
				$start = $i;
				$end = $i+$results_per_page-1;
			}
			$html_link = "<a href='admin.php?action=page_list&list_from=".$start."&search=".$search."'>".($start+1)." to ".($end+1)."</a>";
			if ($i==$list_from){
				$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
			} else {
				$html_pagelinks .= $html_link."&nbsp;";
			}
		}
	}

	if ($search==""){
		$sql = "SELECT DISTINCT ".$db_tableprefix."Pages.nPageId,".$db_tableprefix."Pages.cPageKey,".$db_tableprefix."Pages.cTitle,".$db_tableprefix."Pages.cApproved,".$db_tableprefix."UserTypeMember.nUserId,".$db_tableprefix."PageType.cPageTypeName"
			." FROM ".$db_tableprefix."Pages"
			." INNER JOIN ".$db_tableprefix."PageSecurity ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageSecurity.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."PageType ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageType.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."PageSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."PageSecurity.cView='x' OR ".$db_tableprefix."PageSecurity.cAdd='x' OR ".$db_tableprefix."PageSecurity.cEdit='x' OR ".$db_tableprefix."PageSecurity.cDelete='x' OR ".$db_tableprefix."PageSecurity.cApprove='x')"	
			." ORDER BY cTitle"
			." LIMIT ".$list_from.",".$results_per_page;
	} else {
		$sql = "SELECT DISTINCT ".$db_tableprefix."Pages.nPageId,".$db_tableprefix."Pages.cPageKey,".$db_tableprefix."Pages.cTitle,".$db_tableprefix."Pages.cApproved,".$db_tableprefix."UserTypeMember.nUserId,".$db_tableprefix."PageType.cPageTypeName"
			." FROM ".$db_tableprefix."Pages"
			." INNER JOIN ".$db_tableprefix."PageSecurity ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageSecurity.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."PageType ON ".$db_tableprefix."Pages.nPageTypeId=".$db_tableprefix."PageType.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."PageSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."PageSecurity.cView='x' OR ".$db_tableprefix."PageSecurity.cAdd='x' OR ".$db_tableprefix."PageSecurity.cEdit='x' OR ".$db_tableprefix."PageSecurity.cDelete='x' OR ".$db_tableprefix."PageSecurity.cApprove='x')"	
			." AND (".$db_tableprefix."Pages.cPageKey LIKE '%".$search."%' OR ".$db_tableprefix."Pages.cTitle LIKE '%".$search."%')"
			." ORDER BY cTitle"
			." LIMIT ".$list_from.",".$results_per_page;
	}
	
	//print $sql;
	//$sql = "SELECT * FROM ".$db_tableprefix."Pages ORDER BY cTitle LIMIT ".$list_from.",".$results_per_page;
	$result = mysql_query($sql,$con);
	if ($result!=false){

		$html = "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='Pages'></td>\n"
			."<td class='cms_huge'>Page List</td>\n"
			."</tr></table>\n";

		$html .= "<br><div><a href='admin.php?action=page_add' class='cms_button_thin'>Add New Page</a></div><br>\n";

		$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='7' bgcolor='#aaaabb'  background='images/grad_bg.gif' class='cms_small'><b>Pages</b></td></tr>\n"
			."<tr><td colspan='7' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks." (".$count." pages in total)</td></tr>\n";
		
		// show the search
		$html .= "<tr><td colspan='7' bgcolor='#ffffff' class='cms_small'>"
			."<form method='POST' action='admin.php?action=page_list&from=".$list_from."'>\n"
			."Search (Keys, Titles) <input class='cms_text' type='text' name='search' value='".$search."' size='30'>\n"
			."<input type='submit' value='Search' class='cms_button'>\n"
			."</form>\n"
			."</td></tr>\n";
			
		$html .= "<tr>"
			."<td bgcolor='#dddddd' class='cms_small'>Key</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Title</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Type</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Appr?</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Cache</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Last Cached</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Control</td>"
			."</tr>\n";

		while($row=@mysql_fetch_array($result)){

			// figure out cached status
			if (file_exists($site_root."/cache/".$row["cPageKey"].".htm")){
				$last_cached = date("Y-m-d H:i:s",filemtime($site_root."/cache/".$row["cPageKey"].".htm"));
			} else {
				$last_cached = "&nbsp;";
			}

			// make the content bold if spc is on
			if ($_GET["spc"]==$row["nPageId"] && $_GET["spc"]!=""){
				$b_start = "<b>";
				$b_end = "</b>";
			} else {
				$b_start = "";
				$b_end = "";
			}

			// figure out what the user can do (controls section)
			$a_perms = get_user_page_permissions($_SESSION["cms_userid"],$row["nPageId"]);
			$html_controls = "";
			if ($a_perms["view"]!=""){
				$html_controls .= "&nbsp;<a href='index.php?pk=".$row["cPageKey"]."' target='_blank' class='cms_button_thin'>View</a>";
			}
			if ($a_perms["edit"]!=""){
				$html_controls .= "&nbsp;<a href='admin.php?action=page_edit&pageid=".$row["nPageId"]."' class='cms_button_thin'>Edit</a>";
			}
			if ($a_perms["delete"]!=""){
				$html_controls .= "&nbsp;<a href='admin.php?action=page_del&pageid=".$row["nPageId"]."' class='cms_button_thin'>Del</a>";
			}
			if ($a_perms["approve"]!=""){
				$html_controls .= "&nbsp;<a href='admin.php?action=page_approve&pageid=".$row["nPageId"]."' class='cms_button_thin'>Appr</a>";
			}

			// admin only commands
			if ($_SESSION["cms_admin"]!=""){
				$html_controls .= "&nbsp;&nbsp;&nbsp;<a href='admin_exec.php?action=page_cache_clear&pagekey=".$row["cPageKey"]."' class='cms_button_thin'>Clear</a>"
					."&nbsp;<a href='admin_exec.php?action=page_cache&pagekey=".$row["cPageKey"]."' class='cms_button_thin'>Cache</a>";
			}
			
			// free to all commands
			$html_controls .= "&nbsp;&nbsp;&nbsp;<a href='admin.php?action=page_list&list_from=".$list_from."&spc=".$row["nPageId"]."' class='cms_button_thin'>?</a>";
			
			$html.="<tr>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cPageKey"]).$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cTitle"]).$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cPageTypeName"]).$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cApproved"]).$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cCache"]).$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.$last_cached.$b_end."</td>\n"
				."<td bgcolor='#ffffff' class='cms_small'>".$html_controls."</td>\n"
				."</tr>\n";
				
			// check to see if we should be showing the content of this page
			if ($_GET["spc"]!="" && $_GET["spc"]==$row["nPageId"]){
				// show a row of the content that is hooked in to this page
				$sql = "SELECT pc.nTemplateElementId,pc.nIndex,co.nContentId,co.cContentKey,co.cTitle,co.nContentTypeId FROM ".$db_tableprefix."PageContent pc"
					." INNER JOIN ".$db_tableprefix."Content co ON pc.cContentKey=co.cContentKey"
					." WHERE pc.nPageId=".$row["nPageId"]
					." ORDER BY pc.nTemplateElementId,pc.nIndex";
				$page_result = mysql_query($sql,$con);
				if ($page_result!=false){
					$html .= "<tr><td colspan='7' bgcolor='#eeeeee'>"
						."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' width='100%'>\n"
						."<tr><td colspan='4' bgcolor='#bbbbcc' class='cms_small'><b>Content on Page...</b></td></tr>"
						."<tr>"
						."<td bgcolor='#ccccdd' class='cms_small'>Element</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Index</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Key</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Title</td>"
						."</tr>";
					while($content_row=@mysql_fetch_array($page_result)){
						$html .= "<tr>"
							."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($content_row["nTemplateElementId"])."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($content_row["nIndex"])."</td>";
						
						if (can_user_edit_this_contenttype($content_row["nContentTypeId"])!=""){
							$html .= "<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=content_edit&contentid=".$content_row["nContentId"]."'>".stripslashes($content_row["cContentKey"])."</a></td>";
						} else {
							$html .= "<td bgcolor='#ffffff' class='cms_small'>".stripslashes($content_row["cContentKey"])."</td>";
						}							
						
						$html .= "<td bgcolor='#ffffff' class='cms_small'>".stripslashes($content_row["cTitle"])."</td>"
							."</tr>\n";
					}
					$html .= "</table>"
						."</td></tr>\n";
				}
			}
		}

		$html .= "</table>\n";

		$html .= "</div>\n";
	}
	return $html;
}


// Function    : html_page_add()
// Description : Used in the admin page to show a page add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_page_add(){

	global $db_tableprefix;
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Page Add'></td>\n"
		."<td class='cms_huge'>Add Page Form</td>\n"
		."</tr></table>\n";

	if (can_user_add_pages()!=""){

		$con = db_connect();

		// build dropdown for template
		$html_template_select = "<select name='templateid' class='cms_small'>\n";
		$sql = "SELECT nTemplateId,cTitle FROM ".$db_tableprefix."Templates WHERE cType='page' ORDER BY cTitle";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html_template_select .= "<option value='".$row["nTemplateId"]."'>".$row["cTitle"]."</option>\n";
				}
			}
		}
		$html_template_select .= "</select>\n";


		// build a dropdown for the auto-populate function
		$html_select .= "<select name='base_on_page' class='cms_small'>\n<option value='0'>Do Not Auto-Populate</option>\n";
		$sql = "SELECT nPageId,cPageKey,cTitle FROM ".$db_tableprefix."Pages ORDER BY cPageKey,cTitle";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html_select .= "<option value='".$row["nPageId"]."'>".$row["cPageKey"]." - ".$row["cTitle"]."</option>\n";
				}
			}
		}
		$html_select .= "</select>\n";

		// prepare pagetype html select
		$a_pagetypes = get_user_add_pagetypes();
		if (is_array($a_pagetypes)){
			$sql = "SELECT * FROM ".$db_tableprefix."PageType WHERE nPageTypeId IN (".implode(",",$a_pagetypes).") ORDER BY cPageTypeName";
		} else {
			$sql = "SELECT * FROM ".$db_tableprefix."PageType ORDER BY cPageTypeName";
		}
		
		$tresult = mysql_query($sql,$con);
		$html_pagetype_select = "<select name='pagetypeid' class='cms_small'>\n";
		if ($tresult!=false) {
			while ($trow=@mysql_fetch_array($tresult)){
				$html_pagetype_select .= "<option value='".$trow["nPageTypeId"]."'>".stripslashes($trow["cPageTypeName"])."</option>\n";
			}
		} else {
			$html .= "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
		}
		$html_pagetype_select .= "</select>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=page_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Page Form</b></td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>Key</td><td bgcolor='#ffffff'><input type='text' name='key' size='50' class='cms_text'></td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>PageType</td><td bgcolor='#ffffff'>".$html_pagetype_select."</td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>Notes</td><td bgcolor='#ffffff'><textarea cols='80' rows='5' name='notes' class='cms_text'></textarea></td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>Template</td><td bgcolor='#ffffff'>".$html_template_select."</td></tr>\n"
			."  <tr><td class='cms_small' bgcolor='#dddddd'>Copy PageContent</td><td bgcolor='#ffffff' class='cms_small'>".$html_select." (pre-fill content from an existing page)</td></tr>\n"
			."  <tr><td colspan='2' bgcolor='#ffffff' class='cms_small' align='right'><input type='submit' class='cms_button' value='Add Page'></td></tr>\n"
			."</table>\n"
			."</form>\n";

	} else {
	
		$html = "<p align='center' class='cms_small'><span class='cms_huge'>Sorry</span><br>You have insufficient rights to add any pages.</p>\n";
	}

	$html .= "</div>\n";

	return $html;
}

// Function    : html_page_edit()
// Description : Used in the admin page to show a page edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_page_edit(){

	global $db_tableprefix;

	$con = db_connect();

	// Output Page Edit Form
	$sql = "SELECT * FROM ".$db_tableprefix."Pages WHERE nPageId=".$_GET["pageid"].";";
	$result = mysql_query($sql,$con);
	if ($result!=false) {
		$row = mysql_fetch_array($result);

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Page Edit'></td>\n"
			."<td class='cms_huge'>Edit Page Form</td>\n"
			."</tr></table>\n";

		if (can_user_edit_this_pagetype($row["nPageTypeId"])!="") {


			// prepare pagetemplate html select
			$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE cType='page' ORDER BY cTitle";
			$tresult = mysql_query($sql,$con);
			$select_pagetemplate = "<select name='templateid' class='cms_small'>\n<option value='0'>None</option>\n";
			if ($tresult!=false) {
				while ($trow=@mysql_fetch_array($tresult)){
					if ($row["nTemplateId"]==$trow["nTemplateId"]){
						$selected = " selected ";
					} else {
						$selected = "";
					}
					$select_pagetemplate .= "<option value='".$trow["nTemplateId"]."' ".$selected.">".stripslashes($trow["cTitle"])."</option>\n";
				}
			} else {
				$html .= "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
			}
			$select_pagetemplate .= "</select>\n";

			// prepare pagetype html select
			$a_pagetypes = get_user_add_pagetypes();
			if (is_array($a_pagetypes)){
				$sql = "SELECT * FROM ".$db_tableprefix."PageType WHERE nPageTypeId IN (".implode(",",$a_pagetypes).") ORDER BY cPageTypeName";
			} else {
				$sql = "SELECT * FROM ".$db_tableprefix."PageType ORDER BY cPageTypeName";
			}
			$tresult = mysql_query($sql,$con);
			$html_select_pagetype = "<select name='pagetypeid' class='cms_small'>\n";
			if ($tresult!=false) {
				while ($trow=@mysql_fetch_array($tresult)){
					if ($row["nPageTypeId"]==$trow["nPageTypeId"]){
						$selected = " selected ";
					} else {
						$selected = "";
					}
					$html_select_pagetype .= "<option value='".$trow["nPageTypeId"]."' ".$selected.">".stripslashes($trow["cPageTypeName"])."</option>\n";
				}
			} else {
				$html .= "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
			}
			$html_select_pagetype .= "</select>\n";

			$html .= "<form method='POST' action='admin_exec.php?action=page_edit'>\n"
				."<input type='hidden' name='pageid' value='".$_GET["pageid"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."  <tr><td colspan='3' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Page Form</b></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Key</td><td bgcolor='#ffffff'><input type='text' name='key' size='80' value='".stripslashes($row["cPageKey"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='80' value='".stripslashes($row["cTitle"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Type</td><td bgcolor='#ffffff'>".$html_select_pagetype."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Notes</td><td bgcolor='#ffffff'><textarea cols='80' rows='5' name='notes' class='cms_text'>".stripslashes($row["cNotes"])."</textarea></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Template</td><td bgcolor='#ffffff'>".$select_pagetemplate."</td></tr>\n";

			// prepare the metadata fields
			$sql = "SELECT DISTINCT ptp.nPageTypePropertyId,ptp.cPropertyName,ptp.cDataType,pd.*"
				." FROM ".$db_tableprefix."PageTypeProperties ptp"
				." LEFT OUTER JOIN ".$db_tableprefix."PageData pd ON (pd.nPropertyId=ptp.nPageTypePropertyId AND pd.nPageId=".$row["nPageId"].")"
				." WHERE ptp.nPageTypeId=".$row["nPageTypeId"]; //." AND pd.nPageId=".$row["nPageId"];

			$result = mysql_query($sql,$con);
			if ($result!=false){
				if (mysql_num_rows($result)>0){
					$html .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
					$html .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";
					$i=0;
					while($prop_row =@ mysql_fetch_array($result)){
						$i++;
						switch ($prop_row["cDataType"]){
							case "nDataInt":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."<input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'></td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "nDataBigInt":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "dDataDate":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "bDataBoolean":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "nDataFloat":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "cDataVarchar":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($prop_row[$prop_row["cDataType"]])."' class='cms_text'></td></tr>\n";
								break;
							case "cDataMediumText":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nPageDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='2' class='cms_text'>".stripslashes($prop_row[$prop_row["cDataType"]])."</textarea></td></tr>\n";
								break;
							case "bDataBlob":
								$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($prop_row[$prop_row["cDataType"]])."' class='cms_text'></td></tr>\n";
								break;
						}

						$html .= "";
					}
				} else {
					// no rows
					$html .= "<tr><td bgcolor='#dddddd' class='cms_small' colspan='2'>No rows for [".$sql."]</td></tr>\n";
				}
			} else {
				$html .= "<tr><td bgcolor='#dddddd' class='cms_small' colspan='2'>Problem with SQL [".$sql."]</td></tr>\n";
			}

			$html .= "  <tr><td bgcolor='#ffffff' colspan='3' align='right'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
				."</table>\n"
				."</form>\n";

			// handle page content links
			// prepare content list
			$sql = "SELECT * FROM ".$db_tableprefix."Content ORDER BY cTitle";
			$result = mysql_query($sql,$con);
			$select_options = "<select name='contentkey' class='cms_small'>\n";
			if ($result!=false){
				while ($row =@ mysql_fetch_array($result)){
					$select_options .= "<option value='".$row["cContentKey"]."'>".stripslashes($row["cTitle"])."</option>\n";
				}
			} else {
				$html.= "<p>Problem with SQL [".$sql."]</p>\n";
			}
			$select_options .= "</select>\n";


			// prepare template html select
			$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE cType='pagecontent' ORDER BY cTitle";
			$tresult = mysql_query($sql,$con);
			$select_contenttemplate = "<select name='templateid' class='cms_small'>\n<option value='0'>None (Default)</option>\n";
			if ($tresult!=false) {
				while ($trow=@mysql_fetch_array($tresult)){
					$select_contenttemplate .= "<option value='".$trow["nTemplateId"]."'>".stripslashes($trow["cTitle"])."</option>\n";
				}
			} else {
				$html .= "<p>Problem with SQL [".$sql."]</p>\n";
			}
			$select_contenttemplate .= "</select>\n";

			// Output PageContent Add Form
			$html.= "<form method='POST' action='admin_exec.php?action=pagecontent_add'>\n"
				."<input type='hidden' name='pageid' value='".$_GET["pageid"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."  <tr><td colspan='4' bgcolor='#aaaabb' class='cms_small'  background='images/grad_bg.gif'><b>Add Page Content</b></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Content</td><td bgcolor='#ffffff'>".$select_options."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Content Template</td><td bgcolor='#ffffff'>".$select_contenttemplate."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Page Template Element</td><td bgcolor='#ffffff'><input name='templateelementid' type='text' size='5' value='1' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Index</td><td bgcolor='#ffffff'><input type='text' size='5' name='index' value='1' class='cms_text'></td></tr>\n"
				."  <tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' value='Add Content' class='cms_button'></td></tr>\n"
				."</table>\n"
				."</form>\n";

			// Output PageContent List
			$sql = "SELECT tpc.nPageContentId,tco.nContentId,tpc.cContentKey,tco.cTitle,tpc.nTemplateId,tpc.nTemplateElementId,tpc.nIndex,tpc.nPageId,tpl.cTitle AS cTemplateName"
				." FROM ".$db_tableprefix."PageContent tpc"
				." LEFT OUTER JOIN ".$db_tableprefix."Content tco ON tpc.cContentKey=tco.cContentKey"
				." LEFT OUTER JOIN ".$db_tableprefix."Templates tpl ON tpc.nTemplateId=tpl.nTemplateId"
				." WHERE tpc.nPageId=".$_GET["pageid"]
				." ORDER BY tpc.nTemplateElementId,tpc.nIndex";
				
			$result = mysql_query($sql,$con);
			$html.= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td bgcolor='#aaaabb' colspan='6' class='cms_small'  background='images/grad_bg.gif'><b>Existing Page Content</b></td></tr>\n"
				."<tr>"
				."  <td bgcolor='#dddddd' class='cms_small'>Key</td>"
				."  <td bgcolor='#dddddd' class='cms_small'>Content Title</td>"
				."  <td bgcolor='#dddddd' class='cms_small'>Template</td>"
				."  <td bgcolor='#dddddd' class='cms_small'>Elem</td>"
				."  <td bgcolor='#dddddd' class='cms_small'>Index</td>"
				."  <td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";
			if ($result!=false){
				if (mysql_num_rows($result)>0){
					while ($row =@ mysql_fetch_array($result)){
						$html.= "<tr>"
							."<td bgcolor='#ffffff' class='cms_small'>".$row["cContentKey"]."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cTitle"])."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".($row["cTemplateName"]!="" ? $row["cTemplateName"] : "None")."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".$row["nTemplateElementId"]."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".$row["nIndex"]."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>"
							."<a href='admin_exec.php?action=pagecontent_up&pagecontentid=".$row["nPageContentId"]."&pageid=".$row["nPageId"]."' class='cms_button_thin'>Up</a>"
							."&nbsp;<a href='admin_exec.php?action=pagecontent_down&pagecontentid=".$row["nPageContentId"]."&pageid=".$row["nPageId"]."' class='cms_button_thin'>Down</a>"
							."&nbsp;<a href='admin.php?action=pagecontent_edit&pagecontentid=".$row["nPageContentId"]."' class='cms_button_thin'>Edit PageContent</a>"
							."&nbsp;<a href='admin.php?action=content_edit&contentid=".$row["nContentId"]."' class='cms_button_thin'>Edit Content</a>"
							."&nbsp;<a href='admin_exec.php?action=pagecontent_del&pageid=".$row["nPageId"]."&pagecontentid=".$row["nPageContentId"]."' class='cms_button_thin'>Remove</a></td>"
							."</tr>\n";
					}
				} else {
					$html.= "<tr><td colspan='6' bgcolor='#ffffff' class='cms_small'>No Records</td></tr>\n";
				}
			} else {
				$html.= "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
			}
			$html.= "</table>\n";

				
		} else {
			// user cannot edit this page type
			$html .= "<br><div class='cms_small'><span class='cms_huge'>Sorry!</span><br>You have insufficient privilages to edit the requested page.</div>\n";
		}
		
	} else {
		$html.= "<p>Could not find record [".$sql."]</p>\n";
	}


	$html .= "</div>\n";

	return $html;
}


// Function    : html_pagecontent_edit()
// Description : Used in the admin page to show a pagecontent edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_pagecontent_edit(){

	global $db_tableprefix;

	$pagecontentid=$_GET["pagecontentid"];

	$con = db_connect();

	// get the existing data
	$sql = "SELECT * FROM ".$db_tableprefix."PageContent WHERE nPageContentId=".$pagecontentid;
	$result = mysql_query($sql,$con);

	if ($result!=false){

		$pagecontent_row = mysql_fetch_array($result);

		// prepare page list
		$sql = "SELECT * FROM ".$db_tableprefix."Pages ORDER BY cTitle";
		$select_result = mysql_query($sql,$con);
		$select_pages = "<select name='pageid' class='cms_small'>\n";
		if ($select_result!=false){
			while ($row =@ mysql_fetch_array($select_result)){
				if ($row["nPageId"]==$pagecontent_row["nPageId"]){
					$selected = "selected";
				} else {
					$selected = "";
				}
				$select_pages .= "<option value='".$row["nPageId"]."' ".$selected.">".$row["cTitle"]."</option>\n";
			}
		} else {
			print "<p>Problem with SQL [".$sql."]</p>\n";
		}
		$select_pages .= "</select>\n";

		// prepare content list
		$sql = "SELECT * FROM ".$db_tableprefix."Content ORDER BY cTitle";
		$select_result = mysql_query($sql,$con);
		$select_options = "<select name='contentkey' class='cms_small'>\n";
		if ($select_result!=false){
			while ($row =@ mysql_fetch_array($select_result)){
				if ($row["cContentKey"]==$pagecontent_row["cContentKey"]){
					$selected = "selected";
				} else {
					$selected = "";
				}
				$select_options .= "<option value='".$row["cContentKey"]."' ".$selected.">".stripslashes($row["cTitle"])."</option>\n";
			}
		} else {
			$html="<p>Problem with SQL [".$sql."]</p>\n";
		}
		$select_options .= "</select>\n";

		// prepare template html select
		$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE cType='pagecontent' ORDER BY cTitle";
		$tresult = mysql_query($sql,$con);
		$select_contenttemplate = "<select name='templateid' class='cms_small'>\n<option value='0'>None</option>\n";
		if ($tresult!=false) {
			while ($trow=@mysql_fetch_array($tresult)){
				if ($pagecontent_row["nTemplateId"]==$trow["nTemplateId"]){
					$selected = " selected ";
				} else {
					$selected = "";
				}
				$select_contenttemplate .= "<option value='".$trow["nTemplateId"]."' ".$selected.">".stripslashes($trow["cTitle"])."</option>\n";
			}
		} else {
			$html .= "<p>Problem with SQL [".$sql."]</p>\n";
		}
		$select_contenttemplate .= "</select>\n";

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_content.png' width='48' height='52' title='CMS Page Content'></td>\n"
			."<td class='cms_huge'>Edit Page Content Form</td>\n"
			."</tr></table>\n";		

		$html .= "<form method='POST' action='admin_exec.php?action=pagecontent_edit'>\n"
			."<input type='hidden' name='pagecontentid' value='".$pagecontentid."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'>Edit Page Content Form</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Page</td><td bgcolor='#ffffff'>".$select_pages."</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Content</td><td bgcolor='#ffffff'>".$select_options."</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Content Template</td><td bgcolor='#ffffff'>".$select_contenttemplate."</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Page Template Element</td><td bgcolor='#ffffff'><input name='templateelementid' size='5' type='text' value='".$pagecontent_row["nTemplateElementId"]."' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Index</td><td bgcolor='#ffffff'><input type='text' size='5' name='index' value='".$pagecontent_row["nIndex"]."' class='cms_text'></td></tr>\n"
			."  <tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

	} else {
		$html.="<p>Problem with SQL [".$sql."]</p>\n";
	}
	return $html;
}


// Function    : html_page_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_page_del(){

	global $db_tableprefix;

	$con = db_connect();
	$sql = "SELECT nPageId,cPageKey,cTitle FROM ".$db_tableprefix."Pages WHERE nPageId=".$_GET["pageid"];
	$result = mysql_query($sql,$con);
	if ($result!=false){

		$row = mysql_fetch_array($result);

		$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=page_del&pageid=".$row["nPageId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm Page Deletion</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the Page detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'><b>Key</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cPageKey"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'><b>Title</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cTitle"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
			."</table>\n"
			."</form></div>\n";

	} else {
		// problem getting the record from the database
		$html = "<div class='cms_small'>Problem with the SQL [".$sql."]</div>\n";
	}

	return $html;
}


// Function    : html_pagetype_list()
// Description : Used in the admin page to show a list of content types
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_pagetype_list(){

	global $db_tableprefix;
	global $results_per_page;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();

		if ($_GET["list_from"]!=""){
			$list_from = $_GET["list_from"];
		} else {
			$list_from = "0";
		}
		
		// Build page list to limit search result
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."PageType";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
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
				$html_link = "<a href='admin.php?action=pagetype_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}


		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Page Types'></td>\n"
			."<td class='cms_huge'>Page Type List</td>\n"
			."</tr></table>\n";

		// Content

		$sql = "SELECT * FROM ".$db_tableprefix."PageType ORDER BY cPageTypeName LIMIT ".$list_from.",".$results_per_page;
		$result = mysql_query($sql,$con);

		if ($result!=false){

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>PageType List</b></td></tr>\n"
				."<tr><td colspan='2' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>Name</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";

			while($row=@mysql_fetch_array($result)){
				$html.="<tr>"
					."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cPageTypeName"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small'>"
					."<a href='admin.php?action=pagetype_edit&pagetypeid=".$row["nPageTypeId"]."' class='cms_button_thin'>Edit</a>"
					."&nbsp;<a href='admin.php?action=pagetype_del&pagetypeid=".$row["nPageTypeId"]."' class='cms_button_thin'>Remove</a>"
					."</td></tr>\n";
			}

			$html .= "<tr><td colspan='2' bgcolor='#ffffff' align='right'><a href='admin.php?action=pagetype_add' class='cms_button_thin'>Add New PageType</a></td></tr>\n"
				."</table>\n";

		} else {
			$html="<li class='cms_small'>Problem with SQL<br>[".$sql."]</li>\n";
		}
		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}

// Function    : html_pagetype_edit()
// Description : Used in the admin page to show a pagetype edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2005-01-21
function html_pagetype_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."PageType WHERE nPageTypeId=".$_GET["pagetypeid"];
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			$row = mysql_fetch_array($result);
		}

		if ($row["cApprovalRequired"]!=""){
			$approval_required_checked = "x";
		} else {
			$approval_required_checked = "";
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_document.png' width='48' height='52' title='CMS Page Type Edit'></td>\n"
			."<td class='cms_huge'>Edit Page Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=pagetype_edit'>\n"
			."<input type='hidden' name='pagetypeid' value='".$row["nPageTypeId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Page Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='80' class='cms_text' value='".stripslashes($row["cPageTypeName"])."'></td></tr>\n"
			."  <tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' class='cms_button' value='Make Changes'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		// also put the table in showing the PageSecurity entries
		$sql = "SELECT nPageSecurityId,".$db_tableprefix."PageSecurity.nUserTypeId,".$db_tableprefix."PageSecurity.nPageTypeId,cUserTypeName,cView,cAdd,cEdit,cDelete,cApprove"
			." FROM ".$db_tableprefix."PageSecurity"
			." INNER JOIN ".$db_tableprefix."UserType ON ".$db_tableprefix."PageSecurity.nUserTypeId=".$db_tableprefix."UserType.nUserTypeId"
			." WHERE nPageTypeId=".$_GET["pagetypeid"]
			." ORDER BY cUserTypeName";

		$result = mysql_query($sql,$con);

		$html .= "<form method='POST' action='admin_exec.php?action=pagesecurity_edit'>\n"
			."<input type='hidden' name='pagetypeid' value='".$_GET["pagetypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>"
			."<tr><td colspan='7' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Page Type Security</b></td></tr>\n"
			."<tr>"
			."<td bgcolor='#dddddd' class='cms_small'>UserType</td>"
			."<td bgcolor='#dddddd' class='cms_small'>View</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Add</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Edit</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Delete</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Approve</td>"
			."<td bgcolor='#ffaa00' class='cms_small'>Remove</td>"
			."</tr>\n";

		if ($result!=false){
			while($row=@mysql_fetch_array($result)){
				if ($row["cView"]!=""){$view_checked="checked";} else {$view_checked="";}
				if ($row["cAdd"]!=""){$add_checked="checked";} else {$add_checked="";}
				if ($row["cEdit"]!=""){$edit_checked="checked";} else {$edit_checked="";}
				if ($row["cDelete"]!=""){$delete_checked="checked";} else {$delete_checked="";}
				if ($row["cApprove"]!=""){$approve_checked="checked";} else {$approve_checked="";}
				$html.="<tr>\n"
					."<td bgcolor='#ffffff' class='cms_small'>".$row["cUserTypeName"]."</td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_view_".$row["nPageSecurityId"]."' type='checkbox' ".$view_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_add_".$row["nPageSecurityId"]."' type='checkbox' ".$add_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_edit_".$row["nPageSecurityId"]."' type='checkbox' ".$edit_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_delete_".$row["nPageSecurityId"]."' type='checkbox' ".$delete_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_approve_".$row["nPageSecurityId"]."' type='checkbox' ".$approve_checked." value='x'></td>\n"
					."<td bgcolor='#ffaa00' class='cms_small'><input name='chkbox_remove_".$row["nPageSecurityId"]."' type='checkbox'></td>"
					."</tr>\n";
			}
		} else {
			print "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
		}

		// prepare usertype select
		$usertype_select = "<select name='usertype_new'><option value=''>&nbsp;</option>";
		$sql = "SELECT * FROM ".$db_tableprefix."UserType ORDER BY cUserTypeName";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			while ($row=@mysql_fetch_array($result)){
				$usertype_select.="<option value='".$row["nUserTypeId"]."'>".stripslashes($row["cUserTypeName"])."</option>";
			}
		} else {
			print "<li>Problem with SQL [".$sql."]</li>\n";
		}
		$usertype_select.="</select>\n";

		$html.="<tr>\n"
			."<td bgcolor='#ffaa00' class='cms_small'>".$usertype_select."</td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_view_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_add_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_edit_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_delete_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_approve_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00' class='cms_small'>&laquo;&nbsp;New</td>\n"
			."</tr>\n";

		$html.= "<tr><td colspan='7' align='right' bgcolor='#ffffff'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "<br>\n";

		// now make the form to set metadata properties for page

		$html .= "<form method='POST' action='admin_exec.php?action=pagetype_prop_edit'>\n"
			."<input type='hidden' name='pagetypeid' value='".$_GET["pagetypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='8' background='images/grad_bg.gif'><b>Page Type Properties</b></td></tr>\n";

		$sql = "SELECT * FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypeId=".$_GET["pagetypeid"]." ORDER BY nSortIndex";
		$result = mysql_query($sql,$con);

		if ($result!=false) {

			$html .= "<input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

			// column headings
			$html .= "<tr>"
				."<td bgcolor='#cccccc' class='cms_small'>Name</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Description</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Data Type</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Input Mask</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Mandatory</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Hidden</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Unique</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Delete</td>"
				."</tr>\n";

			$datatypes[1] = "nDataInt";
			$datatypes[2] = "nDataBigInt";
			$datatypes[3] = "dDataDate";
			$datatypes[4] = "bDataBoolean";
			$datatypes[5] = "nDataFloat";
			$datatypes[6] = "cDataVarchar";
			$datatypes[7] = "cDataMediumText";
			$datatypes[8] = "bDataBlob";

			$datatypenames[1] = "Integer";
			$datatypenames[2] = "BigInt";
			$datatypenames[3] = "Date";
			$datatypenames[4] = "Boolean";
			$datatypenames[5] = "Float";
			$datatypenames[6] = "Varchar";
			$datatypenames[7] = "MediumText";
			$datatypenames[8] = "Blob";

			if (mysql_num_rows($result)>0){

				$i = 0;

				while ($prop_row =@ mysql_fetch_array($result)){

					$i++;

					// prepare data
					$name = stripslashes($prop_row["cPropertyName"]);
					$description = stripslashes($prop_row["cPropertyDescription"]);
					$inputmask = stripslashes($prop_row["cInputMask"]);

					// prepare the datatype dropdown for each
					$html_datatype_select = "<select name='row".$i."_datatype'>";
					for ($j=1;$j<=count($datatypes);$j++){
						if ($datatypes[$j]==$prop_row["cDataType"]){
							$sel = "selected";
						} else {
							$sel = "";
						}
						$html_datatype_select .= "<option value='".$datatypes[$j]."' ".$sel.">".$datatypenames[$j]."</option>";
					}
					$html_datatype_select .= "</select>";

					if ($prop_row["bMandatory"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_mandatory = "<input name='row".$i."_mandatory' type='checkbox' value='1' ".$checked.">";

					if ($prop_row["bHidden"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_hidden = "<input name='row".$i."_hidden' type='checkbox' value='1' ".$checked.">";

					if ($prop_row["bUnique"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_unique = "<input name='row".$i."_unique' type='checkbox' value='1' ".$checked.">";

					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_pagepropid' type='hidden' value='".$prop_row["nPageTypePropertyId"]."'><input name='row".$i."_name' type='text' class='cms_text' size='12' value='".$name."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_description' type='text' class='cms_text' size='25' value='".$description."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_datatype_select."</td>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_inputmask' type='text' class='cms_text' size='12' value='".$inputmask."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_mandatory."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_hidden."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_unique."</td>"
						."<td bgcolor='#ffffff' c;ass='cms_small'><input name='row".$i."_del' type='checkbox' value='1'></td>"
						."</tr>\n";
				}

			} else {
				// no rows returned
				$html .= "<tr><td bgcolor='#ffffff' class='cms_small' colspan='8'>No Properties Returned</td></tr>\n";
			}

			// put the final line in to add new entries

			// prepare the datatype dropdown for new lines
			$html_datatype_select = "<select name='new_datatype'>";
			for ($j=1;$j<=count($datatypes);$j++){
				if ($datatypes[$i]==$prop_row["cDataType"]){
					$sel = "selected";
				} else {
					$sel = "";
				}
				$html_datatype_select .= "<option value='".$datatypes[$j]."' ".$sel.">".$datatypenames[$j]."</option>";
			}
			$html_datatype_select .= "</select>";

			$html .= "<tr>"
				."<td bgcolor='#ffaa00'><input name='new_name' type='text' class='cms_text' size='12'></td>"
				."<td bgcolor='#ffaa00'><input name='new_description' type='text' class='cms_text' size='25'></td>"
				."<td bgcolor='#ffaa00'>".$html_datatype_select."</td>"
				."<td bgcolor='#ffaa00'><input name='new_inputmask' type='text' class='cms_text' size='12'></td>"
				."<td bgcolor='#ffaa00'><input name='new_mandatory' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00'><input name='new_hidden' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00'><input name='new_unique' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00' class='cms_small'>&laquo;&nbsp;New</td>"
				."</tr>\n";

		} else {
			// problem with SQL
			$html = "Problem with SQL [".$sql."]\n";
		}
		$html .= "<tr><td bgcolor='#ffffff' colspan='8' align='right'><input type='submit' class='cms_button' value='Make Changes to Properties'></td></tr>\n";
		$html .= "</table>\n";


		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_pagetype_add()
// Description : Used in the admin page to show a contenttype add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_pagetype_add(){

	if ($_SESSION["cms_admin"]!=""){

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_document.png' width='48' height='52' title='CMS Page Type Add'></td>\n"
			."<td class='cms_huge'>Add Page Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=pagetype_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Page Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Add PageType'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}

// Function    : html_pagetype_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_pagetype_del(){

	global $db_tableprefix;

	$con = db_connect();
	$sql = "SELECT nPageTypeId,cPageTypeName FROM ".$db_tableprefix."PageType WHERE nPageTypeId=".$_GET["pagetypeid"];
	$result = mysql_query($sql,$con);
	if ($result!=false){
		$row = mysql_fetch_array($result);

		$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=pagetype_del&pagetypeid=".$row["nPageTypeId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm PageType Deletion</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the page type detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'><b>PageType</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cPageTypeName"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
			."</table>\n"
			."</form></div>\n";

	} else {

		// problem getting the record from the database
		$html = "<div class='cms_small'>Problem with the SQL [".$sql."]</div>\n";

	}

	return $html;
}


function html_page_search(){

	global $db_tableprefix;
	
	$con = db_connect();
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_pages.png' width='48' height='52' title='Page Search'></td>\n"
		."<td class='cms_huge'>Page Search</td>\n"
		."</tr></table>\n";

	// the document add form has two stages - asking for a document type, then asking for the details
	if ($_GET["pagetypeid"]!=""){
		
		// show the search form

		// get the properties that need to be filled for the doctype
		$sql = "SELECT * FROM ".$db_tableprefix."PageTypeProperties"
			." WHERE nPageTypeId=".$_GET["pagetypeid"];

		$result = mysql_query($sql,$con);
		if ($result!=false){

		$html .= "<form enctype='multipart/form-data' method='POST' action='admin.php?action=page_search&pagetypeid=".$_GET["pagetypeid"]."'>\n"
			."<input type='hidden' name='pagetypeid' value='".$_GET["pagetypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Page Search Form</b></td></tr>\n";

			if (mysql_num_rows($result)>0){

				$html .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
				$html .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

				$i=0;
				while($prop_row =@ mysql_fetch_array($result)){
					$i++;
					switch ($prop_row["cDataType"]){
						case "nDataInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataBigInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "dDataDate":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "bDataBoolean":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataFloat":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataVarchar":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataMediumText":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='5' class='cms_text'>".stripslashes($_POST["prop".$i."_value"])."</textarea></td></tr>\n";
							break;
						case "bDataBlob":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nPageTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
					}

					$html .= "";
				}

			}

			$html .= "<tr><td bgcolor='#ffffff' class='small' align='right' colspan='2'><input type='submit' class='cms_button' value='Perform Search'></td></tr>"
				."</table>\n"
				."</form>\n";

		} else {
			// sql failed retrieving properties

		}
			
		if ($_POST["numrows"]!=""){
			
			// do the search!
			
			// make sense of the arrays first
			for($i=1;$i<=$_POST["numrows"];$i++){
				$propid[$i] = $_POST["prop".$i."_propid"];
				$datatype[$i] = $_POST["prop".$i."_datatype"];
				$value[$i] = $_POST["prop".$i."_value"];
			}
			
			$k=0;
			
			for ($j=1;$j<=$i;$j++){
			
				// for each search term, do a query
				
				if ($value[$j]!=""){
				
					// prepare the value appropriately
					$result = "0";
					switch($datatype[$j]){
						case "nDataBigInt":
							if (!is_numeric($value[$j])){
								$result = "-4";
							}
							break;
						case "dDataDate":
							if (!strtotime($value[$j])!=-1){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "bDataBoolean":
							if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
								switch($value){
									case "T":
										$value[$j] = "1";
										break;
									case "F":
										$value[$j] = "0";
										break;
								}
							} else {
								$result = "-4";
							}
							break;
						case "nDataFloat":
							if (!is_numeric($value[$j])){
								$result = "-4";
							}
							break;
						case "cDataVarchar":
							if (!is_string($value[$j])){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "cDataMediumText":
							if (!is_string($value[$j])){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "bDataBlob":
							break;
					}

					$sql = "SELECT DISTINCT pag.nPageId FROM ".$db_tableprefix."Pages pag"
						." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pag.nPageTypeId=ps.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
						." INNER JOIN ".$db_tableprefix."PageTypeProperties ptp ON pag.nPageTypeId=ptp.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."PageData pd ON (ptp.nPageTypePropertyId=pd.nPropertyId AND pd.nPageId=pag.nPageId)"
						." WHERE ptp.nPageTypePropertyId=".$propid[$j]." AND pd.".$datatype[$j]."=".$value[$j]
						." AND um.nUserId=".$_SESSION["cms_userid"]
						." AND (ps.cView='x' OR ps.cEdit='x' OR ps.cDelete='x' OR ps.cApprove='x')";
						
					/*
					$sql = "SELECT DISTINCT pag.nPageId FROM ".$db_tableprefix."Pages pag"
						." INNER JOIN ".$db_tableprefix."PageTypeProperties ptp ON pag.nPageTypeId=ptp.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."PageData pd ON (ptp.nPageTypePropertyId=pd.nPropertyId AND pd.nPageId=pag.nPageId)"
						." WHERE ptp.nPageTypePropertyId=".$propid[$j]." AND pd.".$datatype[$j]."=".$value[$j];
					*/
					
					
					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$k++;
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nPageId"];									
							}
							
							if (is_array($a_single_result)){
							
								// intersect the single result into the overall results
								if ($k==1){
									//print "<li>First result : ".implode(",",$a_single_result);
									$a_overall_result = $a_single_result;
								} else {
									//print "<li>Following result : ".implode(",",$a_single_result);
									$a_overall_result = array_intersect($a_overall_result,$a_single_result);
								}
							
							}

						}
					} else {
						$result = "-2";
						break;
					}
				
				}
				
			}
			
			// we now have an array of documentids that we can use
			// stored in a_overall_result
			if (is_array($a_overall_result)){
				
				$result = implode(",",$a_overall_result);
				
				// loop through the array and present the results to the user
				
				// column headings first
				$html .= "<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>"
					."<tr><td colspan='6' bgcolor='#aaaabb' class='cms_small'><b>Search Results</b></td></tr>\n"
					."<tr>"
					."<td bgcolor='#bbbbcc' class='cms_small'>ID</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Key</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Title</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Date Added</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Added By</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Controls</td>"
					."</tr>\n";
				
				foreach($a_overall_result as $pageid){
					
					// get the document info from the document table
					$sql = "SELECT usr.cUsername,pag.* FROM ".$db_tableprefix."Pages pag"
						." LEFT OUTER JOIN ".$db_tableprefix."Users usr ON pag.nUserAdded=usr.nUserId"
						." WHERE nPageId=".$pageid;
						
					//print "<li>".$sql;
					
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result);
							
							$html .= "<tr>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["nPageId"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=page_edit&pageid=".$pageid."'>".$row["cPageKey"]."</a></td>"
								."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cTitle"])."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["dAdded"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["cUsername"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>&nbsp;</td>"
								."</tr>\n";
								
						}
					}
					
				}
				
				$html .= "</table>\n";
				
			} else {
				$html .= "<div class='cms_small'>No Pages Found</div>";				
			}
			//$html .= "<li>Result : ".$result;
			
		}
		
	} else {

		$html .= "<p class='cms_small'>Before you can search for a page, you need to choose a page type from the list below (this is required because page types dictate the fields that are attached to the page).</p>";
		
		// show a pick list of document types (that the logged in user can add)
		$sql = "SELECT * FROM ".$db_tableprefix."PageType ORDER BY cPageTypeName";
		$result = mysql_query($sql,$con);
		if($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<li class='cms_normal'><a href='admin.php?action=page_search&pagetypeid=".$row["nPageTypeId"]."'>".stripslashes($row["cPageTypeName"])."</a></li>\n";
				}
			}
		}
	}

	$html .= "</div>\n";

	return $html;
}

?>