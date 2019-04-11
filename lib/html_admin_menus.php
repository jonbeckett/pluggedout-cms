<?php

/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_menus.php
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

// Function    : html_admin_page
// Description : Returns the html to represent an page in the admin section
//               (it expects the relevant areas to be replaced out at runtime)
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-08-03
function html_admin_page(){

	$html = "<html>\n"
		."<head>\n"
		."<title>CMS Administration Interface</title>\n"
		."<link rel='stylesheet' href='lib/cms_style.css'>\n"
		."<script>\n"
		."var customFunc;\n"
		."customFunc = null;\n"
		."function runCode(){\n"
		."  if (customFunc!=null){\n"
		."    customFunc();\n"
		."  }\n"
		."}\n"
		."</script>\n"
		."</head>\n"
		."<body bgcolor='#ffffff' text='#000000' link='#0000ff' alink='0000ff' vlink='0000ff' style='margin-left:0;margin-right:0;margin-top:0;' onLoad='runCode();'>\n"
		."<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>\n"
		."<td style='border-right:1px solid #aaaabb;' width='200' valign='top'>\n"
		."<div style='padding:5px;'><img src='images/pix5.gif' width='5' height='5'></div><div style='border:1px #aaa;' align='center'><img src='images/cms_logo_small.gif' width='177' height='132'></div>\n"
		."<!--MENU-->\n"
		."<div class='cms_smallprint' style='padding:5px;' align='center'><br>© Jonathan Beckett, 2005<br>All Rights Reserved<br></div>\n"
		."<div align='center'><a href='http://www.pluggedout.com' target='_blank'><img src='images/logo_pluggedout.gif' border='0' title='Powered by PluggedOut (http://www.pluggedout.com)'></a></div>\n"
		."</td>\n"
		."<td valign='top'>";

	if ($_SESSION["cms_userid"]!=""){
	
		if ($_SESSION["cms_userid"]!="" && $_SESSION["cms_userid"]!="1"){
			$html_logout = "<table border='0' cellspacing='0' cellpadding='0'><tr><td class='cms_small'><a class='cms_link' style='text-decoration:none;' href='admin_exec.php?action=user_logout'>Logout</a></td><td>&nbsp;</td><td><a class='cms_link' href='admin_exec.php?action=user_logout'><img src='images/icon_logout_small.png' width='16' height='16' border='0' title='Click here to logout'></a></td><td>&nbsp;</td></tr></table>";
		} else {
			$html_logout = "&nbsp;";
		}
		
		$html .= "<table border='0' cellspacing='0' cellpadding='2' bgcolor='#aaaabb' width='100%'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_normal' background='images/grad_bg.gif'><b>CMS Administration Interface</b></td><td bgcolor='#aaaabb'  background='images/grad_bg.gif' align='right' class='cms_small'>".$html_logout."</td></tr>\n"
			."<tr><td bgcolor='#bbbbcc' class='cms_small'>&nbsp;Logged in as <b>".$_SESSION["cms_username"]."</b></td><td align='right' class='cms_small' bgcolor='#bbbbcc'>Version 0.4.9 ~ © <a href='http://www.pluggedout.com/index.php?pk=dev_cms' class='cms_link' style='text-decoration:none;'>PluggedOut</a>, 2005&nbsp;</td></tr>\n"
			."</table>\n";
	}

	$html .= "<!--BODY-->"
		."</td>\n"
		."</tr></table>\n"
		."</td></tr></table>\n"
		."</body>\n"
		."</html>\n";

	return $html;
}


// Function    : html_admin_loginform
// Description : Returns the html to represent the login form for the admin section
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-08-03
function html_admin_loginform(){

	global $db_tableprefix;

	$html = "<div align='center' style='padding:10px;'><img src='images/cms_logo_big.gif' width='434' height='349' title='CMS Content Management System'></div>\n"
		."<br>\n";
	
	// when showing the login form, check to see if the admin user has a password...
	// - if the admin user does not have a password yet, make sure we make one.
	
	$con = db_connect();
	$sql = "SELECT cPassword FROM ".$db_tableprefix."Users WHERE cUsername='admin' AND cPassword=''";
	$result = mysql_query($sql,$con);
	if ($result!=false){
	
		if (mysql_num_rows($result)>0){

			// the admin account does have a password
			$html .= "<p class='cms_small' align='center'>The administrator password has not been set yet.<br>You must set a password in the form below.</p>\n"
				."<form method='POST' action='admin_exec.php?action=admin_set_password'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' align='center'>\n"
				."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Set Admin Password</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'>Password</td><td bgcolor='#ffffff'><input type='password' name='password1' class='cms_text'></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'>Confirm Password</td><td bgcolor='#ffffff'><input type='password' name='password2' class='cms_text'></td></tr>\n"
				."<tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' class='cms_button' value='Set Password'></td></tr>\n"
				."</table>\n"
				."</form>\n";
	
		} else {

			// the admin account does not have a password
			$html .= "<form method='POST' action='admin_exec.php?action=user_login'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' align='center'>\n"
				."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Administration Login</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'>Username</td><td bgcolor='#ffffff'><input type='text' name='username' class='cms_text'></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'>Password</td><td bgcolor='#ffffff'><input type='password' name='password' class='cms_text'></td></tr>\n"
				."<tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' class='cms_button' value='Login'></td></tr>\n"
				."</table>\n"
				."</form>\n";		

		}
		
	} else {
		header("Location: admin.php?action=problem");
	}
	
	
	$html .= "<br>\n"
		."<div class='cms_smallprint' align='center'>© Jonathan Beckett, 2005<br>All Rights Reserved</div>\n"
		."<br>\n"
		."<div align='center'><img src='images/logo_pluggedout.gif'></div>\n";

		
		
	return $html;

}


// Function    : html_admin_mainmenu
// Description : Returns the html to represent the front page menu of the admin section
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-08-03
function html_admin_mainmenu(){

	$html = "<div style='padding:10px;'>\n";

	$html .= "<div>"
		."<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>"
		."<td align='left' class='cms_huge'>CMS Administration Interface</td>"
		."<td align='right' class='cms_small'><a href='admin.php?action=help' class='cms_link' style='text-decoration:none;'>Help</a></td>"
		."<td align='right' width='48'><a href='admin.php?action=help' class='cms_link'><img src='images/icon_help.png' border='0'></a></td>"
		."</tr></table>"
		."</div>\n";
	

	$html .= "<table border='0' cellspacing='1' cellpadding='2' width='100%' bgcolor='#aaaabb'>\n"
		."<tr>\n";
	
	if ($_SESSION["cms_admin"]!=""){	
		$html .= "<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Users</b></td>\n";
	}
	
	$html .= "<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Content</b></td>\n"
		."<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Pages</b></td>\n";
		
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Collateral</b></td>\n";
	}
	
	$html .= "<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Documents</b></td>\n";
	
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<td bgcolor='#aaaabb' class='cms_small' align='center' width='16%' background='images/grad_bg.gif'><b>Reports</b></td>\n";
	}
	
	$html .= "</tr>\n"
		."<tr>\n";

	// users section
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n"
			."<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=user_list' class='cms_link'><img src='images/icon_users.png' border='0'><br><b>Users</b></a>"
			."</div>\n"
			."<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
			."<a href='admin.php?action=usertype_list' class='cms_link'><img src='images/icon_user_type.png' border='0'><br><b>User Types</b></a>"
			."</div>\n"
			."</td>\n";
	}

	// content section
	$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n"
		."<div style='padding-top:10px;' class='cms_small'>"
		."<a href='admin.php?action=content_list' class='cms_link'><img src='images/icon_content.png' border='0'><br><b>Content</b></a>"
		."</div>\n";
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=contenttype_list' class='cms_link'><img src='images/icon_content_type.png' border='0'><br><b>Content Types</b></a>"
			."</div>\n";
	}
	$html .= "<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
		."<a href='admin.php?action=content_search' class='cms_link'><img src='images/icon_search.png' border='0'><br><b>Content Search</b></a>"
		."</div>\n"
		."</td>\n";

	// page section
	$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n"
		."<div style='padding-top:10px;' class='cms_small'>"
		."<a href='admin.php?action=page_list' class='cms_link'><img src='images/icon_pages.png' border='0'><br><b>Pages</b></a>"
		."</div>\n";
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=pagetype_list' class='cms_link'><img src='images/icon_page_type.png' border='0'><br><b>Page Types</b></a>"
			."</div>\n";
	}
	$html .= "<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
		."<a href='admin.php?action=page_search' class='cms_link'><img src='images/icon_search.png' border='0'><br><b>Page Search</b></a>"
		."</div>\n"
		."</td>\n";

	// collateral section
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n"
			."<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=template_list' class='cms_link'><img src='images/icon_templates.png' border='0'><br><b>Templates</b></a>"
			."</div>\n"
			."<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
			."<a href='admin.php?action=filebrowse' class='cms_link'><img src='images/icon_files.png' border='0'><br><b>Files</b></a>"
			."</div>\n"
			."</td>\n";
	}

	// documents section
	$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n";
	$html .= "<div style='padding-top:10px;' class='cms_small'>"
		."<a href='admin.php?action=document_add' class='cms_link'><img src='images/icon_documents.png' border='0'><br><b>Add Document</b></a>"
		."</div>\n";
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=repository_list' class='cms_link'><img src='images/icon_repositories.png' border='0'><br><b>Repositories</b></a>"
			."</div>\n";

		$html .= "<div style='padding-top:10px;' class='cms_small'>"
			."<a href='admin.php?action=doctype_list' class='cms_link'><img src='images/icon_document_type.png' border='0'><br><b>Document Types</b></a>"
			."</div>\n";
	}

	$html .= "<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
		."<a href='admin.php?action=document_search' class='cms_link'><img src='images/icon_search.png' border='0'><br><b>Document Search</b></a>"
		."</div>\n"
		."</td>\n";


	if ($_SESSION["cms_admin"]!=""){
		$html .= "<td bgcolor='#ffffff' valign='top' align='center' width='16%'>\n"
			."<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
			."<a href='admin.php?action=report_statistics' class='cms_link'><img src='images/icon_reports.png' border='0'><br><b>Statistics</b></a>"
			."</div>\n"
			."<div style='padding-top:10px;padding-bottom:10px;' class='cms_small'>"
			."<a href='admin.php?action=report_audit_trail' class='cms_link'><img src='images/icon_reports.png' border='0'><br><b>Audit Trail</b></a>"
			."</div>\n"
			."</td>\n";
	}

	$html .= "</tr>\n"
		."</table>\n";
			
	$html .= "</div>\n";

	return $html;

}



// Function    : html_admin_menu()
// Description : Used in the admin page to show a menu across the top of pages
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_admin_menu(){

	$html = "<table border='0' cellspacing='0' cellpadding='10' width='100%'><tr><td>\n"
		."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' width='100%'>\n"
		."<tr><td bgcolor='#ffffff' class='cms_small'>Logged in as<br><span class='cms_large'>&nbsp;&nbsp;".$_SESSION["cms_username"]."</span></td></tr>\n"
		."<tr><td bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>User Options</b></td></tr>\n"
		."<tr><td bgcolor='#ffffff'>\n"
		."<table border='0' cellspacing='0' cellpadding='5'><tr><td>\n";


	// ."<td class='cms_small' align='right'>Logged in as <b>".$_SESSION["username"]."&nbsp;</td>\n";
	$html .= "<table border='0' cellspacing='0' cellpadding='1'>\n";

	$html .= "<tr><td colspan='4' class='cms_normal'><b>Admin Home</b></tr>\n"
		."<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php'><img src='images/icon_home_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php'>Admin Home</a></td>\n"
		."</tr>\n"
		."<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='index.php' target='_blank'><img src='images/icon_website_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' target='_blank' href='index.php'>View Website</a></td>\n"
		."</tr>\n";

	$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";

	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr><td colspan='4' class='cms_normal'><b>Users & Security</b></tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=user_list'><img src='images/icon_users_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=user_list'>Users</a></td>\n"
			."</tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=usertype_list'><img src='images/icon_user_type_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=usertype_list'>UserTypes</a></td>\n"
			."</tr>\n"
			."<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";
	}

	$html .= "<tr><td colspan='4' class='cms_normal'><b>Pages</b></tr>\n";

	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=page_list'><img src='images/icon_pages_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=page_list'>Pages</a></td>\n"
		."</tr>\n";
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=pagetype_list'><img src='images/icon_page_type_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=pagetype_list'>PageTypes</a></td>\n"
			."</tr>\n";
	}

	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=page_search'><img src='images/icon_search_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=page_search'>Search</a></td>\n"
		."</tr>\n";

	$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";

	$html .= "<tr><td colspan='4' class='cms_normal'><b>Content</b></tr>\n";

	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=content_list'><img src='images/icon_content_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=content_list'>Content</a></td>\n"
		."</tr>\n";
	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=contenttype_list'><img src='images/icon_content_type_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=contenttype_list'>ContentTypes</a></td>\n"
			."</tr>\n";
	}
	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=content_search'><img src='images/icon_search_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=content_search'>Search</a></td>\n"
		."</tr>\n";

	$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";

	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr><td colspan='4' class='cms_normal'><b>Collateral</b></tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=template_list'><img src='images/icon_templates_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=template_list'>Templates</a></td>\n"
			."</tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=upload_list'><img src='images/icon_files_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=filebrowse'>Browse Files</a></td>\n"
			."</tr>\n";
		$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";
	}

	$html .= "<tr><td colspan='4' class='cms_normal'><b>Document Management</b></tr>\n";


	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=repository_list'><img src='images/icon_repositories_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=repository_list'>Document Repositories</a></td>\n"
			."</tr>\n";

		$html .= "<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=doctype_list'><img src='images/icon_document_type_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=doctype_list'>Document Types</a></td>\n"
			."</tr>\n";
	}			
	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=document_add'><img src='images/icon_pages_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=document_add'>Add Document</a></td>\n"
		."</tr>\n";
	$html .= "<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=document_search'><img src='images/icon_search_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=document_search'>Search</a></td>\n"
		."</tr>\n";

	$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";

	if ($_SESSION["cms_admin"]!=""){
		$html .= "<tr><td colspan='4' class='cms_normal'><b>Reports</b></tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=report_statistics'><img src='images/icon_reports_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=report_statistics'>Statistics (Hits)</a></td>\n"
			."</tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=report_audit_trail'><img src='images/icon_reports_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=report_audit_trail'>Audit Trail</a></td>\n"
			."</tr>\n"
			."<tr>\n"
			."<td rowspan='1' width='10'>&nbsp;</td>\n"
			."<td width='16'><a class='cms_link' href='admin.php?action=report_page_updates'><img src='images/icon_reports_small.png' width='16' height='16' border='0'></a>\n"
			."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
			."<td class='cms_small'><a class='cms_link' href='admin.php?action=report_page_updates'>Page Updates</a></td>\n"
			."</tr>\n";
		$html .= "<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n";
	}

	$html .= "<tr><td colspan='4' class='cms_normal'>Help</tr>\n"
		."<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin.php?action=help'><img src='images/icon_help_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin.php?action=help'>Online Help</a></td>\n"
		."</tr>\n"
		."<tr><td><img src='iamges/pix1.gif' width='1' height='2'></td></tr>\n"
		."<tr><td colspan='4' class='cms_normal'>&nbsp;</tr>\n"
		."<tr>\n"
		."<td rowspan='1' width='10'>&nbsp;</td>\n"
		."<td width='16'><a class='cms_link' href='admin_exec.php?action=user_logout'><img src='images/icon_logout_small.png' width='16' height='16' border='0'></a>\n"
		."<td width='5'><img src='images/pix5.gif' width='5' height='5'></td>\n"
		."<td class='cms_small'><a class='cms_link' href='admin_exec.php?action=user_logout'>Logout</a></td>\n"
		."</tr>\n"
		."</table>\n";


	$html .= "</td></tr></table>\n"
		."</td></tr></table>\n"
		."</td></tr></table>\n";

	return $html;
}


function html_under_construction(){

	$html .= "<div style='padding:20px;'>\n"
		."<div class='cms_huge' align='center'><img src='images/icon_underconstruction.png'><br>Under Construction</div>\n"
		."<div class='cms_normal' align='center'><b>The page you have requested is not yet available.</b><br><br>Please remember that CMS is under continual heavy development.<br><br>If you have any questions, please contact <a class='cms_link' href='mailto:enquiries@pluggedout.com'>enquiries@pluggedout.com</a>.</div>\n"
		."</div>\n";

	return $html;

}



?>