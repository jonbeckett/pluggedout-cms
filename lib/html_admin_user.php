<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_user.php
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

// Function    : html_usertype_list()
// Description : Used in the admin page to show a list of usertypes
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_usertype_list(){

	global $db_tableprefix;
	global $results_per_page;

	if ($_SESSION["cms_admin"]!=""){

		$con = db_connect();

		// Build page list to limit search result
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."UserType";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
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
				$html_link = "<a href='admin.php?action=usertype_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}
		if ($_GET["list_from"]!=""){
			$list_from = $_GET["list_from"];
		} else {
			$list_from = "0";
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_usertypes.png' width='48' height='52' title='CMS User Types'></td>\n"
			."<td class='cms_huge'>User Type List</td>\n"
			."</tr></table>\n";

		$sql = "SELECT * FROM ".$db_tableprefix."UserType ORDER BY cUserTypeName LIMIT ".$list_from.",".$results_per_page;
		$result = mysql_query($sql,$con);

		if ($result!=false){

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='3' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>User Type List</b></td></tr>\n"
				."<tr><td colspan='3' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>User Type</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";

			while($row=@mysql_fetch_array($result)){
				$html.="<tr>"
					."<td class='cms_small' bgcolor='#ffffff'>".stripslashes($row["cUserTypeName"])."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>";

				// protect the admin and guest user accounts
				if ($row["cUserTypeName"]!="Administrator" && $row["cUserTypeName"]!="Guest"){
					$html .= "<a href='admin.php?action=usertype_edit&usertypeid=".$row["nUserTypeId"]."' class='cms_button_thin'>Edit</a>"
						."&nbsp;<a href='admin.php?action=usertype_del&usertypeid=".$row["nContentTypeId"]."' class='cms_button_thin'>Remove</a>";
				} else {
					$html .= "&nbsp;";
				}
				$html .= "</td></tr>\n";
			}

			$html .= "<tr><td colspan='3' bgcolor='#ffffff' align='right'><a href='admin.php?action=usertype_add' class='cms_button_thin'>Add New UserType</a></td></tr>\n"
				."</table>\n";

		} else {
			$html="<li class='cms_small'>Problem with SQL<br>[".$sql."]</li>\n";
		}
		$html .= "</div>\n";

		return $html;

	} else {
		// they are not an admin - send them back
		header("Location: admin.php");
	}
}


// Function    : html_usertype_add()
// Description : Used in the admin page to show a usertype add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_usertype_add(){

	if ($_SESSION["cms_admin"]!=""){
	
		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_usertypes.png' width='48' height='52' title='CMS User Type Add'></td>\n"
			."<td class='cms_huge'>Add User Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=usertype_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add User Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='50' class='cms_text'></td></tr>\n"
			."  <tr><td colspan='2' bgcolor='#ffffff' class='cms_small' align='right'><input type='submit' class='cms_button' value='Add UserType'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;
	} else {
		header("Location: admin.php");
	}
}


// Function    : html_usertype_edit()
// Description : Used in the admin page to show a usertype edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_usertype_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){

		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."UserType WHERE nUserTypeId=".$_GET["usertypeid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
		}

		// make the admin and guest accounts mandatory
		if ($row["cUserTypeName"]=="Guest" || $row["cUserTypeName"]=="Administrator"){
			$html_usertype = stripslashes($row["cUserTypeName"])."<input type='hidden' name='name' value='".stripslashes($row["cUserTypeName"])."'>";
		} else {
			$html_usertype = "<input type='text' name='name' size='50' class='cms_text' value='".stripslashes($row["cUserTypeName"])."'>";
		}
			
		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_usertypes.png' width='48' height='52' title='CMS User Type Edit'></td>\n"
			."<td class='cms_huge'>Edit User Type</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=usertype_edit'>\n"
			."<input name='usertypeid' type='hidden' value='".$row["nUserTypeId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit User Type Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Name</td><td bgcolor='#ffffff' class='cms_small'>".$html_usertype."</td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Make Changes'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_user_list()
// Description : Used in the admin page to show a list of users
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_user_list(){

	global $db_tableprefix;
	global $results_per_page;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();

		// Build page list to limit search result
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."Users";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
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
				$html_link = "<a href='admin.php?action=user_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}
		if ($_GET["list_from"]!=""){
			$list_from = $_GET["list_from"];
		} else {
			$list_from = "0";
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_users.png' width='48' height='52' title='CMS User List'></td>\n"
			."<td class='cms_huge'>User List</td>\n"
			."</tr></table>\n";


		$sql = "SELECT * FROM ".$db_tableprefix."Users ORDER BY cUsername LIMIT ".$list_from.",".$results_per_page;
		$result = mysql_query($sql,$con);

		if ($result!=false){

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='4' class='cms_small' bgcolor='#aaaabb' background='images/grad_bg.gif'><b>User List</b></td></tr>\n"
				."<tr><td colspan='4' class='cms_small' bgcolor='#ffffff'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>Username</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Admin</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Last Login</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Control</td>"
				."</tr>\n";

			while($row=@mysql_fetch_array($result)){

				$html.="<tr>"
					."<td class='cms_small' bgcolor='#ffffff'>".stripslashes($row["cUsername"])."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>".$row["cAdmin"]."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>".$row["dLastLogon"]."</td>"
					."<td class='cms_small' bgcolor='#ffffff'>";
				
				$html .= "<a href='admin.php?action=user_edit&userid=".$row["nUserId"]."' class='cms_button_thin'>Edit</a>";
					
				if ($row["cUsername"]!="admin" && $row["cUsername"]!="guest"){
					$html .= "&nbsp;<a href='admin.php?action=user_del&userid=".$row["nUserId"]."' class='cms_button_thin'>Remove</a></td>";
				} else {
					$html .= "&nbsp;";
				}
				$html .= "</tr>\n";
			}

			$html.= "<tr><td colspan='4' bgcolor='#ffffff' align='right'><a href='admin.php?action=user_add' class='cms_button_thin'>Add New User</a></td></tr>\n"
				."</table>\n";
		}
		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_user_add()
// Description : Used in the admin page to show a user add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_user_add(){

	if ($_SESSION["cms_admin"]!=""){
	
		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_users.png' width='48' height='52' title='CMS User Add'></td>\n"
			."<td class='cms_huge'>Add User Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=user_add'>\n"
			."<table border='0' cellpadding='3' cellspacing='1' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' class='cms_small' background='images/grad_bg.gif'><b>Add User Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Username</td><td bgcolor='#ffffff'><input type='text' name='cms_username' size='40' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Password</td><td bgcolor='#ffffff'><input type='text' name='cms_password' size='40' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>E-Mail Address</td><td bgcolor='#ffffff'><input type='text' name='email' size='60' value='' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Admin</td><td bgcolor='#ffffff'><input type='checkbox' name='admin' value='x' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Add User' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;
	} else {
		header("Location: admin.php");
	}
}


// Function    : html_user_edit()
// Description : Used in the admin page to show a user edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_user_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_users.png' width='48' height='52' title='CMS User Edit'></td>\n"
			."<td class='cms_huge'>Edit User Form</td>\n"
			."</tr></table>\n";

		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."Users WHERE nUserId=".$_GET["userid"].";";
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			$row = mysql_fetch_array($result);

			// show the basic user edit form
			if ($row["cAdmin"]!=""){
				$admin_checked = " checked ";
			} else {
				$admin_checked = "";
			}

			// make the admin and guest accounts mandatory
			if ($row["cUsername"]=="guest" || $row["cUsername"]=="admin"){
				$html_username = stripslashes($row["cUsername"])."<input type='hidden' name='cms_username' value='".stripslashes($row["cUsername"])."'>";
			} else {
				$html_username = "<input type='text' name='cms_username' size='40' value='".stripslashes($row["cUsername"])."' class='cms_text'>";
			}

			$html .= "<form method='POST' action='admin_exec.php?action=user_edit'>\n"
				."<input type='hidden' name='userid' value='".$_GET["userid"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit User Form</b></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Username</td><td bgcolor='#ffffff' class='cms_small'>".$html_username."</td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Password</td><td bgcolor='#ffffff'><input type='password' name='cms_password' size='40' value='' class='cms_text'><span class='cms_small'>&nbsp;* enter a password to change it</span></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>E-Mail Address</td><td bgcolor='#ffffff'><input type='text' name='email' size='60' value='".stripslashes($row["cEMailAddress"])."' class='cms_text'></td></tr>\n"
				."  <tr><td bgcolor='#dddddd' class='cms_small'>Admin</td><td bgcolor='#ffffff'><input type='checkbox' name='admin' value='x' class='cms_text' value='x' ".$admin_checked."></td></tr>\n"
				."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
				."</table>\n"
				."</form>\n";

			// show the usergroup memberships

			// first, prepare the UserTypeSelect
			$UserTypeSelect = "<select name='usertypeid'>";
			$sql = "SELECT * FROM ".$db_tableprefix."UserType ORDER BY cUserTypeName";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				while ($row=@mysql_fetch_array($result)){
					$UserTypeSelect .= "<option value='".$row["nUserTypeId"]."'>".stripslashes($row["cUserTypeName"])."</option>\n";
				}
			}
			$UserTypeSelect .= "</select>";

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>User Type Memberships</b></td></tr>\n"
				."<tr>\n"
				."  <td bgcolor='#dddddd' align='left' class='cms_small'>UserType</td>\n"
				."  <td bgcolor='#dddddd' align='center' class='cms_small'>Controls</td>\n"
				."</tr>\n";
			$sql = "SELECT * FROM ".$db_tableprefix."UserTypeMember"
				." INNER JOIN ".$db_tableprefix."UserType ON ".$db_tableprefix."UserTypeMember.nUserTypeId=".$db_tableprefix."UserType.nUserTypeId"
				." WHERE ".$db_tableprefix."UserTypeMember.nUserId=".$_GET["userid"].";";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<tr>\n"
						."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cUserTypeName"])."</td>\n"
						."<td bgcolor='#ffffff' class='cms_small'>";
					
					$html .= "<a href='admin_exec.php?action=usertypemember_remove&usertypememberid=".$row["nUserTypeMemberId"]."&userid=".$_GET["userid"]."' class='cms_button_thin'>remove</a>";
					
					$html .= "</td>\n"
						."</tr>\n";
				}
				$html .= "<form method='POST' action='admin_exec.php?action=user_joinusertype'>\n"
					."<input type='hidden' name='userid' value='".$_GET["userid"]."'>"
					."<tr>\n"
					."<td bgcolor='#dddddd' class='cms_small'>".$UserTypeSelect."</td>"
					."<td bgcolor='#dddddd' class='cms_small'><input type='submit' value='Join' class='cms_button'></td>"
					."</tr>\n"
					."</form>\n";
			} else {
			}

		} else {
			$html.="<li class='cms_small'>Could not find record [".$sql."]</li>\n";
		}
		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_usertype_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_usertype_del(){

	global $db_tableprefix;

	if ($_SESSION["admin"]!=""){

		$con = db_connect();
		$sql = "SELECT nUserTypeId,cUserTypeName FROM ".$db_tableprefix."UserType WHERE nUserTypeId=".$_GET["usertypeid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){

			$row = mysql_fetch_array($result);

			$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=usertype_del&usertypeid=".$row["nUserTypeId"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm User Type Deletion</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the User Type detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'><b>UserType</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cUserTypeName"]."</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
				."</table>\n"
				."</form></div>\n";

		} else {
			// problem getting the record from the database
			$html = "<div class='cms_small'>Problem with the SQL [".$sql."]</div>\n";
		}

		return $html;

	} else {
		header("Location: admin.php");
	}
}

// Function    : html_user_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_user_del(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT nUserId,cUsername FROM ".$db_tableprefix."Users WHERE nUserId=".$_GET["userid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){

			$row = mysql_fetch_array($result);

			$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=user_del&userid=".$row["nUserId"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm User Deletion</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the User detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'><b>Username</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cUsername"]."</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' align='right' colspan='2'><input type='submit' class='cms_button' value='Remove'></td></tr>\n"
				."</table>\n"
				."</form></div>\n";

		} else {
			// problem getting the record from the database
			$html = "<div class='cms_small'>Problem with the SQL [".$sql."]</div>\n";
		}

		return $html;
		
	} else {
		header("Location: admin.php");
	}
}

?>