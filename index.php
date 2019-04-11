<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : index.php
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


// Include library files for handling generic CMS functionality
// such as session handling, configuration constants, database
// functions, security checking functions and of course page generation

include "./lib/session.php";
include "./lib/config.php";
include "./lib/database.php";
include "./lib/security.php";
include "./lib/html_public.php";


// Look for the 'includes' directory and loop through it's
// contents - including anything we find in there
// (the purpose being to include functions that are later
// used in either function based content, or through
// tag replacement.

if ($handle = opendir('./includes')) {
	while (false !== ($file = readdir($handle))) {
		if ($file!="." && $file!=".."){
			include "includes/".$file;
		}
	}
}

// output the content
if ($_GET["pk"]!=""){
	$pk = $_GET["pk"];
} else {
	$pk = "home";
}

if ($_GET["pageid"]!=""){
	$pk = $_GET["pageid"];
}

print html_page($pk);

?>
