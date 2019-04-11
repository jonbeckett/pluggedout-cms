<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : session.php
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
#= along with BLOG files; if not, write to the Free Software
#= Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#===========================================================================
*/

// Get the session ID from the URL
if ($_GET["sid"]!=""){
	session_id($_GET["sid"]);
}
if ($_GET["PHPSESSID"]!=""){
	session_id($_GET["PHPSESSID"]);
}

// start a session if one is not already started
session_start();

if ($_SESSION["cms_userid"]==""){
	$_SESSION["cms_username"]="guest";
	$_SESSION["cms_userid"]=1;
}

// Function    : form_url
// Description : Makes a URL with the session ID in it.
// Arguments   : $url - a universal resource locator
// Returns     : a url
// Author      : Jonathan Beckett
// Last Change : 2003-11-11
function form_url($url){
	// put the session id in the URL if it is not already there
	if (ereg("sid",$url) || ereg("PHPSESSID",$url)){
		// sid is already there
		$result = $url;
	} else {
		// put the sid in
		if (ereg("\?",$url)){
			$connector = "&";
		} else {
			$connector = "?";
		}
		$result = $url.$connector."sid=".session_id();
	}
	return $result;	
}

?>