<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : config.php
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


// Initialise Constants
// ====================
// The config file is used to set values used throughout the rest of the CMS
// code for common things like database connections, and to influence behaviour
// of particular functionality



// Database Constants
// ------------------
// The database constants should be set according to the MySQL database you
// are connecting to - commonly the server will be 'localhost' if it resides
// on the same machine and you have not changed it. The user will likewise
// commonly be 'root'. The prefix refers to the first few letters of all the
// CMS table names - you can change the table names in MySQL if you want as
// long as you change the prefix in here to suit.

 $db_server = "localhost";
 $db_name = "cms";
 $db_username = "root";
 $db_password = "";
 $db_tableprefix = "cms_";


// Results Per Page
// ----------------
// All list views throughout the administration interface use the
// results_per_page to influence how many pages/content etc to show
// in each screen-full.
 $results_per_page = 20;
 

// Site URL
// --------
// The Site URL should be filled with the full URL of your site, WITHOUT a
// slash on the end.
 $site_url = "http://yourwebserver/cms";


// Site Root
// ---------
// The site root should be filled with the full path on your web server to
// the root of the CMS installation (i.e. the directory containing index.php)
// - DO NOT put a slash on the end.
 $site_root = "/full/path/from/root/to/cms";


// Pickup Path
// -----------
// The pickup path should be filled with the full path of your pickup folder
// - you only need to fill this if you are going to be using the document
// management API functions
 $pickup_path = "/full/path/from/root/to/cms/pickup";


// Crypt Salt
// ----------
// All passwords in the CMS user table are encrypted using the salt
// stored here. Remember that if you change the salt after creating user
// accounts, none of your passwords will work any more. In simple terms,
// CMS stores the hash of a password using the salt given here - and checks
// the hash of a submitted password against the stored hash.
 $crypt_salt = "put_some_random_words_here";
 
 
?>