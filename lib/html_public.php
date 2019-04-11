<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : lib/html_public.php
#= Version: 0.4.9 (2005-05-10)
#= Author : Jonathan Beckett
#= Email  : jonbeckett@pluggedout.com
#= Website: http://www.pluggedout.com/development/projects/cms
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



// Function    : process_adhoc_metadata()
// Description : looks for metadata within a content chunk, and processes it into itself
//               (which should already have the template in it)
// Arguments   : input - the content and template you wish to work on
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function process_adhoc_metadata($input) {

	if (strpos($input,"[metadata]")!==false && strpos($input,"[/metadata]")!==false){

		$result = $input;

		$content = substr($input,strpos($input,"[metadata]")+11,(strpos($input,"\n[/metadata]")-(strpos($input,"[metadata]")+11)));

		$data = explode("\n",$content);

		for ($i=0;$i<count($data);$i++){
			$pos = strpos($data[$i],"=");
			$name = substr($data[$i],0,$pos);
			$value = substr($data[$i],$pos+1);
			$result = str_replace("<!--ahmd:".$name."-->",$value,$result);
		}
		// remove the metadata section entirely
		$result = ereg_replace("\[metadata\].+\[\/metadata\]","",$result);

	} else {
		$result = $input;
	}

	return $result;
}


// Function    : process_content_metadata()
// Description : retrieves the metadata asssociated with a piece of content and processes
//               it into itself before returning it
// Arguments   : input - the content and template you wish to work on
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function process_content_metadata($contentid,$input) {
	
	global $db_tableprefix;
	
	$html_result = $input;
	
	// find out fields for content
	$con = db_connect();
	$sql = "SELECT ctp.nContentTypePropertyId,ctp.cPropertyName,ctp.cDataType,cod.* FROM ".$db_tableprefix."ContentTypeProperties ctp"
		." INNER JOIN ".$db_tableprefix."Content con ON ctp.nContentTypeId=con.nContentTypeId"
		." INNER JOIN ".$db_tableprefix."ContentData cod ON (con.nContentId=cod.nContentId AND ctp.nContentTypePropertyId=cod.nPropertyId)"
		." WHERE con.nContentId=".$contentid;
	
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			while ($row =@ mysql_fetch_array($result)){
				$name = stripslashes($row["cPropertyName"]);
				$data = stripslashes($row[$row["cDataType"]]);
				$html_result = str_replace("<!--comd:".$name."-->",$data,$html_result);
			}
		} else {
			// no fields to process
		}
	}
	
	return $html_result;
	
}

// Function    : process_page_metadata()
// Description : retrieves the metadata asssociated with a page and processes
//               it into itself before returning it
// Arguments   : input - the content and template you wish to work on
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function process_page_metadata($pageid,$input) {
	
	global $db_tableprefix;
	
	$html_result = $input;
	
	// find out fields
	$con = db_connect();
	$sql = "SELECT ptp.nPageTypePropertyId,ptp.cPropertyName,ptp.cDataType,pad.* FROM ".$db_tableprefix."PageTypeProperties ptp"
		." INNER JOIN ".$db_tableprefix."Pages pag ON ptp.nPageTypeId=pag.nPageTypeId"
		." INNER JOIN ".$db_tableprefix."PageData pad ON (pag.nPageId=pad.nPageId AND ptp.nPageTypePropertyId=pad.nPropertyId)"
		." WHERE pag.nPageId=".$pageid;

	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			while ($row =@ mysql_fetch_array($result)){
				$name = stripslashes($row["cPropertyName"]);
				$data = stripslashes($row[$row["cDataType"]]);
				$html_result = str_replace("<!--pgmd:".$name."-->",$data,$html_result);
			}
		} else {
			// no fields to process
		}
	}
	
	return $html_result;
	
}


// Function    : process_page_function()
// Description : Runs 3rd party functions (that usually reside in the includes
//               directory), to replace a tag of the following format;
//                 <!--pgfn:myfunc-->
//               with the results of that function (the function would normally
//               return HTML to replace the above tag. pgfn tags can be used in
//               any templates or content
// Arguments   : html       - the constructed page including it's content
//               pageid     - the unique id of the page
//               pagetypeid - the unique id of the pagetype
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-03-02
function process_page_function($html,$pageid,$pagetypeid){
	preg_match_all("/<!--pgfn:(([^-]*|[^-]->|-[^>])+)-->/i",$html,$a_fn_result);
	foreach ($a_fn_result as $func){
		foreach ($func as $afunc){
			if ($afunc!="" && substr($afunc,0,1)!="<"){
				$call_func = $afunc;
				$html_fn_result = call_user_func($call_func,$pageid,$pagetypeid);
				$html = str_replace("<!--pgfn:".$call_func."-->",$html_fn_result,$html);
			}
		}
	}
	return $html;
}


// Function    : process_pagecontent_function()
// Description : Runs 3rd party functions (that usually reside in the includes
//               directory), to replace a tag of the following format;
//                 <!--pcfn:myfunc-->
//               with the results of that function (the function would normally
//               return HTML to replace the above tag. pcfn tags should only be
//               used in pagecontent templates or content
// Arguments   : html          - the constructed piece of pagecontent
//               pageid        - the unique id of the page
//               pagetypeid    - the unique id of the pagetype
//               pagecontentid - the unique id of the pagecontent instance
//               contentid     - the unqieu id of the piece of content
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-03-02
function process_pagecontent_function($html,$pageid,$pagetypeid,$pagecontentid,$contentid){
	preg_match_all("/<!--pcfn:(([^-]*|[^-]->|-[^>])+)-->/i",$html,$a_fn_result);
	foreach ($a_fn_result as $func){
		foreach ($func as $afunc){
			if ($afunc!="" && substr($afunc,0,1)!="<"){
				$call_func = $afunc;
				$html_fn_result = call_user_func($call_func,$pageid,$pagetypeid,$pagecontentid,$contentid);
				$html = str_replace("<!--pcfn:".$call_func."-->",$html_fn_result,$html);
			}
		}
	}
	return $html;
}


// Function    : process_pagecontent_function()
// Description : Runs 3rd party functions (that usually reside in the includes
//               directory), to replace a tag of the following format;
//                 <!--cofn:myfunc-->
//               with the results of that function (the function would normally
//               return HTML to replace the above tag). cofn tags should only
//               be used in content
// Arguments   : html          - the constructed piece of content
//               pageid        - the unique id of the page
//               pagetypeid    - the unique id of the pagetype
//               pagecontentid - the unique id of the pagecontent instance
//               contentid     - the unqieu id of the piece of content
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-03-02
function process_content_function($html,$pageid,$pagetypeid,$pagecontentid,$contentid){
	preg_match_all("/<!--cofn:(([^-]*|[^-]->|-[^>])+)-->/i",$html,$a_fn_result);
	foreach ($a_fn_result as $func){
		foreach ($func as $afunc){
			if ($afunc!="" && substr($afunc,0,1)!="<"){
				$call_func = $afunc;
				$html_fn_result = call_user_func($call_func,$pageid,$pagetypeid,$pagecontentid,$contentid);
				$html = str_replace("<!--cofn:".$call_func."-->",$html_fn_result,$html);
			}
		}
	}
	return $html;
}


// Function    : html_create_page()
// Description : Builds the html to represent the entire body of the page
// Arguments   : pageid - Unique Page Identification
//               cancel_edit   - T/F/null - if T will stop editing functions if logged in (for caching)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-12-15
function html_create_page($pk,$force_guest){

	global $db_tableprefix;

	$con = db_connect();

	// work out which template to use
	$sql = "SELECT * FROM ".$db_tableprefix."Pages WHERE cPageKey='".$pk."'";
	$result = mysql_query($sql,$con);
	if ($result!=false) {
		$page_row = mysql_fetch_array($result);
	}

	if (mysql_num_rows($result)>0) {

		// get the template
		$sql = "SELECT * FROM ".$db_tableprefix."Templates WHERE nTemplateId=".$page_row["nTemplateId"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$template_row = mysql_fetch_array($result);
			$template = stripslashes($template_row["cTemplate"]);
		}
		
		// do the page title replacement
		$template = str_replace("<!--PAGETITLE-->",stripslashes($page_row["cTitle"]),$template);

		// get the page content
		$sql = "SELECT DISTINCT"
			." tco.nContentId AS nContentId"
			.",tco.cContentKey AS cContentKey"
			.",tco.nContentTypeId AS nContentTypeId"
			.",tco.cFile AS cContentFile"
			.",tco.cFunction AS cContentFunction"
			.",tco.cTitle AS cContentTitle"
			.",tco.cBody AS cContentBody"
			.",tco.dAdded AS dContentAdded"
			.",tco.dEdited AS dContentEdited"
			.",tco.nTemplateId AS nContentTemplateId"
			.",tpc.nTemplateId AS nPageContentTemplateId"
			.",tpc.nPageId AS nPageId"
			.",tpc.nPageContentId AS nPageContentId"
			.",tpc.nTemplateElementId AS nTemplateElementId"
			.",ttl.cTemplate AS cContentTemplate"
			.",ttp.cTemplate AS cPageContentTemplate"
			.",tct.cFile AS cContentTypeFile"
			.",tct.cFunction AS cContentTypeFunction"
			.",tpg.cTitle AS cPageTitle"
			." FROM ".$db_tableprefix."PageContent tpc"
			." INNER JOIN ".$db_tableprefix."Pages tpg ON tpg.nPageId=tpc.nPageId"
			." INNER JOIN ".$db_tableprefix."Content tco ON tpc.cContentKey=tco.cContentKey"
			." INNER JOIN ".$db_tableprefix."ContentType tct ON tco.nContentTypeId=tct.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."ContentSecurity tcs ON (tco.nContentTypeId=tcs.nContentTypeId AND tum.nUserTypeId=tcs.nUserTypeId)"
			." INNER JOIN ".$db_tableprefix."UserTypeMember tum ON tcs.nUserTypeId=tum.nUserTypeId"
			." LEFT OUTER JOIN ".$db_tableprefix."Templates ttl ON tco.nTemplateId=ttl.nTemplateId"
			." LEFT OUTER JOIN ".$db_tableprefix."Templates ttp ON tpc.nTemplateId=ttp.nTemplateId"
			." WHERE tpg.cPageKey='".$pk."'"
			." AND tct.cApprovalRequired=tco.cApproved"
			." AND tcs.cView='x'"
			." AND tum.nUserId=".$_SESSION["cms_userid"]
			." AND (  ( tco.dStart<=now() AND tco.dEnd>=now() )   OR  (tco.dStart=0 AND tco.dEnd=0) )"
			." ORDER BY tpc.nTemplateElementId,tpc.nIndex";

		//." AND IF(tco.dStart>0 AND tco.dEnd>0,tco.dStart<=now() AND tco.dEnd>=now())"

		$result = mysql_query($sql,$con);

		if ($result!=false){
		
			// are there any content rows to work on?
			if (mysql_num_rows($result)>0){

				if ($result!=false) {

					// initialise a counter - used at the end of the following while
					// loop to populate an array of associative arrays holding content
					$i=0;

					// loop through the content to go into the page, and put it into an array
					// while doing it, implement the content template on the data if used.
					while ($pagecontent_row =@ mysql_fetch_array($result)){

						$i++;

						// get the content chunk
						$content_body = $pagecontent_row["cContentBody"];

						// implement the content function (used to modify or populate content programatically)
						if ($pagecontent_row["cContentFunction"]!="") {
							$content_body = call_user_func($pagecontent_row["cContentFunction"],$force_guest,stripslashes($pagecontent_row["cContentTitle"]),stripslashes($content_body),stripslashes($pagecontent_row["dContentAdded"]),stripslashes($pagecontent_row["dContentEdited"]));
						}

						// implement the pagecontent function (used to modify or populate content programatically)
						if ($pagecontent_row["cContentTypeFunction"]!="") {
							$content_body = call_user_func($pagecontent_row["cContentTypeFunction"],$force_guest,stripslashes($pagecontent_row["cContentTitle"]),stripslashes($content_body),stripslashes($pagecontent_row["dContentAdded"]),stripslashes($pagecontent_row["dContentEdited"]));
						}

						// process the content template
						if ($pagecontent_row["cContentTemplate"]!=""){

							// get the content template
							$content_template = $pagecontent_row["cContentTemplate"];

							// swap all appropriate data into the content template
							$content_template = str_replace("<!--TITLE-->",stripslashes($pagecontent_row["cContentTitle"]),$content_template);
							$content_template = str_replace("<!--DATEADDED-->",stripslashes($pagecontent_row["dContentAdded"]),$content_template);
							$content_template = str_replace("<!--DATEEDITED-->",stripslashes($pagecontent_row["dContentEdited"]),$content_template);
							$content_template = str_replace("<!--BODY-->",stripslashes($pagecontent_row["cContentBody"]),$content_template);

						} else {

							// do not manipulate it
							$content_template = $content_body;

						}

						// process <!--cofn:....--> replacements
						$content_template = process_content_function($content_template,$page_row["nPageId"],$page_row["nPageTypeId"],$pagecontent_row["nPageContentId"],$pagecontent_row["nContentId"]);

						// process the pagecontent template
						if ($pagecontent_row["cPageContentTemplate"]!=""){

							// get the template
							$pagecontent_template = $pagecontent_row["cPageContentTemplate"];

							// swap all appropriate data into the template (remembering to put the constructed content_template into the body
							$pagecontent_template = str_replace("<!--TITLE-->",stripslashes($pagecontent_row["cContentTitle"]),$pagecontent_template);
							$pagecontent_template = str_replace("<!--DATEADDED-->",stripslashes($pagecontent_row["dContentAdded"]),$pagecontent_template);
							$pagecontent_template = str_replace("<!--DATEEDITED-->",stripslashes($pagecontent_row["dContentEdited"]),$pagecontent_template);
							$pagecontent_template = str_replace("<!--BODY-->",stripslashes($content_template),$pagecontent_template);

						} else {

							// do not manipulate it
							$pagecontent_template = $content_template;

						}

						// process the content metadata
						$pagecontent_template = process_content_metadata($pagecontent_row["nContentId"],$pagecontent_template);

						// process the ad-hoc metadata
						$pagecontent_template = process_adhoc_metadata($pagecontent_template);

						// process <!--pcfn:....--> replacements
						$pagecontent_template = process_pagecontent_function($pagecontent_template,$page_row["nPageId"],$page_row["nPageTypeId"],$pagecontent_row["nPageContentId"],$pagecontent_row["nContentId"]);


						// put the constructed content in a variable
						$content = $pagecontent_template;

						// put the changed content back into the object
						// if the user is logged in, show the edit tags for content chunks

						if ($_SESSION["cms_userid"]!="1" && $force_guest!="T" && can_user_edit_this_contenttype($pagecontent_row["nContentTypeId"])!=""){
							// user is logged in and we are not forcing guest mode
							$edit_title = "key [".$pagecontent_row["cContentKey"]."]"
								." title [".$pagecontent_row["cContentTitle"]."]";

							$pagecontent_row["cContentBody"] = "<div style='border:1px dashed #f00;'>"
								."<div class='cms_small'>"
								."<a href='admin.php?action=content_edit&contentid=".$pagecontent_row["nContentId"]."' target='_blank'><img src='images/smallicon_content_edit.png' width='16' height='16' border='0' title='".$edit_title."'></a>"
								."</div>"
								.stripslashes($content)
								."</div>";
						} else {

							// user is NOT logged in
							$pagecontent_row["cContentBody"] = stripslashes($content);
						}

						//$pagecontent_row["cContentBody"] = stripslashes($content);

						// put the pagecontent_row into an array of rows
						$pagecontent_rows[$i] = $pagecontent_row;

					}
				} else {
					print "problem with sql<br>".$sql;
				}


				// go through the content array and put stuff in template
				// (i.e. do replacements)
				$elem_data = "";

				$html = $template;

				for($i=1;$i<=count($pagecontent_rows);$i++){

					// add all the content for an element together before replacing the tag in the template
					if ($pagecontent_rows[$i]["nTemplateElementId"]==$last_elem){

						$elem_data .= stripslashes($pagecontent_rows[$i]["cContentBody"]);

					} else {

						// do replacement for data built so far
						$find = "<!--PAGECONTENT".$last_elem."-->";
						$replace = $elem_data;
						$html = str_replace($find,$replace,$html);

						// reset data for next section
						$elem_data = stripslashes($pagecontent_rows[$i]["cContentBody"]);
						$last_elem = $pagecontent_rows[$i]["nTemplateElementId"];
					}
				}

				// do final replace
				$find = "<!--PAGECONTENT".$last_elem."-->";
				$replace = $elem_data;
				$html = str_replace($find,$replace,$html);

			} else {

				// there is no content visible on the page... put the template into the variable we return
				$html = $template;

			}

		} else {
			$html = $sql;
		}

		// process the page metadata against whatever has been built - content or no content
		$html = process_page_metadata($page_row["nPageId"],$html);
		
		$html = process_page_function($html,$page_row["nPageId"],$page_row["nPageTypeId"]);
		

	} else {

		// there were no rows returned from the Page table

	}

	return $html;

}


// Function    : html_page()
// Description : Returns the html for a page - which could be in the cache or require building from scratch
// Arguments   : pageid - Unique Page Identification
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-12-15
function html_page($pk){

	// record this page request in the statistics table
	global $db_tableprefix;
	$con = db_connect();
	$sql = "INSERT INTO ".$db_tableprefix."Statistics (nUserId,cPageKey,dView,cIPAddress,cSessionId)"
		." VALUES ("
		.$_SESSION["cms_userid"]
		.",'".mysql_escape_string($pk)."'"
		.",now()"
		.",'".mysql_escape_string($_SERVER["REMOTE_ADDR"])."'"
		.",'".mysql_escape_string($_REQUEST["PHPSESSID"])."'"
		.")";
	$result = mysql_query($sql,$con);
	
	// only use the cache if we are the guest user
	if ($_SESSION["cms_userid"]!="1"){

		// build the html from source
		$html = html_create_page($pk,"");

	} else {

		$filename = "cache/".$pk.".htm";

		// if the file exists in the cache
		if (file_exists($filename)){

			// just read it from the cache
			$handle = fopen($filename,"r");
			$html = fread($handle,filesize($filename));
			fclose($handle);


		} else {

			// create the data
			$html = html_create_page($pk,"");

		}

	}
	return $html;
}

?>