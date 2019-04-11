<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_content.php
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

// Function    : html_content_list()
// Description : Used in the admin page to show a list of content chunks
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_content_list(){

	global $db_tableprefix;
	global $results_per_page;
	
	// Content
	$con = db_connect();

	$search = $_REQUEST["search"];

	if ($search==""){
		$sql = "SELECT COUNT(DISTINCT ".$db_tableprefix."Content.nContentId) AS nCount FROM ".$db_tableprefix."Content"
			." INNER JOIN ".$db_tableprefix."ContentSecurity ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentSecurity.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."ContentType ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentType.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."ContentSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."ContentSecurity.cView='x' OR ".$db_tableprefix."ContentSecurity.cAdd='x' OR ".$db_tableprefix."ContentSecurity.cEdit='x' OR ".$db_tableprefix."ContentSecurity.cDelete='x' OR ".$db_tableprefix."ContentSecurity.cApprove='x')";
	} else {
		$sql = "SELECT COUNT(DISTINCT ".$db_tableprefix."Content.nContentId) AS nCount FROM ".$db_tableprefix."Content"
			." INNER JOIN ".$db_tableprefix."ContentSecurity ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentSecurity.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."ContentType ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentType.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."ContentSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."Content.cTitle LIKE '%".$search."%' OR ".$db_tableprefix."Content.cBody LIKE '%".$search."%' OR ".$db_tableprefix."Content.cContentKey LIKE '%".$search."%')"
			." AND (".$db_tableprefix."ContentSecurity.cView='x' OR ".$db_tableprefix."ContentSecurity.cAdd='x' OR ".$db_tableprefix."ContentSecurity.cEdit='x' OR ".$db_tableprefix."ContentSecurity.cDelete='x' OR ".$db_tableprefix."ContentSecurity.cApprove='x')";
	}
	
	$result = mysql_query($sql,$con);
	
	if ($_GET["list_from"]!=""){
		$list_from = $_GET["list_from"];
	} else {
		$list_from = "0";
	}
	
	if ($result!=false){
		$row = mysql_fetch_array($result);
		$count = $row["nCount"];
		$html_pagelinks = "List Results (".$count." records in total) : ";
		
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
			$html_link = "<a href='admin.php?action=content_list&list_from=".$start."&search=".$search."'>".($start+1)." to ".($end+1)."</a>";
			if ($i==$list_from){
				$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
			} else {
				$html_pagelinks .= $html_link."&nbsp;";
			}
		}
	}
	
	if ($search==""){
		$sql = "SELECT DISTINCT ".$db_tableprefix."Content.nContentId,".$db_tableprefix."Content.cContentKey,".$db_tableprefix."Content.cTitle,".$db_tableprefix."Content.cApproved,".$db_tableprefix."UserTypeMember.nUserId,".$db_tableprefix."Content.nContentTypeId,".$db_tableprefix."ContentType.cContentTypeName AS cTypeName,".$db_tableprefix."ContentType.cApprovalRequired AS cApprovalReqd FROM ".$db_tableprefix."Content"
			." INNER JOIN ".$db_tableprefix."ContentSecurity ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentSecurity.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."ContentType ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentType.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."ContentSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."ContentSecurity.cView='x' OR ".$db_tableprefix."ContentSecurity.cAdd='x' OR ".$db_tableprefix."ContentSecurity.cEdit='x' OR ".$db_tableprefix."ContentSecurity.cDelete='x' OR ".$db_tableprefix."ContentSecurity.cApprove='x')"
			." ORDER BY cTitle"
			." LIMIT ".$list_from.",".$results_per_page;
	} else {
		$sql = "SELECT DISTINCT ".$db_tableprefix."Content.nContentId,".$db_tableprefix."Content.cContentKey,".$db_tableprefix."Content.cTitle,".$db_tableprefix."Content.cApproved,".$db_tableprefix."UserTypeMember.nUserId,".$db_tableprefix."Content.nContentTypeId,".$db_tableprefix."ContentType.cContentTypeName AS cTypeName,".$db_tableprefix."ContentType.cApprovalRequired AS cApprovalReqd FROM ".$db_tableprefix."Content"
			." INNER JOIN ".$db_tableprefix."ContentSecurity ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentSecurity.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."ContentType ON ".$db_tableprefix."Content.nContentTypeId=".$db_tableprefix."ContentType.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember ON ".$db_tableprefix."ContentSecurity.nUserTypeId=".$db_tableprefix."UserTypeMember.nUserTypeId"
			." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_SESSION["cms_userid"]
			." AND (".$db_tableprefix."ContentSecurity.cView='x' OR ".$db_tableprefix."ContentSecurity.cAdd='x' OR ".$db_tableprefix."ContentSecurity.cEdit='x' OR ".$db_tableprefix."ContentSecurity.cDelete='x' OR ".$db_tableprefix."ContentSecurity.cApprove='x')"
			." AND (".$db_tableprefix."Content.cTitle LIKE '%".$search."%' OR ".$db_tableprefix."Content.cBody LIKE '%".$search."%' OR ".$db_tableprefix."Content.cContentKey LIKE '%".$search."%')"
			." ORDER BY cTitle"
			." LIMIT ".$list_from.",".$results_per_page;
	}
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_content.png' width='48' height='52' title='CMS Content'></td>\n"
		."<td class='cms_huge'>Content List</td>\n"
		."</tr></table>\n";

	$result = mysql_query($sql,$con);
	if ($result!=false){

		// link to add new content
		$html.="<br><div><a href='admin.php?action=content_add' class='cms_button_thin'>Add New Content</a></div><br>\n";

		$html .= "<table border='0' cellspacing='1' bgcolor='#aaaabb' cellpadding='3'>\n"
			."<tr><td colspan='6' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Content List</b></td></tr>\n"
			."<tr><td colspan='6' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks." (".$count." pieces of content)</td></tr>\n";
		
		// show the search
		$html .= "<tr><td colspan='6' bgcolor='#ffffff' class='cms_small'>"
			."<form method='POST' action='admin.php?action=content_list&from=".$list_from."'>\n"
			."Search (Keys, Titles, Bodies) <input class='cms_text' type='text' name='search' value='".$search."' size='30'>\n"
			."<input type='submit' value='Search' class='cms_button'>\n"
			."</form>\n"
			."</td></tr>\n";
		
		$html .= "<tr>"
			."<td bgcolor='#dddddd' class='cms_small'>Key</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Title</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Type</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Approval<br>Required</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Approval<br>Received</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Control</td>"
			."</tr>\n";
			
		while($row=@mysql_fetch_array($result)){
			
			$html_controls = "";
			
			// find out what permissions the logged in user has for each piece of content
			$a_perm = get_user_content_permissions($_SESSION["cms_userid"],$row["nContentId"]);			
			if ($a_perm["edit"]!=""){
				$html_controls .= "&nbsp;<a class='cms_button_thin' href='admin.php?action=content_edit&contentid=".$row["nContentId"]."'>Edit</a>";
			}
			if ($a_perm["delete"]!=""){
				$html_controls .= "&nbsp;<a class='cms_button_thin' href='admin.php?action=content_del&contentid=".$row["nContentId"]."'>Del</a>";
			}

			// for all users
			$html_controls .= "&nbsp;&nbsp;&nbsp;<a class='cms_button_thin' href='admin.php?action=content_list&list_from=".$list_from."&spc=".$row["nContentId"]."'>?</a>";

			// make the content bold if spc is on
			if ($_GET["spc"]==$row["nContentId"] && $_GET["spc"]!=""){
				$b_start = "<b>";
				$b_end = "</b>";
			} else {
				$b_start = "";
				$b_end = "";
			}
		
			$html.="<tr>"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cContentKey"]).$b_end."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cTitle"]).$b_end."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cTypeName"]).$b_end."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cApprovalReqd"]).$b_end."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$b_start.stripslashes($row["cApproved"]).$b_end."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$html_controls."</td></tr>\n";
			
			// check to see if we should be showing the content of this page
			if ($_GET["spc"]!="" && $_GET["spc"]==$row["nContentId"]){
				// show a row of the content that is hooked in to this page
				$sql = "SELECT pc.nPageId,pg.cPageKey,pc.nTemplateElementId,pc.nIndex,pg.cPageKey,pg.cTitle,pg.nPageTypeId FROM ".$db_tableprefix."PageContent pc"
					." INNER JOIN ".$db_tableprefix."Pages pg ON pc.nPageId=pg.nPageId"
					." WHERE pc.cContentKey='".$row["cContentKey"]."'"
					." ORDER BY pg.cTitle,pc.nTemplateElementId,pc.nIndex";
				$page_result = mysql_query($sql,$con);
				if ($page_result!=false){
					$html .= "<tr><td colspan='6' bgcolor='#eeeeee'>"
						."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' width='100%'>\n"
						."<tr><td colspan='4' bgcolor='#bbbbcc' class='cms_small'><b>Pages containing this content...</b></td></tr>"
						."<tr>"
						."<td bgcolor='#ccccdd' class='cms_small'>Page Key</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Page Title</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Element</td>"
						."<td bgcolor='#ccccdd' class='cms_small'>Index</td>"
						."</tr>";
					while($page_row=@mysql_fetch_array($page_result)){
						$html .= "<tr>";
						
						if (can_user_edit_this_pagetype($page_row["nPageTypeId"])!=""){
							$html .= "<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=page_edit&pageid=".$page_row["nPageId"]."'>".stripslashes($page_row["cPageKey"])."</a></td>";
						} else {
							$html .= "<td bgcolor='#ffffff' class='cms_small'>".stripslashes($page_row["cPageKey"])."</td>";
						}
						
						$html .= "<td bgcolor='#ffffff' class='cms_small'>".stripslashes($page_row["cTitle"])."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($page_row["nTemplateElementId"])."</td>"
							."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($page_row["nIndex"])."</td>"
							."</tr>\n";
					}
					$html .= "</table>"
						."</td></tr>\n";
				} else {
					$html .= "<tr><td colspan='6'>".$sql."</td></tr>\n";
				}
			}
		}		
		$html.="</table>\n";
		
	} else {
		$html="<li class='cms_small'>Problem with SQL<br>[".$sql."]</li>\n";
	}
	$html .= "</div>\n";

	return $html;
}


// Function    : html_contenttype_list()
// Description : Used in the admin page to show a list of content types
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_contenttype_list(){

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
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."ContentType";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
			$html_pagelinks = "List Results (".$count." records in total) : ";
			for($i=0;$i<=$count;$i+=$results_per_page){
				$start = $i;
				if ($i>=($count-$results_per_page)){
					$start = $i;
					$end = $count-1;
				} else {
					$start = $i;
					$end = $i+$results_per_page-1;
				}
				$html_link = "<a href='admin.php?action=contenttype_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_contenttypes.png' width='48' height='52' title='CMS Content Types'></td>\n"
			."<td class='cms_huge'>Content Type List</td>\n"
			."</tr></table>\n";

		// Content
		$sql = "SELECT * FROM ".$db_tableprefix."ContentType ORDER BY cContentTypeName LIMIT ".$list_from.",".$results_per_page;
		$result = mysql_query($sql,$con);

		if ($result!=false){

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='3' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Content Type List</b></td></tr>\n"
				."<tr><td colspan='3' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>Name</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Approval Req</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";

			while($row=@mysql_fetch_array($result)){
				$html.="<tr>"
					."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cContentTypeName"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cApprovalRequired"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small'>"
					."<a href='admin.php?action=contenttype_edit&contenttypeid=".$row["nContentTypeId"]."' class='cms_button_thin'>Edit</a>"
					."&nbsp;<a href='admin.php?action=contenttype_del&contenttypeid=".$row["nContentTypeId"]."' class='cms_button_thin'>Remove</a>"
					."</td></tr>\n";
			}

			$html .= "<tr><td colspan='3' bgcolor='#ffffff' align='right'><a href='admin.php?action=contenttype_add' class='cms_button_thin'>Add New Content</a></td></tr>\n"
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

// Function    : html_contenttype_add()
// Description : Used in the admin page to show a contenttype add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_contenttype_add(){

	if ($_SESSION["cms_admin"]!=""){
	
		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_contenttypes.png' width='48' height='52' title='CMS Content Type Add'></td>\n"
			."<td class='cms_huge'>Add Content Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=contenttype_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Content Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>File</td><td bgcolor='#ffffff'><input type='text' name='file' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Function</td><td bgcolor='#ffffff'><input type='text' name='function' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Approval Required</td><td bgcolor='#ffffff'><input type='checkbox' name='approval_required' value='x'></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Add ContentType'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_contenttype_edit()
// Description : Used in the admin page to show a contenttype edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_contenttype_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."ContentType WHERE nContentTypeId=".$_GET["contenttypeid"];
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			$row = mysql_fetch_array($result);
		}

		if ($row["cApprovalRequired"]!=""){
			$approval_required_checked = "checked";
		} else {
			$approval_required_checked = "";
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_contenttypes.png' width='48' height='52' title='CMS Content Type Edit'></td>\n"
			."<td class='cms_huge'>Edit Content Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=contenttype_edit'>\n"
			."<input type='hidden' name='contenttypeid' value='".$row["nContentTypeId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Content Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='80' class='cms_text' value='".stripslashes($row["cContentTypeName"])."'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Function</td><td bgcolor='#ffffff'><input type='text' name='function' size='80' class='cms_text' value='".stripslashes($row["cFunction"])."'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Approval Required</td><td bgcolor='#ffffff'><input type='checkbox' name='approval_required' value='x' ".$approval_required_checked."></td></tr>\n"
			."  <tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' class='cms_button' value='Make Changes'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		// also put the table in showing the ContentSecurity entries
		$sql = "SELECT nContentSecurityId,".$db_tableprefix."ContentSecurity.nUserTypeId,".$db_tableprefix."ContentSecurity.nContentTypeId,cUserTypeName,cView,cAdd,cEdit,cDelete,cApprove"
			." FROM ".$db_tableprefix."ContentSecurity"
			." INNER JOIN ".$db_tableprefix."UserType ON ".$db_tableprefix."ContentSecurity.nUserTypeId=".$db_tableprefix."UserType.nUserTypeId"
			." WHERE nContentTypeId=".$_GET["contenttypeid"]
			." ORDER BY cUserTypeName";

		$result = mysql_query($sql,$con);

		$html .= "<form method='POST' action='admin_exec.php?action=contentsecurity_edit'>\n"
			."<input type='hidden' name='contenttypeid' value='".$_GET["contenttypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>"
			."<tr><td colspan='7' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Content Type Security</b></td></tr>\n"
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
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_view_".$row["nContentSecurityId"]."' type='checkbox' ".$view_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_add_".$row["nContentSecurityId"]."' type='checkbox' ".$add_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_edit_".$row["nContentSecurityId"]."' type='checkbox' ".$edit_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_delete_".$row["nContentSecurityId"]."' type='checkbox' ".$delete_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_approve_".$row["nContentSecurityId"]."' type='checkbox' ".$approve_checked." value='x'></td>\n"
					."<td bgcolor='#ffaa00' class='cms_small'><input name='chkbox_remove_".$row["nContentSecurityId"]."' type='checkbox'></td>"
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

		// now make the form to set metadata properties for content

		$html .= "<form method='POST' action='admin_exec.php?action=contenttype_prop_edit'>\n"
			."<input type='hidden' name='contenttypeid' value='".$_GET["contenttypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='8' background='images/grad_bg.gif'><b>Content Type Properties</b></td></tr>\n";

		$sql = "SELECT * FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypeId=".$_GET["contenttypeid"]." ORDER BY nSortIndex";
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
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_contentpropid' type='hidden' value='".$prop_row["nContentTypePropertyId"]."'><input name='row".$i."_name' type='text' class='cms_text' size='12' value='".$name."'></td>"
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

// Function    : html_content_add()
// Description : Used in the admin page to show a content add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_content_add(){

	global $db_tableprefix;

	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_content.png' width='48' height='52' title='CMS Content Add'></td>\n"
		."<td class='cms_huge'>Add Content Form</td>\n"
		."</tr></table>\n";
			
	if (can_user_add_content()!=""){
		
		// prepare contenttype html select
		$a_contenttypes = get_user_add_contenttypes();
		if (is_array($a_contenttypes)){
			$sql = "SELECT * FROM ".$db_tableprefix."ContentType WHERE nContentTypeId IN (".implode(",",$a_contenttypes).") ORDER BY cContentTypeName";
		} else {
			$sql = "SELECT * FROM ".$db_tableprefix."ContentType ORDER BY cContentTypeName";
		}

		$con = db_connect();
		$contenttype_select = "<select name='contenttypeid' class='cms_small'>";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			while ($row=@mysql_fetch_array($result)){
				$contenttype_select .= "<option value='".$row["nContentTypeId"]."'>".$row["cContentTypeName"]."</option>";
			}
		}
		$contenttype_select .= "</select>\n";

		// next, prepare template dropdown
		$template_select = "<select name='template' class='cms_small'><option value='0'>None(default)</option>";
		$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE cType='content' ORDER BY cTitle";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			while ($row=@mysql_fetch_array($result)){
				$template_select.="<option value='".$row["nTemplateId"]."'>".$row["cTitle"]."</option>";
			}
		}
		$template_select.="</select>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=content_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Content Form</b></td></tr>\n"
			."  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Content Type & Identification</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Key</td><td bgcolor='#ffffff'><input type='text' name='contentkey' size='50' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Content Type</td><td bgcolor='#ffffff'>".$contenttype_select."</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Template</td><td bgcolor='#ffffff'>".$template_select."</td></tr>\n"
			."  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Scripted Content Function Call</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Function</td><td bgcolor='#ffffff'><input type='text' name='function' size='50' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Timed Content Parameters</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Start</td><td bgcolor='#ffffff'><input type='text' name='start' size='50' value='0000-00-00 00:00:00' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>End</td><td bgcolor='#ffffff'><input type='text' name='end' size='50' value='0000-00-00 00:00:00' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Basic Content</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='80' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Body</td><td bgcolor='#ffffff'><textarea cols='100' rows='30' name='body' class='cms_text'></textarea></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Add Content'></td></tr>\n"
			."</table>\n"
			."</form>\n";

	} else {
		
		$html = "<p align='center' class='cms_small'><span class='cms_huge'>Sorry</span><br>You have insufficient rights to add any content.</p>\n";
	}

	$html .= "</div>\n";
	
	return $html;

}


// Function    : html_content_edit()
// Description : Used in the admin page to show a content editing form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_content_edit(){

	global $db_tableprefix;
	global $site_url;
	
	$con = db_connect();

	$sql = "SELECT * FROM ".$db_tableprefix."Content WHERE nContentId=".$_GET["contentid"].";";
	$result = mysql_query($sql,$con);
	if ($result!=false) {
		$row = mysql_fetch_array($result);

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Content Edit'></td>\n"
			."<td class='cms_huge'>Edit Content Form</td>\n"
			."</tr></table>\n";
				
		if (can_user_edit_this_contenttype($row["nContentTypeId"])!=""){
		
			// prepare contenttype html select
			$a_contenttypes = get_user_add_contenttypes();
			if (is_array($a_contenttypes)){
				$sql = "SELECT * FROM ".$db_tableprefix."ContentType WHERE nContentTypeId IN (".implode(",",$a_contenttypes).") ORDER BY cContentTypeName";
			} else {
				$sql = "SELECT * FROM ".$db_tableprefix."ContentType ORDER BY cContentTypeName";
			}
			$contenttype_select = "<select name='contenttypeid' class='cms_small'>";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				while ($ct_row=@mysql_fetch_array($result)){
					if ($row["nContentTypeId"]==$ct_row["nContentTypeId"]) {
						$selected = "selected";
					} else {
						$selected = "";
					}
					$contenttype_select.="<option value='".$ct_row["nContentTypeId"]."' ".$selected.">".$ct_row["cContentTypeName"]."</option>";
				}
			}
			$contenttype_select.="</select>\n";

			// next, prepare template dropdown
			$template_select = "<select name='template' class='cms_small'><option value='0'>None(default)</option>";
			$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE cType='content' ORDER BY cTitle";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				while ($t_row=@mysql_fetch_array($result)){
					if ($row["nTemplateId"]==$t_row["nTemplateId"]){
						$selected = "selected";
					} else {
						$selected = "";
					}
					$template_select.="<option value='".$t_row["nTemplateId"]."' ".$selected.">".$t_row["cTitle"]."</option>";
				}
			}
			$template_select.="</select>\n";

			// prepare approve select
			$approve_select = "<select name='approve' class='cms_small'>\n";
			if ($row["cApproved"]=="x"){
				$html_approve = "Approved";
				$approve_select .= "<option value=''>Unapproved</option>\n"
					."<option value='x' selected>Approved</option>\n";
			} else {
				$html_approve = "Not Approved";
				$approve_select .= "<option value='' selected>Unapproved</option>\n"
					."<option value='x'>Approved</option>\n";
			}
			$approve_select .= "</select>\n";

			// prepare the metadata fields
			$sql = "SELECT DISTINCT ctp.nContentTypePropertyId,ctp.cPropertyName,ctp.cDataType,cd.*"
				." FROM ".$db_tableprefix."ContentTypeProperties ctp"
				." LEFT OUTER JOIN ".$db_tableprefix."ContentData cd ON (cd.nPropertyId=ctp.nContentTypePropertyId AND cd.nContentId=".$row["nContentId"].")"
				." WHERE ctp.nContentTypeId=".$row["nContentTypeId"]; //." AND cd.nContentId=".$row["nContentId"];

			$result = mysql_query($sql,$con);
			if ($result!=false){
                
				$html_md .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";
				$html_md .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
				if (mysql_num_rows($result)>0){	
					$i=0;
					while($prop_row =@ mysql_fetch_array($result)){
						$i++;
						switch ($prop_row["cDataType"]){
							case "nDataInt":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."<input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'></td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "nDataBigInt":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "dDataDate":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "bDataBoolean":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "nDataFloat":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "cDataVarchar":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
							case "cDataMediumText":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='5' class='cms_text'>".$prop_row[$prop_row["cDataType"]]."</textarea></td></tr>\n";
								break;
							case "bDataBlob":
								$html_md .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_dataid' value='".$prop_row["nContentDataId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".$prop_row[$prop_row["cDataType"]]."' class='cms_text'></td></tr>\n";
								break;
						}

						$html_md .= "";
					}
				} else {
					// no rows
					$html_md .= "<tr><td bgcolor='#dddddd' class='cms_small' colspan='2'>This content type has no metadata fields.</td></tr>\n";
				}
			} else {
				$html_md .= "<tr><td bgcolor='#dddddd' class='cms_small' colspan='2'>Problem with SQL [".$sql."]</td></tr>\n";
			}

			$html .= "<form method='POST' action='admin_exec.php?action=content_edit'>\n"
				."<input type='hidden' name='contentid' value='".$_GET["contentid"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."  <tr><td bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Content Form</b></td></tr>\n";
				
			// draw some tabs
			$html .= "  <tr><td bgcolor='#eeeeee' class='cms_small'>&nbsp;"
				."<a href='#' onClick=\"showTab('tab1')\"><b>Basic</b></a>"
				."&nbsp;<a href='#' onClick=\"showTab('tab2')\"><b>Advanced</b></a>"
				."&nbsp;<a href='#' onClick=\"showTab('tab3')\"><b>Metadata</b></a>&nbsp;"
				."</td></tr>\n";
			
			$html .= "  <tr><td bgcolor='#ffffff'><div id='contentarea'></div>\n";

			$html .= "<div id='tab1' style='position:absolute;left:0px;top:0px;visibility:hidden;'>"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Key</td><td bgcolor='#ffffff'><input type='text' name='contentkey' size='50' value='".stripslashes($row["cContentKey"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='80' value='".stripslashes($row["cTitle"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Body</td><td bgcolor='#ffffff'><textarea cols='100' rows='30' name='body' class='cms_text'>".htmlspecialchars(stripslashes($row["cBody"]))."</textarea></td></tr>\n"
				."</table>\n"
				."</div>\n";
			
			$html .= "<div id='tab2' style='position:absolute;left:0px;top:0px;visibility:hidden;'>"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Content Type</td><td bgcolor='#ffffff'>".$contenttype_select."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Content Template</td><td bgcolor='#ffffff'>".$template_select."</td></tr>\n";
			$perm = get_user_content_permissions($_SESSION["cms_userid"],$_GET["contentid"]);
			if ($perm["approve"]=="x"){
				$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>Approval</td><td bgcolor='#ffffff'>".$approve_select."</td></tr>\n";
			} else {
				$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>Approval</td><td bgcolor='#ffffff'>".$html_approved."</td></tr>\n";
			}
			$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>Function</td><td bgcolor='#ffffff'><input type='text' name='function' size='50' value='".stripslashes($row["cFunction"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Start</td><td bgcolor='#ffffff'><input type='text' name='start' size='50' value='".stripslashes($row["dStart"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>End</td><td bgcolor='#ffffff'><input type='text' name='end' size='50' value='".stripslashes($row["dEnd"])."' class='cms_text'></td></tr>\n"
				."</table>\n"
				."</div>\n";
			
			$html .= "<div id='tab3' style='position:absolute;left:0px;top:0px;visibility:hidden;'>"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				.$html_md
				."</table>\n"
				."</div>\n";
			
			$html .= "</td></tr>\n";
				
				
			$html .= "  <tr><td bgcolor='#ffffff' align='right'><input type='submit' class='cms_button' value='Make Changes'></td></tr>\n"
				."</table>\n"
				."</form>\n";
		
			$html .= "<script>\n\n"
				."function showTab(sTab){\n"
				."  positionTabs();\n"
				."	document.getElementById('tab1').style.visibility='hidden';\n"
				."  document.getElementById('tab2').style.visibility='hidden';\n"
				."  document.getElementById('tab3').style.visibility='hidden';\n"
				."  var sContentArea = '<div style=\"width:' + document.getElementById(sTab).offsetWidth + 'px; height:' + document.getElementById(sTab).offsetHeight + 'px;\">&nbsp;</div>';\n"
				."  document.getElementById('contentarea').innerHTML = sContentArea;\n"
				."  document.getElementById(sTab).style.visibility='visible';\n"
				."}\n\n"
				."function showTab1(){\n"
				."  showTab('tab1');\n"
				."}\n\n"
				."function get_x(obj){\n"
				."	var xpos = 0;\n"
				."	if (obj.offsetParent){\n"
				."		while (obj.offsetParent){\n"
				."			xpos += obj.offsetLeft;\n"
				."			obj = obj.offsetParent;\n"
				."		}\n"
				."	}\n"
				."	else if (obj.x) xpos += obj.x;\n"
				."	return xpos;\n"
				."}\n\n"
				."function get_y(obj){\n"
				."	var ypos = 0;\n"
				."	if (obj.offsetParent){\n"
				."		while (obj.offsetParent){\n"
				."			ypos += obj.offsetTop;\n"
				."			obj = obj.offsetParent;\n"
				."		}\n"
				."	}\n"
				."	else if (obj.y) ypos += obj.y;\n"
				."	return ypos;\n"
				."}\n"
				."\n"
				."function positionTabs(){\n"
				."  var xpos = get_x(document.getElementById('contentarea'));\n"
				."  var ypos = get_y(document.getElementById('contentarea'));\n"
				."  document.getElementById('tab1').style.top=ypos;\n"
				."  document.getElementById('tab1').style.left=xpos;\n"
				."  document.getElementById('tab2').style.top=ypos;\n"
				."  document.getElementById('tab2').style.left=xpos;\n"
				."  document.getElementById('tab3').style.top=ypos;\n"
				."  document.getElementById('tab3').style.left=xpos;\n"
				."}\n"
				."customFunc = showTab1;\n"
				."</script>\n";
				
		} else {
			$html .= "<br><div class='cms_small'><span class='cms_huge'>Sorry!</span><br>You have insufficient privilages to edit the requested content.</div>\n";
		}
		
		$html .= "</div>\n";

	} else {
		print "<li class='cms_small'>Could not find record [".$sql."]</li>\n";
	}
	return $html;
}


// Function    : html_content_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_content_del(){

	global $db_tableprefix;

	$con = db_connect();
	$sql = "SELECT nContentId,cContentKey,cTitle FROM ".$db_tableprefix."Content WHERE nContentId=".$_GET["contentid"];
	$result = mysql_query($sql,$con);
	if ($result!=false){
		$row = mysql_fetch_array($result);

		$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=content_del&contentid=".$row["nContentId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm Content Deletion</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the content detailed below. (if not, use your browser's back button)</td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Key</td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cContentKey"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Title</td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cTitle"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' value='Remove' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form></div>\n";
	} else {
		// problem getting record from database
		$html = "<div class='cms_small'>Problem with SQL [".$sql."]</div>\n";
	}

	return $html;
}

// Function    : html_contenttype_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_contenttype_del(){

	global $db_tableprefix;

	$con = db_connect();
	$sql = "SELECT nContentTypeId,cContentTypeName,cFunction,cApprovalRequired FROM ".$db_tableprefix."ContentType WHERE nContentTypeId=".$_GET["contenttypeid"];
	$result = mysql_query($sql,$con);
	if ($result!=false){
		$row = mysql_fetch_array($result);

		$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=contenttype_del&contenttypeid=".$row["nContentTypeId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm ContentType Deletion</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the content type detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'><b>ContentType</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cContentTypeName"]."</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
			."</table>\n"
			."</form></div>\n";

	} else {

		// problem getting the record from the database
		$html = "<div class='cms_small'>Problem with the SQL [".$sql."]</div>\n";

	}

	return $html;
}


function html_content_search(){

	global $db_tableprefix;
	
	$con = db_connect();
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_content.png' width='48' height='52' title='Content Search'></td>\n"
		."<td class='cms_huge'>Content Search</td>\n"
		."</tr></table>\n";

	// the document add form has two stages - asking for a document type, then asking for the details
	if ($_GET["contenttypeid"]!=""){
		
		// show the search form

		// get the properties that need to be filled for the doctype
		$sql = "SELECT * FROM ".$db_tableprefix."ContentTypeProperties"
			." WHERE nContentTypeId=".$_GET["contenttypeid"];

		$result = mysql_query($sql,$con);
		if ($result!=false){

		$html .= "<form enctype='multipart/form-data' method='POST' action='admin.php?action=content_search&contenttypeid=".$_GET["contenttypeid"]."'>\n"
			."<input type='hidden' name='contenttypeid' value='".$_GET["contenttypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Content Search Form</b></td></tr>\n";

			if (mysql_num_rows($result)>0){

				$html .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
				$html .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

				$i=0;
				while($prop_row =@ mysql_fetch_array($result)){
					$i++;
					switch ($prop_row["cDataType"]){
						case "nDataInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataBigInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "dDataDate":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "bDataBoolean":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataFloat":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataVarchar":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataMediumText":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='5' class='cms_text'>".stripslashes($_POST["prop".$i."_value"])."</textarea></td></tr>\n";
							break;
						case "bDataBlob":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nContentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
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

					$sql = "SELECT DISTINCT con.nContentId FROM ".$db_tableprefix."Content con"
						." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON con.nContentTypeId=cs.nContentTypeId"
						." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
						." INNER JOIN ".$db_tableprefix."ContentTypeProperties ctp ON con.nContentTypeId=ctp.nContentTypeId"
						." INNER JOIN ".$db_tableprefix."ContentData cd ON (ctp.nContentTypePropertyId=cd.nPropertyId AND cd.nContentId=con.nContentId)"
						." WHERE ctp.nContentTypePropertyId=".$propid[$j]." AND cd.".$datatype[$j]."=".$value[$j]
						." AND um.nUserId=".$_SESSION["cms_userid"]
						." AND (cs.cView='x' OR cs.cEdit='x' OR cs.cDelete='x' OR cs.cApprove='x')";
					
					
					
					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$k++;
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nContentId"];									
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
				
				foreach($a_overall_result as $contentid){
					
					// get the document info from the document table
					$sql = "SELECT usr.cUsername,con.* FROM ".$db_tableprefix."Content con"
						." INNER JOIN ".$db_tableprefix."Users usr ON con.nUserAdded=usr.nUserId"
						." WHERE nContentId=".$contentid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result);
							
							$html .= "<tr>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["nContentId"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'><a href='admin.php?action=content_edit&contentid=".$contentid."'>".$row["cContentKey"]."</a></td>"
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
				$html .= "<div class='cms_small'>No Contents Found</div>";				
			}
			//$html .= "<li>Result : ".$result;
			
		}
		
	} else {

		$html .= "<p class='cms_small'>Before you can search for a piece of content, you need to choose a content type from the list below (this is required because content types dictate the fields that are attached to the content).</p>";
		
		// show a pick list of document types (that the logged in user can add)
		$sql = "SELECT * FROM ".$db_tableprefix."ContentType ORDER BY cContentTypeName";
		$result = mysql_query($sql,$con);
		if($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<li class='cms_normal'><a href='admin.php?action=content_search&contenttypeid=".$row["nContentTypeId"]."'>".stripslashes($row["cContentTypeName"])."</a></li>\n";
				}
			}
		}
	}

	$html .= "</div>\n";

	return $html;
}
?>