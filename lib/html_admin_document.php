<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_document.php
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

function html_doctype_list(){

	global $db_tableprefix;
	global $results_per_page;
	
	if ($_SESSION["cms_admin"]!=""){

		$con = db_connect();

		if ($_GET["list_from"]!=""){
			$list_from = $_GET["list_from"];
		} else {
			$list_from = "0";
		}
		
		// Build page list to limit search result
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."DocumentType";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
			$html_pagelinks = "List Results : ";
			for($i=0;$i<$count;$i+=$results_per_page){
				$start = $i;
				if ($i>=($count-$results_per_page)){
					$start = $i;
					$end = $count-1;
				} else {
					$start = $i;
					$end = $i+$results_per_page-1;
				}
				$html_link = "<a href='admin.php?action=doctype_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}


		$sql = "SELECT dt.nDocumentTypeId,dt.cName,dt.cDescription"
			." FROM ".$db_tableprefix."DocumentType dt"
			." ORDER BY dt.cName LIMIT ".$list_from.",".$results_per_page;

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Document Types'></td>\n"
			."<td class='cms_huge'>Document Type List</td>\n"
			."</tr></table>\n";

		$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' colspan='3' class='cms_small' background='images/grad_bg.gif'><b>Document Types</b></td></tr>\n"
			."<tr><td colspan='3' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
			."<tr><td bgcolor='#cccccc' class='cms_small'>Name</td><td bgcolor='#cccccc' class='cms_small'>Description</td><td bgcolor='#cccccc' class='cms_small'>Controls</td></tr>\n";

		$result = mysql_query($sql,$con);
		if ($result!=false){
			// loop through recordset
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'>".$row["cName"]."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$row["cDescription"]."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>"
						."<a class='cms_button_thin' href='admin.php?action=doctype_edit&doctypeid=".$row["nDocumentTypeId"]."'>Edit</a>"
						."&nbsp;<a class='cms_button_thin' href='admin.php?action=doctype_del&doctypeid=".$row["nDocumentTypeId"]."'>Delete</a>"
						."</td>"
						."</tr>\n";
				}
			} else {
				$html .= "<tr><td bgcolor='#ffffff' class='cms_small' colspan='3'>There are no document types yet.</td></tr>\n";
			}
		} else {
			// problem with SQL
			$html = "Problem with SQL [".$sql."]\n";
		}
		$html .= "<tr><td bgcolor='#ffffff' colspan='3' align='right'><a class='cms_button_thin' href='admin.php?action=doctype_add'>Add Document Type</a></td></tr>\n";

		$html .= "</table>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}

function html_doctype_add(){

	global $db_tableprefix;
	
	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();

		// prepare the select drop-down for the repository type
		$html_select = "<select name='repositoryid' class='cms_small'>\n";
		$sql = "SELECT * FROM ".$db_tableprefix."Repository ORDER BY nRepositoryId";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html_select .= "<option value='".$row["nRepositoryId"]."'>".stripslashes($row["cPath"])."</option>\n";
				}
			} else {
				// no repositories defined
			}
		} else {
			// problem with repository lookup sql
		}
		$html_select .= "</select>\n";

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Document Type Add'></td>\n"
			."<td class='cms_huge'>Add Document Type Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=doctype_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Document Type Form</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Name</td><td bgcolor='#ffffff'><input type='text' name='name' size='40' class='cms_text'></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Description</td><td bgcolor='#ffffff'><textarea name='description' class='cms_text' cols='70' rows='3'></textarea></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Repository</td><td bgcolor='#ffffff'>".$html_select."</td></tr>\n"
			."<tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Add Document Type' class='cms_button'></td></tr>\n"
			."</table>\n";

		$html .= "</div>\n";

		} else {
			header("Location: admin.php");
		}

	return $html;

}


function html_doctype_del(){

	if ($_SESSION["cms_admin"]!=""){
	
		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Document Type Delete'></td>\n"
			."<td class='cms_huge'>Delete Document Type</td>\n"
			."</tr></table>\n";

		$html .= "<p class='cms_large'>Delete Document Type Confirmation</p>\n"
			."<p class='cms_normal'>Are you sure you want to delete this document type?</p>\n"
			."<p class='cms_normal'><a href='admin_exec.php?action=doctype_del&doctypeid=".$_GET["doctypeid"]."'>Yes</a>"
			."&nbsp;&nbsp;<a href='dms_admin.php?action=doctype_list'>No</a></p>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


function html_doctype_edit(){

	$doctypeid = $_GET["doctypeid"];

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		// connect to database
		$con = db_connect();

		// get the doctype record
		$sql = "SELECT * FROM ".$db_tableprefix."DocumentType WHERE nDocumentTypeId=".$doctypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){

				$doctype_row = mysql_fetch_array($result);
				$name = stripslashes($doctype_row["cName"]);
				$description = stripslashes($doctype_row["cDescription"]);

			} else {
				// no rows returned
				$html = "Problem with SQL [".$sql."]\n";
			}
		} else {
			// problem with SQL
		}

		// prepare the select drop-down for the repository type
		$html_rep_select = "<select name='repositoryid' class='cms_small'>\n";
		$sql = "SELECT * FROM ".$db_tableprefix."Repository ORDER BY nRepositoryId";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					if ($row["nRepositoryId"]==$doctype_row["nRepositoryId"]){
						$selected = "selected";
					} else {
						$selected = "";
					}
					$html_rep_select .= "<option value='".$row["nRepositoryId"]."' ".$selected.">".stripslashes($row["cPath"])."</option>\n";
				}
			} else {
				// no repositories defined
			}
		} else {
			// problem with repository lookup sql
		}
		$html_rep_select .= "</select>\n";

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_pages.png' width='48' height='52' title='CMS Document Type Edit'></td>\n"
			."<td class='cms_huge'>Document Type Edit Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='post' action='admin_exec.php?action=doctype_edit'>\n"
			."<input type='hidden' name='doctypeid' value='".$doctypeid."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Document Type</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Name</td><td bgcolor='#ffffff'><input name='name' type='text' class='cms_text' value='".$name."'></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Description</td><td bgcolor='#ffffff'><textarea name='description' class='cms_text' cols='70' rows='3'>".$description."</textarea></td></tr>\n"
			."<tr><td bgcolor='#ffffff' class='cms_small'>Repository</td><td bgcolor='#ffffff'>".$html_rep_select."</td></tr>\n"
			."<tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Make Changes to Header'></td></tr>\n"
			."</table>\n"
			."</form>\n";


		// showing the Document Security entries
		$sql = "SELECT nDocumentSecurityId,".$db_tableprefix."DocumentSecurity.nUserTypeId,".$db_tableprefix."DocumentSecurity.nDocumentTypeId,cUserTypeName,cView,cAdd,cEdit,cDelete,cReplace"
			." FROM ".$db_tableprefix."DocumentSecurity"
			." INNER JOIN ".$db_tableprefix."UserType ON ".$db_tableprefix."DocumentSecurity.nUserTypeId=".$db_tableprefix."UserType.nUserTypeId"
			." WHERE nDocumentTypeId=".$_GET["doctypeid"]
			." ORDER BY cUserTypeName";

		$result = mysql_query($sql,$con);

		$html .= "<form method='POST' action='admin_exec.php?action=docsecurity_edit'>\n"
			."<input type='hidden' name='doctypeid' value='".$_GET["doctypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>"
			."<tr><td colspan='6' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Document Type Security</b></td></tr>\n"
			."<tr>"
			."<td bgcolor='#dddddd' class='cms_small'>UserType</td>"
			."<td bgcolor='#dddddd' class='cms_small'>View</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Add</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Edit</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Delete</td>"
			."<td bgcolor='#dddddd' class='cms_small'>Replace</td>"
			."<td bgcolor='#ffaa00' class='cms_small'>Remove</td>"
			."</tr>\n";

		if ($result!=false){
			while($row=@mysql_fetch_array($result)){
				if ($row["cView"]!=""){$view_checked="checked";} else {$view_checked="";}
				if ($row["cAdd"]!=""){$add_checked="checked";} else {$add_checked="";}
				if ($row["cEdit"]!=""){$edit_checked="checked";} else {$edit_checked="";}
				if ($row["cDelete"]!=""){$delete_checked="checked";} else {$delete_checked="";}
				if ($row["cReplace"]!=""){$replace_checked="checked";} else {$replace_checked="";}
				$html.="<tr>\n"
					."<td bgcolor='#ffffff' class='cms_small'>".$row["cUserTypeName"]."</td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_view_".$row["nDocumentSecurityId"]."' type='checkbox' ".$view_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_add_".$row["nDocumentSecurityId"]."' type='checkbox' ".$add_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_edit_".$row["nDocumentSecurityId"]."' type='checkbox' ".$edit_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_delete_".$row["nDocumentSecurityId"]."' type='checkbox' ".$delete_checked." value='x'></td>\n"
					."<td bgcolor='#ffffff' class='cms_small'><input name='chkbox_replace_".$row["nDocumentSecurityId"]."' type='checkbox' ".$replace_checked." value='x'></td>\n"
					."<td bgcolor='#ffaa00' class='cms_small'><input name='chkbox_remove_".$row["nDocumentSecurityId"]."' type='checkbox'></td>"
					."</tr>\n";
			}
		} else {
			print "<li class='cms_small'>Problem with SQL [".$sql."]</li>\n";
		}

		// prepare usertype select
		$usertype_select = "<select name='usertype_new'><option value=''>&nbsp;</option>";
		$sql = "SELECT * FROM ".$db_tableprefix."UserType ORDER BY cUserTypeName";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			while ($row=@mysql_fetch_array($result)){
				$usertype_select.="<option value='".$row["nUserTypeId"]."'>".stripslashes($row["cUserTypeName"])."</option>";
			}
		} else {
			print "<li>Problem with SQL [".$sql."]</li>\n";
		}
		$usertype_select.="</select>\n";

		$html.="<tr>\n"
			."<td bgcolor='#ffaa00' class='cms_small'>".$usertype_select."</td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_view_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_add_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_edit_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_delete_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00'><input name='chkbox_replace_new' type='checkbox' value='x'></td>\n"
			."<td bgcolor='#ffaa00' class='cms_small'>&laquo;&nbsp;New</td>\n"
			."</tr>\n";

		$html.= "<tr><td colspan='7' align='right' bgcolor='#ffffff'><input type='submit' value='Make Changes' class='cms_button'></td></tr>\n"
			."</table>\n"
			."</form>\n";




		// show the properties associated with the doctype

		$html .= "<form method='POST' action='admin_exec.php?action=doctype_prop_edit'>\n"
			."<input type='hidden' name='doctypeid' value='".$doctypeid."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='7' background='images/grad_bg.gif'><b>Document Type Properties</b></td></tr>\n";

		$sql = "SELECT * FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypeId=".$doctypeid." ORDER BY nSortIndex";
		$result = mysql_query($sql,$con);

		if ($result!=false) {

			$html .= "<input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

			// column headings
			$html .= "<tr>"
				."<td bgcolor='#cccccc' class='cms_small'>Name</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Description</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Data Type</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Input Mask</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Mandatory</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Hidden</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Unique</td>"
				."<td bgcolor='#cccccc' class='cms_small'>Delete</td>"
				."</tr>\n";

			$datatypes[1] = "nDataInt";
			$datatypes[2] = "nDataBigInt";
			$datatypes[3] = "dDataDate";
			$datatypes[4] = "bDataBoolean";
			$datatypes[5] = "nDataFloat";
			$datatypes[6] = "cDataVarchar";
			$datatypes[7] = "cDataMediumText";
			$datatypes[8] = "bDataBlob";

			$datatypenames[1] = "Integer";
			$datatypenames[2] = "BigInt";
			$datatypenames[3] = "Date";
			$datatypenames[4] = "Boolean";
			$datatypenames[5] = "Float";
			$datatypenames[6] = "Varchar";
			$datatypenames[7] = "MediumText";
			$datatypenames[8] = "Blob";

			if (mysql_num_rows($result)>0){

				$i = 0;

				while ($prop_row =@ mysql_fetch_array($result)){

					$i++;

					// prepare data
					$name = stripslashes($prop_row["cPropertyName"]);
					$description = stripslashes($prop_row["cPropertyDescription"]);
					$inputmask = stripslashes($prop_row["cInputMask"]);

					// prepare the datatype dropdown for each
					$html_datatype_select = "<select name='row".$i."_datatype'>";
					for ($j=1;$j<=count($datatypes);$j++){
						if ($datatypes[$j]==$prop_row["cDataType"]){
							$sel = "selected";
						} else {
							$sel = "";
						}
						$html_datatype_select .= "<option value='".$datatypes[$j]."' ".$sel.">".$datatypenames[$j]."</option>";
					}
					$html_datatype_select .= "</select>";

					if ($prop_row["bMandatory"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_mandatory = "<input name='row".$i."_mandatory' type='checkbox' value='1' ".$checked.">";

					if ($prop_row["bHidden"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_hidden = "<input name='row".$i."_hidden' type='checkbox' value='1' ".$checked.">";

					if ($prop_row["bUnique"]>0){
						$checked = " checked ";
					} else {
						$checked = "";
					}
					$html_checkbox_unique = "<input name='row".$i."_unique' type='checkbox' value='1' ".$checked.">";

					$html .= "<tr>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_docpropid' type='hidden' value='".$prop_row["nDocumentTypePropertyId"]."'><input name='row".$i."_name' type='text' class='cms_text' size='12' value='".$name."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_description' type='text' class='cms_text' size='25' value='".$description."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_datatype_select."</td>"
						."<td bgcolor='#ffffff' class='cms_small'><input name='row".$i."_inputmask' type='text' class='cms_text' size='12' value='".$inputmask."'></td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_mandatory."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_hidden."</td>"
						."<td bgcolor='#ffffff' class='cms_small'>".$html_checkbox_unique."</td>"
						."<td bgcolor='#ffffff' c;ass='cms_small'><input name='row".$i."_del' type='checkbox' value='1'></td>"
						."</tr>\n";
				}

			} else {
				// no rows returned
				$html .= "<tr><td bgcolor='#ffffff' class='cms_small' colspan='8'>No Properties Returned</td></tr>\n";
			}

			// put the final line in to add new entries

			// prepare the datatype dropdown for new lines
			$html_datatype_select = "<select name='new_datatype'>";
			for ($j=1;$j<=count($datatypes);$j++){
				if ($datatypes[$i]==$prop_row["cDataType"]){
					$sel = "selected";
				} else {
					$sel = "";
				}
				$html_datatype_select .= "<option value='".$datatypes[$j]."' ".$sel.">".$datatypenames[$j]."</option>";
			}
			$html_datatype_select .= "</select>";

			$html .= "<tr>"
				."<td bgcolor='#ffaa00'><input name='new_name' type='text' class='cms_text' size='12'></td>"
				."<td bgcolor='#ffaa00'><input name='new_description' type='text' class='cms_text' size='25'></td>"
				."<td bgcolor='#ffaa00'>".$html_datatype_select."</td>"
				."<td bgcolor='#ffaa00'><input name='new_inputmask' type='text' class='cms_text' size='12'></td>"
				."<td bgcolor='#ffaa00'><input name='new_mandatory' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00'><input name='new_hidden' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00'><input name='new_unique' type='checkbox' value='1'></td>"
				."<td bgcolor='#ffaa00' class='cms_small'>&laquo;&nbsp;New</td>"
				."</tr>\n";

		} else {
			// problem with SQL
			$html = "Problem with SQL [".$sql."]\n";
		}
		$html .= "<tr><td bgcolor='#ffffff' colspan='8' align='right'><input type='submit' class='cms_button' value='Make Changes to Properties'></td></tr>\n";
		$html .= "</table>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


function html_document_add(){

	global $db_tableprefix;
	
	$con = db_connect();
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_document.png' width='48' height='52' title='CMS Document Add'></td>\n"
		."<td class='cms_huge'>Add Document Form</td>\n"
		."</tr></table>\n";

	// the document add form has two stages - asking for a document type, then asking for the details
	if ($_GET["doctypeid"]!=""){

		// get the properties that need to be filled for the doctype
		$sql = "SELECT * FROM ".$db_tableprefix."DocumentTypeProperties"
			." WHERE nDocumentTypeId=".$_GET["doctypeid"];
		
		$result = mysql_query($sql,$con);
		if ($result!=false){
	
		$html .= "<form enctype='multipart/form-data' method='POST' action='admin_exec.php?action=document_add'>\n"
			."<input type='hidden' name='doctypeid' value='".$_GET["doctypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Document Form</b></td></tr>\n"
			."<tr><td bgcolor='#dddddd' class='cms_small'>Filename</td><td bgcolor='#ffffff'><input name='userfile' type='file' size='70' class='cms_small'></td></tr>\n";
			
			if (mysql_num_rows($result)>0){

				$html .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
				$html .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

				$i=0;
				while($prop_row =@ mysql_fetch_array($result)){
					$i++;
					switch ($prop_row["cDataType"]){
						case "nDataInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "nDataBigInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "dDataDate":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "bDataBoolean":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "nDataFloat":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "cDataVarchar":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
						case "cDataMediumText":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='5' class='cms_text'></textarea></td></tr>\n";
							break;
						case "bDataBlob":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='' class='cms_text'></td></tr>\n";
							break;
					}

					$html .= "";
				}

			}
			
			$html .= "<tr><td bgcolor='#ffffff' class='small' align='right' colspan='2'><input type='submit' class='cms_button' value='Add Document'></td></tr>"
				."</table>\n"
				."</form>\n";
			
		} else {
			// sql failed retrieving properties
			
		}
	} else {

		// show a pick list of document types (that the logged in user can add)
		$sql = "SELECT * FROM ".$db_tableprefix."DocumentType ORDER BY cName";
		$result = mysql_query($sql,$con);
		if($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<li class='cms_normal'><a href='admin.php?action=document_add&doctypeid=".$row["nDocumentTypeId"]."'>".stripslashes($row["cName"])."</a></li>\n";
				}
			}
		}
	}

	$html .= "</div>\n";

	return $html;
}

// Function    : html_repository_list()
// Description : Used in the admin page to show a list of repositories
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_repository_list(){

	global $db_tableprefix;
	global $results_per_page;
	
	if ($_SESSION["cms_admin"]!=""){

		$con = db_connect();

		if ($_GET["list_from"]!=""){
			$list_from = $_GET["list_from"];
		} else {
			$list_from = "0";
		}
		
		// Build page list to limit search result
		$sql = "SELECT COUNT(*) AS nCount FROM ".$db_tableprefix."Repository";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
			$count = $row["nCount"];
			$html_pagelinks = "List Results : ";
			for($i=0;$i<$count;$i+=$results_per_page){
				$start = $i;
				if ($i>=($count-$results_per_page)){
					$start = $i;
					$end = $count-1;
				} else {
					$start = $i;
					$end = $i+$results_per_page-1;
				}
				$html_link = "<a href='admin.php?action=repository_list&list_from=".$start."'>".($start+1)." to ".($end+1)."</a>";
				if ($i==$list_from){
					$html_pagelinks .= "<b>".$html_link."</b>&nbsp;";
				} else {
					$html_pagelinks .= $html_link."&nbsp;";
				}
			}
		}


		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_document.png' width='48' height='52' title='CMS Repositories'></td>\n"
			."<td class='cms_huge'>Repository List</td>\n"
			."</tr></table>\n";

		// Content
		$sql = "SELECT * FROM ".$db_tableprefix."Repository ORDER BY nRepositoryId";
		$result = mysql_query($sql,$con);

		if ($result!=false){

			$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
				."<tr><td colspan='5' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Repository List</b></td></tr>\n"
				."<tr><td colspan='5' bgcolor='#ffffff' class='cms_small'>".$html_pagelinks."</td></tr>\n"
				."<tr>"
				."<td bgcolor='#dddddd' class='cms_small'>Path</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Exists</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Permission</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Files</td>"
				."<td bgcolor='#dddddd' class='cms_small'>Controls</td>"
				."</tr>\n";

			while($row=@mysql_fetch_array($result)){

				if (file_exists(stripslashes($row["cPath"]))){
					$exists = "Yes";
					// find out permissions folder has
					$permission = substr(sprintf('%o',fileperms(stripslashes($row["cPath"]))), -4);

					// find out how many files are inside it
					$dir = stripslashes($row["cPath"]);
					$dh  = opendir($dir);
					while (false !== ($filename = readdir($dh))) {
						if ($filename!=".." && $filename!="."){
							$dirfiles[] = $filename;
						}
					}
					$files = count($dirfiles);

				} else {
					$permission = "&nbsp;";
					$files = "&nbsp;";
					$exists = "No";
				}

				$html.="<tr>"
					."<td bgcolor='#ffffff' class='cms_small'>".stripslashes($row["cPath"])."</td>"
					."<td bgcolor='#ffffff' class='cms_small'>".$exists."</td>\n"
					."<td bgcolor='#ffffff' class='cms_small'>".$permission."</td>\n"
					."<td bgcolor='#ffffff' class='cms_small'>".$files."</td>\n"
					."<td bgcolor='#ffffff' class='cms_small'>"
					."<a href='admin.php?action=repository_edit&repositoryid=".$row["nRepositoryId"]."' class='cms_button_thin'>Edit</a>"
					."&nbsp;<a href='admin.php?action=repository_del&repositoryid=".$row["nRepositoryId"]."' class='cms_button_thin'>Remove</a>"
					."</td></tr>\n";
			}

			$html .= "<tr><td colspan='5' bgcolor='#ffffff' align='right'><a href='admin.php?action=repository_add' class='cms_button_thin'>Add New Repository</a></td></tr>\n"
				."</table>\n";

		} else {
			$html="<li class='cms_small'>Problem with SQL<br>[".$sql."]</li>\n";
		}
		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}


// Function    : html_repository_add()
// Description : Used in the admin page to show a repository add form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_repository_add(){

	if ($_SESSION["cms_admin"]!=""){

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_contenttypes.png' width='48' height='52' title='CMS Repository Add'></td>\n"
			."<td class='cms_huge'>Add Repository Form</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=repository_add'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Add Repository Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Path</td><td bgcolor='#ffffff'><input type='text' name='path' size='80' class='cms_text'></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Add Repository'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;

	} else {
		header("Location: admin.php");
	}
}

// Function    : html_repository_edit()
// Description : Used in the admin page to show a repository edit form
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_repository_edit(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."Repository WHERE nRepositoryId=".$_GET["repositoryid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$row = mysql_fetch_array($result);
		}

		$html .= "<div style='padding:20px;'>\n";

		$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
			."<td><img src='images/icon_usertypes.png' width='48' height='52' title='CMS Repository Edit'></td>\n"
			."<td class='cms_huge'>Edit Repository</td>\n"
			."</tr></table>\n";

		$html .= "<form method='POST' action='admin_exec.php?action=repository_edit'>\n"
			."<input name='repositoryid' type='hidden' value='".$row["nRepositoryId"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Edit Repository Form</b></td></tr>\n"
			."  <tr><td bgcolor='#dddddd' class='cms_small'>Path</td><td bgcolor='#ffffff'><input type='text' name='path' size='50' class='cms_text' value='".stripslashes($row["cPath"])."'></td></tr>\n"
			."  <tr><td bgcolor='#ffffff' colspan='2' align='right'><input type='submit' class='cms_button' value='Make Changes'></td></tr>\n"
			."</table>\n"
			."</form>\n";

		$html .= "</div>\n";

		return $html;
		
	} else {
		header("Location: admin.php");
	}
}

// Function    : html_repository_del()
// Description : Used in the admin page to show delete confirmation
// Arguments   : None (uses the GET parameters)
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-09-26
function html_repository_del(){

	global $db_tableprefix;

	if ($_SESSION["cms_admin"]!=""){
	
		$con = db_connect();
		$sql = "SELECT * FROM ".$db_tableprefix."Repository WHERE nRepositoryId=".$_GET["repositoryid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){

			$row = mysql_fetch_array($result);

			$html = "<div style='padding:20px;'><div class='cms_huge'>Removal Confirmation</div><form method='POST' action='admin_exec.php?action=repository_del&repositoryid=".$row["nRepositoryId"]."'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>\n"
				."<tr><td bgcolor='#aaaabb' class='cms_small' colspan='2' background='images/grad_bg.gif'><b>Confirm Repository Deletion</b></td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small' colspan='2'>Please confirm that you really want to remove the Repository detailed below. (if not, use your browser's back button).<br>WARNING - You cannot undo this operation.</td></tr>\n"
				."<tr><td bgcolor='#ffffff' class='cms_small'><b>Path</b></td><td bgcolor='#ffffff' class='cms_small'><b>".$row["cPath"]."</b></td></tr>\n"
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


function html_document_search(){

	global $db_tableprefix;
	
	$con = db_connect();
	
	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_document.png' width='48' height='52' title='Document Search'></td>\n"
		."<td class='cms_huge'>Document Search</td>\n"
		."</tr></table>\n";

	// the document add form has two stages - asking for a document type, then asking for the details
	if ($_GET["doctypeid"]!=""){
		
		// show the search form

		// get the properties that need to be filled for the doctype
		$sql = "SELECT * FROM ".$db_tableprefix."DocumentTypeProperties"
			." WHERE nDocumentTypeId=".$_GET["doctypeid"];

		$result = mysql_query($sql,$con);
		if ($result!=false){

		$html .= "<form enctype='multipart/form-data' method='POST' action='admin.php?action=document_search&doctypeid=".$_GET["doctypeid"]."'>\n"
			."<input type='hidden' name='doctypeid' value='".$_GET["doctypeid"]."'>\n"
			."<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."<tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Document Search Form</b></td></tr>\n";

			if (mysql_num_rows($result)>0){

				$html .= "  <tr><td bgcolor='#cccccc' class='cms_small' colspan='2'>Property Fields</td></tr>\n";
				$html .= "  <input type='hidden' name='numrows' value='".mysql_num_rows($result)."'>\n";

				$i=0;
				while($prop_row =@ mysql_fetch_array($result)){
					$i++;
					switch ($prop_row["cDataType"]){
						case "nDataInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataBigInt":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "dDataDate":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "bDataBoolean":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "nDataFloat":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataVarchar":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
						case "cDataMediumText":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><textarea name='prop".$i."_value' cols='80' rows='5' class='cms_text'>".stripslashes($_POST["prop".$i."_value"])."</textarea></td></tr>\n";
							break;
						case "bDataBlob":
							$html .= "  <tr><td bgcolor='#dddddd' class='cms_small'>".$prop_row["cPropertyName"]."</td><td bgcolor='#ffffff'><input type='hidden' name='prop".$i."_propid' value='".$prop_row["nDocumentTypePropertyId"]."'><input type='hidden' name='prop".$i."_datatype' value='".$prop_row["cDataType"]."'><input type='text' name='prop".$i."_value' size='80' value='".stripslashes($_POST["prop".$i."_value"])."' class='cms_text'></td></tr>\n";
							break;
					}

					$html .= "";
				}

			}

			$html .= "<tr><td bgcolor='#ffffff' class='small' align='right' colspan='2'><input type='submit' class='cms_button' value='Perform Search'></td></tr>"
				."</table>\n"
				."</form>\n";

		} else {
			// sql failed retrieving properties

		}
			
		if ($_POST["numrows"]!=""){
			
			// do the search!
			
			// make sense of the arrays first
			for($i=1;$i<=$_POST["numrows"];$i++){
				$propid[$i] = $_POST["prop".$i."_propid"];
				$datatype[$i] = $_POST["prop".$i."_datatype"];
				$value[$i] = $_POST["prop".$i."_value"];
			}
			
			$k=0;
			
			for ($j=1;$j<=$i;$j++){
			
				// for each search term, do a query
				
				if ($value[$j]!=""){
				
					// prepare the value appropriately
					$result = "0";
					switch($datatype[$j]){
						case "nDataBigInt":
							if (!is_numeric($value[$j])){
								$result = "-4";
							}
							break;
						case "dDataDate":
							if (!strtotime($value[$j])!=-1){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "bDataBoolean":
							if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
								switch($value){
									case "T":
										$value[$j] = "1";
										break;
									case "F":
										$value[$j] = "0";
										break;
								}
							} else {
								$result = "-4";
							}
							break;
						case "nDataFloat":
							if (!is_numeric($value[$j])){
								$result = "-4";
							}
							break;
						case "cDataVarchar":
							if (!is_string($value[$j])){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "cDataMediumText":
							if (!is_string($value[$j])){
								$result = "-4";
							} else {
								$value[$j] = "'".$value[$j]."'";
							}
							break;
						case "bDataBlob":
							break;
					}

					$sql = "SELECT DISTINCT doc.nDocumentId FROM ".$db_tableprefix."Document doc"
						." INNER JOIN ".$db_tableprefix."DocumentTypeProperties dtp ON doc.nDocumentTypeId=dtp.nDocumentTypeId"
						." INNER JOIN ".$db_tableprefix."DocumentData dd ON (dtp.nDocumentTypePropertyId=dd.nPropertyId AND dd.nDocumentId=doc.nDocumentId)"
						." WHERE dtp.nDocumentTypePropertyId=".$propid[$j]." AND dd.".$datatype[$j]."=".$value[$j];
					//print "<li>".$sql;
					
					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$k++;
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nDocumentId"];									
							}
							
							if (is_array($a_single_result)){
							
								// intersect the single result into the overall results
								if ($k==1){
									//print "<li>First result : ".implode(",",$a_single_result);
									$a_overall_result = $a_single_result;
								} else {
									//print "<li>Following result : ".implode(",",$a_single_result);
									$a_overall_result = array_intersect($a_overall_result,$a_single_result);
								}
							
							}

						}
					} else {
						$result = "-2";
						break;
					}
				
				}
				
			}
			
			// we now have an array of documentids that we can use
			// stored in a_overall_result
			if (is_array($a_overall_result)){
				
				$result = implode(",",$a_overall_result);
				
				// loop through the array and present the results to the user
				
				// column headings first
				$html .= "<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb'>"
					."<tr><td colspan='6' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>Search Results</b></td></tr>\n"
					."<tr>"
					."<td bgcolor='#bbbbcc' class='cms_small'>ID</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Original Filename</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Filesize (bytes)</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Date Added</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Added By</td>"
					."<td bgcolor='#bbbbcc' class='cms_small'>Controls</td>"
					."</tr>\n";
				
				foreach($a_overall_result as $docid){
					
					// get the document info from the document table
					$sql = "SELECT usr.cUsername,doc.* FROM ".$db_tableprefix."Document doc"
						." LEFT OUTER JOIN ".$db_tableprefix."Users usr ON doc.nAddedBy=usr.nUserId"
						." WHERE nDocumentId=".$docid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result);
							
							$html .= "<tr>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["nDocumentId"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'><a href='document_fetch.php?documentid=".$docid."'>".$row["cOriginalFilename"]."</a></td>"
								."<td bgcolor='#ffffff' class='cms_small'>".number_format($row["nFilesize"])."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["dAdded"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>".$row["cUsername"]."</td>"
								."<td bgcolor='#ffffff' class='cms_small'>&nbsp;</td>"
								."</tr>\n";
								
						}
					}
					
				}
				
				$html .= "</table>\n";
				
			} else {
				$html .= "<div class='cms_small'>No Documents Found</div>";				
			}
			//$html .= "<li>Result : ".$result;
			
		}
		
	} else {

		$html .= "<p class='cms_small'>Before you can search for a document, you need to choose a document type from the list below (this is required because document types dictate the fields that are attached to the document).</p>";
		
		// show a pick list of document types (that the logged in user can add)
		$sql = "SELECT * FROM ".$db_tableprefix."DocumentType ORDER BY cName";
		$result = mysql_query($sql,$con);
		if($result!=false){
			if (mysql_num_rows($result)>0){
				while ($row =@ mysql_fetch_array($result)){
					$html .= "<li class='cms_normal'><a href='admin.php?action=document_search&doctypeid=".$row["nDocumentTypeId"]."'>".stripslashes($row["cName"])."</a></li>\n";
				}
			}
		}
	}

	$html .= "</div>\n";

	return $html;
}


?>