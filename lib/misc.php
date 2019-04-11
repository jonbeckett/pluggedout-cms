<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : misc.php
#= Version: 0.4.9 (2005-05-10)
#= Author : Jonathan Beckett
#= Email  : jonbeckett@pluggedout.com
#= Website: http://www.pluggedout.com/index.php?pk=dev_cms
#= Support: http://www.pluggedout.com/development/forums/viewforum.php?f=14
#===========================================================================
#= Copyright (c) 2004 Jonathan Beckett
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

// function      is_email(email)
// notes         checks the format of an email
// returns       true or false
// author        Jonathan Beckett
// last changed  2002-08-15
function is_email($email) {
	$validEmailExpr = "^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$";
	if (!eregi($validEmailExpr,$email) ) {
		return true;
	} else {
		return false;
	}
}

// function      is_alphanumeric(string)
// notes         checks the format of a string to make sure its only got alphanumeric chars in it
// returns       true or false
// author        Jonathan Beckett
// last changed  2002-08-15
function is_alphanumeric($input) {
	if ( preg_match("/[^a-zA-Z0-9]/",$userid) ) {
		return true;
	} else {
		return false;
	}
}

// function      get_date_part(string,string)
// notes         Returns part of a SQL date - accepts "day" "month" "year" as second argument
// returns       string
// author        Jonathan Beckett
// last changed  2002-08-15
function get_date_part($sqldate,$part) {
	// expects date in format yyyy-mm-dd hh:nn:ss
	switch ($part)
	{
		case "day":
			$result = substr($sqldate,8,2);
			break;
		case "month":
			$result = substr($sqldate,5,2);
			break;
		case "year":
			$result = substr($sqldate,0,4);
			break;
	}
	return $result;
}


function audit_trail(){
	
	global $db_tableprefix;
	
	if ($_GET["action"]!="report_audit_trail" && $_GET["action"]!=""){
	
		$con = db_connect();
		foreach($_GET AS $key=>$val){
			if ($key=="cms_password" || $key=="password"){
				$val = "CENSORED";
			}
			if (strlen($val)>256){
				$audit_data .= " g [".$key."]=[Too much data to record]\n";
			} else {
				$audit_data .= " g [".$key."]=[".$val."]\n";
			}
		}
		foreach($_POST AS $key=>$val){
			if ($key=="cms_password" || $key=="password"){
				$val = "CENSORED";
			}
			if (strlen($val)>256){
				$audit_data .= " g [".$key."]=[Too much data to record]\n";
			} else {
				$audit_data .= " g [".$key."]=[".$val."]\n";
			}
			$audit_data .= " p [".$key."]=[".$val."]\n";
		}

		$sql = "INSERT INTO ".$db_tableprefix."AuditTrail (nUserId,dAdded,cIPAddress,cSessionId,cPage,cData)"
			." VALUES ("
			.$_SESSION["cms_userid"]
			.",now()"
			.",'".mysql_escape_string($_SERVER["REMOTE_ADDR"])."'"
			.",'".mysql_escape_string($_REQUEST["PHPSESSID"])."'"
			.",'".mysql_escape_string($_SERVER["PHP_SELF"])."'"
			.",'".mysql_escape_string($audit_data)."')";
		$result = mysql_query($sql,$con);
		if ($result==false){
			print "problem with [".$sql."]";
		}
		
	}
}
?>