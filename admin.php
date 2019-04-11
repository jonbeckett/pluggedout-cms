<?php

/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : admin.php
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


// Include Library Files

include "./lib/session.php";
include "./lib/config.php";
include "./lib/database.php";
include "./lib/security.php";
include "./lib/html_admin.php";
include "./lib/html_public.php";
include "./lib/misc.php";

// put this page request in the audit trail
audit_trail();

// get the admin page
$html = html_admin_page();	

if ($_SESSION["cms_userid"]!="1") {

	$html = str_replace("<!--MENU-->",html_admin_menu(),$html);

	if ($_GET["action"]==""){

		//$html = str_replace("<!--HELP-->",html_help("mainmenu"),$html);
		$html = str_replace("<!--BODY-->",html_admin_mainmenu(),$html);
	
	} else {
	
		switch ($_GET["action"]) {

			case "page_list":
				//$html = str_replace("<!--HELP-->",html_help("pages"),$html);
				$html = str_replace("<!--BODY-->",html_page_list(),$html);
				break;
			
			case "page_add":
				$html = str_replace("<!--BODY-->",html_page_add(),$html);
				break;
				
			case "page_edit":
				$html = str_replace("<!--BODY-->",html_page_edit(),$html);
				break;
				
			case "pagecontent_edit":
				$html = str_replace("<!--BODY-->",html_pagecontent_edit(),$html);
				break;
				
			case "template_list" :
				//$html = str_replace("<!--HELP-->",html_help("templates"),$html);
				$html = str_replace("<!--BODY-->",html_template_list(),$html);
				break;

			case "template_add":
				$html = str_replace("<!--BODY-->",html_template_add(),$html);
				break;
				
			case "template_edit":
				$html = str_replace("<!--BODY-->",html_template_edit(),$html);
				break;
			
			case "content_list":
				//$html = str_replace("<!--HELP-->",html_help("content"),$html);
				$html = str_replace("<!--BODY-->",html_content_list(),$html);
				break;
				
			case "content_add":
				$html = str_replace("<!--BODY-->",html_content_add(),$html);
				break;

			case "content_edit":
				$html = str_replace("<!--BODY-->",html_content_edit(),$html);
				break;

			case "content_preview":
				$html = str_replace("<!--BODY-->",html_content_preview(),$html);
				break;

			case "user_list":
				//$html = str_replace("<!--HELP-->",html_help("users"),$html);
				$html = str_replace("<!--BODY-->",html_user_list(),$html);
				break;

			case "user_add":
				$html = str_replace("<!--BODY-->",html_user_add(),$html);
				break;

			case "user_edit":
				$html = str_replace("<!--BODY-->",html_user_edit(),$html);
				break;

			case "contenttype_list":
				//$html = str_replace("<!--HELP-->",html_help("contenttypes"),$html);
				$html = str_replace("<!--BODY-->",html_contenttype_list(),$html);
				break;
			
			case "contenttype_add":
				$html = str_replace("<!--BODY-->",html_contenttype_add(),$html);
				break;
				
			case "contenttype_edit":
				$html = str_replace("<!--BODY-->",html_contenttype_edit(),$html);
				break;

			case "usertype_list":
				//$html = str_replace("<!--HELP-->",html_help("usertypes"),$html);
				$html = str_replace("<!--BODY-->",html_usertype_list(),$html);
				break;
				
			case "usertype_add":
				$html = str_replace("<!--BODY-->",html_usertype_add(),$html);
				break;

			case "usertype_edit":
				$html = str_replace("<!--BODY-->",html_usertype_edit(),$html);
				break;
				
			case "content_del":
				$html = str_replace("<!--BODY-->",html_content_del(),$html);
				break;
				
			case "template_del":
				$html = str_replace("<!--BODY-->",html_template_del(),$html);
				break;
			
			case "contenttype_del":
				$html = str_replace("<!--BODY-->",html_contenttype_del(),$html);
				break;
				
			case "usertype_del":
				$html = str_replace("<!--BODY-->",html_usertype_del(),$html);
				break;
				
			case "pagecontent_del":
				$html = str_replace("<!--BODY-->",html_pagecontent_del(),$html);
				break;
				
			case "user_del":
				$html = str_replace("<!--BODY-->",html_user_del(),$html);
				break;
			
			case "upload_delete":
				$html = str_replace("<!--BODY-->",html_upload_delete(),$html);
				break;
			
			case "page_del":
				$html = str_replace("<!--BODY-->",html_page_del(),$html);
				break;
				
			case "doctype_list":
				$html = str_replace("<!--BODY-->",html_doctype_list(),$html);
				break;
				
			case "doctype_add":
				$html = str_replace("<!--BODY-->",html_doctype_add(),$html);
				break;
			
			case "doctype_edit":
				$html = str_replace("<!--BODY-->",html_doctype_edit(),$html);
				break;
			
			case "doctype_del":
				$html = str_replace("<!--BODY-->",html_doctype_del(),$html);
				break;
			
			case "under_construction":
				$html = str_replace("<!--BODY-->",html_under_construction(),$html);
				break;
		
			case "filebrowse":
				$html = str_replace("<!--BODY-->",html_filebrowse(),$html);
				break;
				
			case "pagetype_list":
				$html = str_replace("<!--BODY-->",html_pagetype_list(),$html);
				break;
			
			case "pagetype_add":
				$html = str_replace("<!--BODY-->",html_pagetype_add(),$html);
				break;
			
			case "pagetype_edit":
				$html = str_replace("<!--BODY-->",html_pagetype_edit(),$html);
				break;
				
			case "pagetype_del":
				$html = str_replace("<!--BODY-->",html_pagetype_del(),$html);
				break;
			
			case "document_add":
				$html = str_replace("<!--BODY-->",html_document_add(),$html);
				break;
			
			case "repository_list":
				$html = str_replace("<!--BODY-->",html_repository_list(),$html);
				break;
			
			case "repository_add":
				$html = str_replace("<!--BODY-->",html_repository_add(),$html);
				break;
				
			case "repository_edit":
				$html = str_replace("<!--BODY-->",html_repository_edit(),$html);
				break;
				
			case "repository_del":
				$html = str_replace("<!--BODY-->",html_repository_del(),$html);
				break;
				
			case "document_search":
				$html = str_replace("<!--BODY-->",html_document_search(),$html);
				break;
				
			case "page_search":
				$html = str_replace("<!--BODY-->",html_page_search(),$html);
				break;
				
			case "content_search":
				$html = str_replace("<!--BODY-->",html_content_search(),$html);
				break;
				
			case "problem":
				$html = str_replace("<!--BODY-->",html_problem(),$html);
				break;
				
			case "report_statistics":
				$html = str_replace("<!--BODY-->",html_report_statistics(),$html);
				break;

			case "report_audit_trail":
				$html = str_replace("<!--BODY-->",html_report_audit_trail(),$html);
				break;

			case "report_page_updates":
				$html = str_replace("<!--BODY-->",html_report_page_updates(),$html);
				break;
				
			case "help":
				$html = str_replace("<!--BODY-->",html_help(),$html);
				break;
				
		}
	}
	

} else {

	//$html = str_replace("<!--HELP-->",html_help("login"),$html);
	$html = str_replace("<!--BODY-->",html_admin_loginform(),$html);

}

print $html;

?>
