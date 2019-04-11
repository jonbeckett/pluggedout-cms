<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_template.php
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

// Function    : html_template_list()
// Description : Used in the admin page to show a list of templates
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_template_list(){

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
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."Templates";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
			$html_pagelinks = "List Results : ";
			for($i=0;$i<=$count;$i+=$results_per_page){
				if ($i==$list_from){
					$html_pagelinks .= "<b><a href='admin.php?action=template_list&from=".$i."'>".($i+1)." to ".($i+$results_per_page-1)."</a></b>&nbsp;";
				} else {
					$html_pagelinks .= "<a href='admin.php?action=template_list&from=".$i."'>".($i+1)." to ".($i+$results_per_page-1)."</a>&nbsp;";
				}
			}
		}


		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_templates.png' width='48' height='52' title='CMS Templates'></td>\n"
			."<td class='cms_huge'>Template List</td>\n"
			."</tr></table>\n";

		// Templates

		$sql = "SELECT * FROM ".$db_tableprefix."Templates LIMIT ".$list_from.",".$results_per_page;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$html.="<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='4' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Template List</b></td></tr>\n"
				."<tr><td colspan='4' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>ID</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Type</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Title</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";
			while($row=@mysql_fetch_array($result)){
				$html.="<tr><td class='cms_small' bgcolor='#ffffff'>".$row["nTemplateId"]."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>".$row["cType"]."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>".stripslashes($row["cTitle"])."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>"
						."<a class='cms_button_thin' href='admin.php?action=template_edit&templateid=".$row["nTemplateId"]."'>Edit</a>"
						."&nbsp;<a href='admin.php?action=template_del&templateid=".$row["nTemplateId"]."' class='cms_button_thin'>Remove</a></td>"
					."</tr>\n";
			}
			$html .= "<tr><td colspan='4' bgcolor='#ffffff' class='cms_small' align='right'><a href='admin.php?action=template_add' class='cms_button_thin'>Add New Template</a></td></tr>\n"
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

// Function    : html_template_add()
// Description : Used in the admin page to show a template add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_template_add(){

	if ($_SESSION["cms_admin"]!=""){
	
		$type_select = "<SELECT name='type' class='cms_small'>"
			."<OPTION value='page'>Page</OPTION>"
			."<OPTION value='pagecontent'>Page Content</OPTION>"
			."<OPTION value='content'>Content</OPTION>"
			."</SELECT>\n";

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_templates.png' width='48' height='52' title='CMS Template Add'></td>\n"
			."<td class='cms_huge'>Add Template Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=template_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Template Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Type</td><td bgcolor='#ffffff'>".$type_select."</td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='40' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Template</td><td bgcolor='#ffffff'><textarea cols='100' rows='30' name='template' class='cms_text'></textarea></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Add Template' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_template_edit()
// Description : Used in the admin page to show a template edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_template_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){

		$templateid = $_GET["templateid"];
		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE nTemplateId=".$templateid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$template_row = mysql_fetch_array($result);

			// prepare the template type dropdown
			if ($template_row["cType"]=="content"){
				$selpage = "";
				$selpagecontent="";
				$selcontent = "selected";
			}
			if ($template_row["cType"]=="pagecontent"){
				$selpage = "";
				$selpagecontent="selected";
				$selcontent = "";
			}
			if ($template_row["cType"]=="page"){
				$selpage="selected";
				$selpagecontent="";
				$selcontent="";
			}

			$type_select = "<SELECT name='type' class='cms_small'>"
				."<OPTION value='page' ".$selpage.">Page</OPTION>"
				."<OPTION value='pagecontent' ".$selpagecontent.">Page Content</OPTION>"
				."<OPTION value='content' ".$selcontent.">Content</OPTION>"
				."</SELECT>\n";

			$html .= "<div style='padding:20px;'>\n";

			$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
				."<td><img src='images/icon_templates.png' width='48' height='52' title='CMS Template Edit'></td>\n"
				."<td class='cms_huge'>Edit Template Form</td>\n"
				."</tr></table>\n";

			$html .= "<form method='POST' action='admin_exec.php?action=template_edit'>\n"
				."<input type='hidden' name='templateid' value='".$templateid."'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Template Form</b></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Type</td><td bgcolor='#ffffff'>".$type_select."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Title</td><td bgcolor='#ffffff'><input type='text' name='title' size='40' value='".stripslashes($template_row["cTitle"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Template</td><td bgcolor='#ffffff'><textarea cols='100' rows='30' name='template' class='cms_text'>".stripslashes($template_row["cTemplate"])."</textarea></td></tr>\n"
				."  <tr><td colspan='2' align='right' bgcolor='#ffffff'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
				."</table>\n"
				."</form>\n";

			$html .= "</div>\n";

		} else {
			// problem
		}

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_template_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_template_del(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT nTemplateId,cType,cTitle FROM ".$db_tableprefix."Templates WHERE nTemplateId=".$_GET["templateid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);

			$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=template_del&templateid=".$row["nTemplateId"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm Template Deletion</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the template detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'><b>Type</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cType"]."</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'><b>Title</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cTitle"]."</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
				."</table>\n"
				."</form></div>\n";
		} else {
			// problem getting record from database
			$html = "<div class='cms_small'>Problem with SQL [".$sql."]</div>\n";
		}

		return $html;
	} else {
		header("Location: admin.php");
	}
}


?>