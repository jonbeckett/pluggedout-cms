<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_example_func.php
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

// Function    : search()
// Description : *UNFINISHED* Do not use this yet
// Author      : Jonathan Beckett
// Last Change : 2005-03-02
function search(){
	
	global $db_tableprefix;
	
	// output the form
	$html = "<form method='POST' action='index.php?pk=search'>\n"
		."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#ccbbaa'>\n"
		."<tr><td bgcolor='#ccbbaa' colspan='2' class='normal'><b>Search</b></td></tr>\n"
		."<tr><td bgcolor='#ffffff' class='normal'>Keywords</td><td bgcolor='#ffffff'><input type='text' name='keywords' value='".$_POST["keywords"]."' size='40'></td></tr>\n"
		."<tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Search' class='button'></td></tr>\n"
		."</table>\n"
		."</form>\n";
	
	// output the results
	if ($_POST["keywords"]!=""){
		
		// prepare keywords
		$aKeywords = explode(" ",mysql_escape_string($_POST["keywords"]));
		
		$keywords = "'".implode(" +",$aKeywords)."'";
		
		$con = db_connect();
		$sql = "SELECT tpg.*,tco.cBody,MATCH(tco.cBody) AGAINST (".$keywords.") AS nMatch FROM ".$db_tableprefix."Content tco,".$db_tableprefix."Pages tpg,".$db_tableprefix."PageContent tpc"
			." WHERE"
			." tpg.nPageId=tpc.nPageId"
			." AND tpc.cContentKey=tco.cContentKey"
			." AND tpc.nTemplateElementId=1"
			." AND MATCH(tco.cBody) AGAINST (".$keywords.")"
			." ORDER BY nMatch DESC;";
		
		$result = mysql_query($sql,$con);
				
		if ($result!=false){
			
			// output the results
			
			$html .= "<table colspan='2' border='0' cellspacing='1' cellpadding='2' bgcolor='#ccbbaa'>\n"
				."<tr><td colspan='2' bgcolor='#ccbbaa' class='normal'><b>Search Results</b></td></tr>\n";
				
			if (mysql_num_rows($result)>0) {
				
				$html .= "<tr><td colspan='2' bgcolor='#ffffff' class='body'>There are ".mysql_num_rows($result)." search results for '".$_POST["keywords"]."'.</td></tr>\n";
				
				$html .= "<tr><td bgcolor='#eeddcc' class='normal'><b>Page</b></td><td bgcolor='#eeddcc' class='normal'><b>Score</b></td></tr>\n";
				while ($row=@mysql_fetch_array($result)){
					$body = strip_tags($row["cBody"]);
					
					$body = str_replace($_POST["keywords"],"<b>".$_POST["keywords"]."</b>",$body);
					
					if (strlen($body)>160){
						$body = substr($body,0,160)."...";
					} else {
						// leave alone
					}
					
					$html .= "<tr><td bgcolor='#ffffff'>"
						."<div class='body'><a href='index.php?pk=".$row["cPageKey"]."'><b>".$row["cTitle"]."</b></a><br>".$body."</td><td bgcolor='#ffffff'>".number_format($row["nMatch"],2)."</div>"
						."</td></tr>\n";
				}
			} else {
				$html .= "<tr><td colspan='2' bgcolor='#ffffff' class='body'>No Search results for '".$_POST["keywords"]."'.</td></tr>\n";
			}
			$html .= "</table>\n";
			
		} else {
			// problem with SQL
			$html .= "<div class='normal'>Problem with SQL [".$sql."]</div>\n";
		}
		
	}
	
	return $html;
}


// Function    : html_login()
// Description : This provides a user login box when used as the basis for a content chunk
//               You typically fill the content with a placeholder value (such as an HTML comment)
//               and then put "html_login" in the content function field.
// Author      : Jonathan Beckett
// Last Change : 2005-03-02
function html_login($force_guest=""){
	// show a login form if appropriate
	if ($_SESSION["cms_userid"]!="1" && $force_guest!="T"){
		// we are not the guest - therefore we are logged in
		$html = "<form method='POST' action='admin_exec.php?action=user_login&dest=index.php' style='padding:0px;margin:0px;'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' width='100%' bgcolor='#cccccc'>\n"
			."<tr><td bgcolor='#cccccc' class='small'>Intranet Login</td></tr>\n"
			."<tr><td bgcolor='#ffffff' align='center'>"
			."<div class='small'>Logged in as</div>"
			."<div class='large'><b>".$_SESSION["cms_username"]."</b></div>"
			."<div class='small'><a href='admin_exec.php?action=user_logout&dest=index.php'>Logout</a></div>"
			."</td></tr>\n"
			."</table>\n";
	} else {
		// we are the guest - therefore we are not logged in
		$html = "<form method='POST' action='admin_exec.php?action=user_login&dest=index.php' style='padding:0px;margin:0px;'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' width='100%' bgcolor='#cccccc'>\n"
			."<tr><td bgcolor='#cccccc' class='small'>Intranet Login</td></tr>\n"
			."<tr><td bgcolor='#ffffff'>"
			."<table border='0' cellspacing='0' cellpadding='2' width='100%'>\n"
			."<tr>\n"
			."<td bgcolor='#ffffff' class='small'>Username</td>\n"
			."<td bgcolor='#ffffff' class='small'><input type='text' class='small' name='username'></td>\n"
			."</tr>\n"
			."<tr>\n"
			."<td bgcolor='#ffffff' class='small'>Password</td>\n"
			."<td bgcolor='#ffffff' class='small'><input type='password' class='small' name='password'></td>\n"
			."</tr>\n"
			."<td bgcolor='#ffffff' colspan='2' class='menu' align='right'><input type='submit' class='button' value='Login'></td>\n"
			."</table>\n"
			."</td></tr></table>\n"
			."</form>\n";
	}
	return $html;
}


// Function    : sample_page_function()
// Arguments   : pageid,pagetypeid
// Returns     : HTML (plain text)
// Description : if you put the following tag inside a page template or any body
//               in the page it will get replaced with the result of this function
//                 <!--pgfn:sample_page_function-->
//               all pgfn functions must accept pageid and pagetypeid - therefore
//               you can use that information to lookup things in the CMS database
// Author      : Jonathan Beckett
// Last Change : 2005-03-02
function sample_page_function($pageid,$pagetypeid){
	$html = "Sample Page Function";
	return $html;
}


// Function    : sample_pagecontent_function()
// Arguments   : pageid,pagetypeid,pagecontentid,contentid
// Returns     : HTML (plain text)
// Description : if you put the following tag inside a page template or any body
//               in the page it will get replaced with the result of this function
//                 <!--pcfn:sample_page_function-->
//               all pcfn functions must accept pageid, pagetypeid, pagecontentid and contentid
//               you can use that information to lookup things in the CMS database
// Author      : Jonathan Beckett
// Last Change : 2005-03-02
function sample_pagecontent_function($pageid,$pagetypeid,$pagecontentid,$contentid){
	$html = "Sample PageContent Function";
	return $html;
}


// Function    : sample_content_function()
// Arguments   : pageid,pagetypeid,pagecontentid,contentid
// Returns     : HTML (plain text)
// Description : if you put the following tag inside a page template or any body
//               in the page it will get replaced with the result of this function
//                 <!--cofn:sample_page_function-->
//               all pcfn functions must accept pageid, pagetypeid, pagecontentid and contentid
//               you can use that information to lookup things in the CMS database
// Author      : Jonathan Beckett
// Last Change : 2005-03-02
function sample_content_function($pageid,$pagetypeid,$pagecontentid,$contentid){
	$html = "Sample Content Function";
	return $html;
}

?>