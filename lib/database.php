<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : database.php
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

// function      db_connect(void)
// notes         connects to the database
// returns       database connection handle
// author        Jonathan Beckett
// last changed  2002-08-15
function db_connect()
{

	global $db_server;
	global $db_username;
	global $db_password;
	global $db_name;

	$con = mysql_connect($db_server,$db_username,$db_password);
	if (!(mysql_select_db($db_name,$con)))
	{
		show_error();
	}

	return $con;
}



?>