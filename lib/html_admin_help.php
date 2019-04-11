<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_help.php
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

// Function    : html_help()
// Description : Returns help text to put on the screen for various admin activities
// Arguments   : subject - a key to return specific help text
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_help(){


	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_help.png' width='48' height='48' title='CMS Online Help'></td>\n"
		."<td class='cms_huge'>CMS Online Help</td>\n"
		."</tr></table>\n";
	
	$subject = stripslashes($_GET["subject"]);
	
	if ($subject==""){
	
		// no subject given - show the main menu
		$html .= "<div style='padding:20px;'>\n"
			."<div class='cms_large'>Choose a Subject...</div>\n"
			."<ul>\n"
			."<div class='cms_normal'><b>Users</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=users'>Users</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=user_types'>User Types</a></li>\n"
			."</ul>\n"
			."<div class='cms_normal'><b>Pages</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=pages'>Pages</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=page_types'>Page Types</a></li>\n"
			."</ul>\n"
			."<div class='cms_normal'><b>Content</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=content'>Content</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=content_types'>Content Types</a></li>\n"
			."</ul>\n"
			."<div class='cms_normal'><b>Collateral</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=templates'>Templates</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=browse_files'>Browse Files</a></li>\n"
			."</ul>\n"
			."<div class='cms_normal'><b>Documents</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=document_repositories'>Document Repositories</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=document_types'>Document Types</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=add_document'>Add Document</a></li>\n"
			."</ul>\n"
			."<div class='cms_normal'><b>Reporting</b></div>\n"
			."<ul>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=report_statistics'>Statistics Reporting</a></li>\n"
			."<li class='cms_normal'><a href='admin.php?action=help&subject=report_audit_trail'>Audit Trail Reporting</a></li>\n"			
			."</ul>\n"
			."</ul>\n"
			."</div>\n";		
	
	} else {

		// show a subject specific chunk of text
		
		$html .= "<div style='padding:20px;'>\n";
		
		switch($subject){
		
			case "login":

				$html .= "<div class='cms_large'>Logging in to CMS</div>\n"
					."<div class='cms_normal'>Enter your designated username and password in the form to the right and click submit.<br><br>Your login will provide you with functionality within the CMS system appropriate to the tasks you need to perform. If your required functionality is not provided, please contact a system administrator.</div>";
					
				break;
				
			case "mainmenu":
				$html .= "<span class='cms_normal'><b>Admin Home / Main Menu</b><br><br>The menubar above, and icons to the right represent the 'home page' of the CMS system. Notice that you will always be able to see the menubar from wherever you are within the CMS administration pages.<br><br>Your login will provide you with functionality within the CMS system appropriate to the tasks you need to perform. If your required functionality is not provided, please contact a system administrator.</span>";
				break;
			case "users":
				$html .= "<span class='cms_normal'><b>User Accounts</b><br><br>The user list shows the various user accounts in the CMS system, and gives options to add, edit and remove users.<br><br>It is worth noting that a user receives access permissions through the 'User Types' (which can be manipulated for a particular user via the 'Edit User' screen) - access permissions are not granted directly on user accounts.</span>";
				break;
			case "usertypes":
				$html .= "<span class='cms_normal'><b>User Types</b><br><br>The user types chief purpose is to allow different types of user disparate permissions to act of types of content.<br><br>Users can be members of more than one user type. The type's abilities on a particular content type are set out in the Content Type editing screen.</span>";
				break;
			case "pages":
				$html .= "<span class='cms_normal'><b>Pages</b><br><br>In CMS terms, pages are collections of content.<br><br>When a user requests a page, it is done so by it's 'pk' or 'Key' identity via the GET parameters in the URL.<br><br>A page has a template, and a list of content associated with it. Special tags within the page template are replaced by the content when a page is fed to a visitor.<br><br>The actual process is as follows;<li>Content for page is gathered together</li><li>Content templates are applied to the content</li><li>Page content templates are applied to the result of the previous step (if required) in line with the templates chosen when adding content to a page</li><li>The resultant chunks of content from the previous steps are then substituted into the page template, replacing place-holder tags.</li><br>It's worth pointing out that pages must have templates, but content and pagecontent templates are optional.</span>";
				break;
			case "content":
				$html .= "<span class='cms_normal'><b>Content</b><br><br>Content can be described as the information held within the pages of your website that changes.<br><br>Each page of your site is based upon a template, and various sections within that template can be represented by content. Think of content as your raw data.<br><br>Content can be shaped in a couple of ways before it arrives in it's destination page (or pages - one piece of content can appear in more than one place).<br><br>Content can have it's own template associated with it (where the title, body and dates added and edited are substituted into the template), and it can have a second template associated with it through it's association with a page as a piece of 'PageContent'.<br><br>Content is also rather clever - in that it can be scripted. You can write functions (usually in the html_func.php file) and call them from a piece of content, which then fills that piece of content with the result of the function - great for integrating with other systems.<br><br>The last trick Content has up it's sleeve is 'adhoc metadata'. This basically means that if you list some data within [metadata] and [/metadata], such as mydata=1, if the tag <code><!--mydata--></code> appears in the content template, it will get replaced by the data you specified (and of course the [metadata] section doesn't appear on the final page).</span>";
				break;
			case "contenttypes":
				$html .= "<span class='cms_normal'><b>Content Types</b><br><br>Each piece of content within CMS is assigned a 'Content Type'. The content types associate the abilities of different types of users with the creation and manipulation of content.<br><br>When you edit a content type, you can change the functionality available to the various user types for that type of content. It's much easier to do than explain, so just experiment.</span>";
				break;
			case "templates":
				$html .= "<span class='cms_normal'><b>Templates</b><br><br>Templates represent all that is static within a website. By using templates, you avoid replicating large swathes of site content.<br><br>There are several types of template within CMS, associated with different stages of building a page.<br><br>The top level is the 'page template'. When building a page, the first thing CMS does is get the page template as a starting point in it's HTML building process.<br><br>Next, CMS will gather all the content together that is associated with the page as 'page content'.<br><br>CMS then steps through each piece of content in turn, applying a 'content template' (if specified) to each piece of content. During this process, the title, body and dates added and edited of the piece of content are substituted into the content template.<br><br>The resultant content from the previous stage then has a 'Page Content Template' applied to it (if one is specified), again substituting title, body and dates into the template.<br><br>All templates use comment tags to surround the data that gets replaced - meaning that unreplaced data naturally doesn't appear.</span>";
				break;
			case "uploads":
				$html .= "<span class='cms_normal'><b>Uploads</b><br><br>The uploads section allows an administrator to upload content to the website without having to resort to FTP.<br><br>If you need to upload a graphic for temporary use within the site, just use the upload form, and the graphic will then be available at the path 'uploads/myfile.ext'.</span>";
				break;
		}
		
		$html .= "<br><div class='cms_small'><a href='admin.php?action=help' class='cms_link'>Back to Help Index...</a></div>";
		$html .= "</div>\n";

	}
	$html .= "</div>\n";

	return $html;

}


function html_problem(){
	
	global $db_tableprefix;
	
	$html = "<p align='center' class='cms_small'><img src='images/icon_problem.png'><br><b>A problem has occurred in CMS</b>.</p>";
	
	$html .= "<p align='center' class='cms_small'>Unfortunately a problem has occurred in CMS.<br>Please try to use the data below when diagnosing the problem.<br>If you cannot see the problem yourself, copy and paste the details below into a posting<BR>on the CMS Development Discussion Forum at PluggedOut.</p>\n";
	
	// try and get the last 3 items out of the audit trail (this will be the most recent)
	$con = db_connect();
	$sql = "SELECT * FROM ".$db_tableprefix."AuditTrail ORDER BY nAuditTrailId DESC LIMIT 5";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		while ($row =@ mysql_fetch_array($result)){
			$data .= "Audit Record : ".$row["nAuditTrailId"]."\n"
				."Page : ".stripslashes($row["cPage"])."\n"
				."User : ".stripslashes($row["nUserId"])."\n"
				."Session : ".stripslashes($row["cSessionId"])."\n"
				."Data :\n".stripslashes($row["cData"])."\n\n";				
		}
	} else {
		// could not get the data
		$data = "The data could not be retrieved - this is either because there is none, or we cannot connect to the database with the following SQL - ".$sql;
	}
	$html .= "<div align='center'><textarea class='cms_small' cols='80' rows='25'>".$data."</textarea></div>\n";
	
	
	return $html;
}



?>