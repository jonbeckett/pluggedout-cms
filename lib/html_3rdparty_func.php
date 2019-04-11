<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_func.php
#= Version: 0.4.5 (2005-01-31)
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


/*
 * Ofgem function for outputing Folder attributes
 *
 */
function ofgemFolderPage($pageid,$pagetypeid){
    $html = "";
	$db = db_connect();
    
    // get the data
    $compRegNo = getPageProperty($db, $pageid, "compRegNo");
    $prevNames = getPageProperty($db, $pageid, "prevNames");
    
    // only draw anything if we have some data
    if ($compRegNo!="" || $prevNames!=""){
    	
    	// start row containing data (part of page table)
    	$html .= "<tr><td colspan='2'>";
    	
    	// put a table within the row to hold the title
    	$html .= "<table border='0' cellspacing='0' cellpadding='0' width='100%'>"
    		."<tr>"
    		."<td width='8'><img src='uploads/topleft-F98240.gif' width='8' height='4'></td>"
    		."<td bgcolor='#F98240'><img src='uploads/pix1.gif' width='1' height='1'></td>"
    		."<td width='8'><img src='uploads/topright-F98240.gif' width='8' height='4'></td>"
    		."</tr>"
    		."<tr><td colspan='3' bgcolor='#F98240' background='uploads/F98240.gif'>"
    		."<table border='0' cellspacing='0' cellpadding='1'>"
    		."<tr><td width='4' class='orange'><img src='uploads/pix1.gif' width='1' height='1'></td><td class='orange'><b>INFORMATION</b></td></tr>\n"
    		."</table>\n"
    		."</td></tr>\n"
    		."<tr>\n"
    		."<td width='8'><img src='uploads/bottomleft-F98240.gif' width='8' height='4'></td>"
			."<td bgcolor='#F98240'><img src='uploads/pix1.gif' width='1' height='1'></td>"
    		."<td width='8'><img src='uploads/bottomright-F98240.gif' width='8' height='4'></td>"
    		."</tr>\n"
    		."</table>\n";
    	
    	$html .= "<table border='0' cellspacing='0' cellpadding='0'>\n"
			."<tr><td><img src='uploads/pix1.gif' width='3' height='3'></td></tr>\n"
			."</table>\n";
    	
    	// surround the data with the light blue background table
		$html .= "<table border='0' cellspacing='0' cellpadding='0' width='100%'>\n"
			."<tr>\n"
			."<td width='8'><img src='uploads/topleft-E5F2FB.gif' width='8' height='4'></td>\n"
			."<td bgcolor='#E5F2FB'><img src='uploads/pix1.gif' width='1' height='1'></td>\n"
			."<td width='8'><img src='uploads/topright-E5F2FB.gif' width='8' height='4'></td>\n"
			."</tr>\n"
			."<tr><td colspan='3' bgcolor='#E5F2FB'>\n"
			."<table border='0' cellspacing='0' cellpadding='5' width='100%'><tr><td>\n";
    	
    	// start another table to hold the data
    	$html .= "<table border='0' cellspacing='0' cellpadding='3'>";
    	
    	if ( $compRegNo <> "" ) {
			$html .= "<tr><td><b>Company Registration Number</b></td><td><b>".$compRegNo."</b></td></tr>\n";	
    	}
    
    	if ( $prevNames <> "" ) {
    	
    	    $prevNamesArray = splitMultiValueField($prevNames);
			$html .= "<tr><td valign='top'><b>Previous Names</b></td><td><b>";
			
    	    for ( $idx=0; $idx < count($prevNamesArray); $idx++) {
				$html .= $prevNamesArray[$idx]."<br>";
    	    }
			
			$html .= "</b></td>"
    	            ."</tr>";
    	}
    	
    	// end the table holding the data
    	$html .= "</table>\n";
    	
    	// end the surrounding background table
		$html .= "</td></tr></table>\n"
			."</td></tr>\n"
			."<tr>\n"
			."<td width='8'><img src='uploads/bottomleft-E5F2FB.gif' width='8' height='4'></td>\n"
			."<td bgcolor='#E5F2FB'><img src='uploads/pix1.gif' width='1' height='1'></td>\n"
			."<td width='8'><img src='uploads/bottomright-E5F2FB.gif' width='8' height='4'></td>\n"
			."</tr>\n"
			."</table>\n";
    	
    	// end row containing data (part of page table)
    	$html .= "</td></tr>\n";
    
    }
    
	return $html;
}

/*
 * Ofgem function for outputing Document attributes
 *
 */
function ofgemDocumentPage($pageid,$pagetypeid){
    $html = "";
	$db = db_connect();
    $createDate = getPageProperty($db, $pageid, "createDate");
    $modifyDate = getPageProperty($db, $pageid, "modifyDate");
    $version = getPageProperty($db, $pageid, "version");
    $lName = getPageProperty($db, $pageid, "lName");
    $lClass = getPageProperty($db, $pageid, "lClass");
    $lType = getPageProperty($db, $pageid, "lType");
    $dType = getPageProperty($db, $pageid, "dType");
    $pOfficier = getPageProperty($db, $pageid, "pOfficier");
    $regFileRef = getPageProperty($db, $pageid, "regFileRef");
    $prevWork = getPageProperty($db, $pageid, "prevWork");
    
    $html .= "<tr><td>Create Date</td><td>&nbsp;</td><td>".$createDate."</td></tr>\n"
    	."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Modified Date</td><td>&nbsp;</td><td>".$modifyDate."</td></tr>\n"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Document Version Number</td><td>&nbsp;</td><td>".$version."</td></tr>\n"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Licencee Name</td><td>&nbsp;</td><td>".$lName."</td></tr>"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Licence Class</td><td>&nbsp;</td><td>".$lClass."</td></tr>"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Licence Type</td><td>&nbsp;</td><td>".$lType."</td></tr>"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Policy Officier</td><td>&nbsp;</td><td>".$pOfficier."</td></tr>"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n"
		."<tr><td>Registray File Reference</td><td>&nbsp;</td><td>".$regFileRef."</td></tr>"
		."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td bgcolor='#cccccc'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n";
	
	// start previous work section
	$html .= "<tr><td valign='top'>Previous Work</td><td>&nbsp;</td>";
	
    if ($prevWork!="") {
        
        $html .= "<td>"
        	."<table width='100%' border='0' cellspacing='0' cellpadding='2'>"
        	."<tr><td><b>Ofgem Document Reference</b></td><td><b>Title</b></td><td><b>Web Site Reference</b></td></tr>"
        	."<tr><td colspan='3'><table width='100%' border='0' cellspacing='0' cellpadding='0' bgcolor='#5D5D5D'><tr><td><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td></tr>\n";
        	
        $prevWorkArray = extractSet($prevWork);
        if (count($prevWorkArray)>1){
        
			for ( $idx=0; $idx < count($prevWorkArray); $idx++) {
				if ($prevWorkArray[$idx]["Ofgem Document Ref"]!=""){
					$html .= "<tr>"
						."<td>".$prevWorkArray[$idx]["Ofgem Document Ref"]."</td>"
						."<td>".$prevWorkArray[$idx]["Title"]."</td>"
						."<td>".$prevWorkArray[$idx]["Location on Web Site"]."</td>"
						."</tr>";
				}
			}
			
		} else {
			
			$html .= "<tr><td colspan='3' align='center'>No Previous Work References</td></tr>\n";
			
		}
			
		$html .= "</table>"
			."</td>";
    } else {
    	$html .= "<td>&nbsp;</td>\n";
    }
    // end previous work section
    $html .= "</tr>\n";
    
	return $html;
}

/*
 * CMS function extracting data from page property
 *
 */
function getPageProperty($db, $pageid, $propName) {
    $data = "";
    $sqlquery = "SELECT  pd.*, ptp.cPropertyName, ptp.cDataType "
            ."FROM  cms_PageData  pd "
            ."INNER JOIN cms_PageTypeProperties ptp "
            ."ON pd.nPropertyId = ptp.nPageTypePropertyId "
            ."WHERE  nPageId  = " .$pageid
            ." AND cPropertyName = '".$propName."'";

    $result = mysql_query($sqlquery,$db);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			$protRow = mysql_fetch_array($result);
			$data = $protRow[$protRow["cDataType"]];
		}
    }
	return $data;
}

/*
 * CMSLivelink function for extracting multivalue datafields from a
 * property value.
 *
 */
function splitMultiValueField($value) {
    return explode("\r\r", $value);
}

/*
 * CMSLivelink function for extracting set data fields from a 
 * property value.
 *
 */
function extractSet($value) {
    $resultSet = array();
    $recSet = explode("\r\r", $value);
    for ( $recIdx = 0; $recIdx < count($recSet); $recIdx++ ) {
        $recItems = explode("\r", $recSet[$recIdx]);
        $resultSet[$recIdx] = array();
        for ( $itemIdx = 0; $itemIdx < count($recItems); $itemIdx++) {
            $item = explode("=", $recItems[$itemIdx]);
            $resultSet[$recIdx][$item[0]] = $item[1];
        }
    }
    return $resultSet;
}



function makeParentLink($pageid,$pagetypeid){
	$db = db_connect();
    $parentPk = getPageProperty($db, $pageid, "parentPk");
    if ( $parentPk <> "" ) {
    	$parentName = getPageProperty($db, $pageid, "parentName");
		$html = "<a href = 'index.php?pk=".$parentPk."' title='Return to Previous Folder (".$parentName.")'><b>Return to Previous Folder</b><br>&nbsp;&nbsp;&raquo;&nbsp;".$parentName."</a>";
    } else {
    	$html = "";
    }
    return $html;
}

function docListHeader($pageid,$pagetypeid){
	// this function is designed to find out if the page has any
	// content in element 4 - if so, return some data, else return nothing
	
	$con = db_connect();
	$sql = "SELECT nPageContentId FROM cms_PageContent WHERE nPageId=".$pageid." AND nTemplateElementId=4";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			
			// HTML for the header of the document list
			$html = "<table border='0' cellspacing='0' cellpadding='0' width='100%'>"
				."<tr>"
				."<td width='8'><img src='uploads/topleft-F98240.gif' width='8' height='4'></td>"
				."<td bgcolor='#F98240'><img src='uploads/pix1.gif' width='1' height='1'></td>"
				."<td width='8'><img src='uploads/topright-F98240.gif' width='8' height='4'></td>"
				."</tr>"
				."<tr><td colspan='3' bgcolor='#F98240' background='uploads/F98240.gif'>"
				."<table border='0' cellspacing='0' cellpadding='1'>"
				."<tr><td width='4' class='orange'><img src='uploads/pix1.gif' width='1' height='1'></td><td class='orange'><b>DOCUMENTS</b></td></tr>\n"
				."</table>\n"
				."</td></tr>\n"
				."<tr>\n"
				."<td width='8'><img src='uploads/bottomleft-F98240.gif' width='8' height='4'></td>"
				."<td bgcolor='#F98240'><img src='uploads/pix1.gif' width='1' height='1'></td>"
				."<td width='8'><img src='uploads/bottomright-F98240.gif' width='8' height='4'></td>"
				."</tr>\n"
    			."</table>\n";
    			
		} else {
			$html = "";
		}
	} else {
		print $sql;
	}
	//print mysql_num_rows($result);
	return $html;
}


function docListColumnHeadings($pageid,$pagetypeid){
	// this function is designed to find out if the page has any
	// content in element 4 - if so, return some data, else return nothing
	
	$con = db_connect();
	$sql = "SELECT nPageContentId FROM cms_PageContent WHERE nPageId=".$pageid." AND nTemplateElementId=4";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			$html = "<tr>\n"
				."<td colspan='2'><b>Document</b></td>\n"
				."<td><b>Download</b></td>\n"
				."<td><b>Created</b></td>\n"
				."<td><b>Modified</b></td>\n"
				."</tr>\n"
				."<tr>\n"
				."<td colspan='5'><table border='0' cellspacing='0' cellpadding='0' width='100%'><tr><td bgcolor='#5D5D5D'><img src='uploads/pix1.gif' width='1' height='1'></td></tr></table></td>\n"
				."</tr>\n";
		} else {
			$html = "";
		}
	} else {
		print $sql;
	}
	//print mysql_num_rows($result);
	return $html;
}

function docListTableStart($pageid,$pagetypeid){
	$con = db_connect();
	$sql = "SELECT nPageContentId FROM cms_PageContent WHERE nPageId=".$pageid." AND nTemplateElementId=4";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
		
			$html = "<table border='0' cellspacing='0' cellpadding='0' width='100%'>\n"
				."<tr>\n"
				."<td width='8'><img src='uploads/topleft-E5F2FB.gif' width='8' height='4'></td>\n"
				."<td bgcolor='#E5F2FB'><img src='uploads/pix1.gif' width='1' height='1'></td>\n"
				."<td width='8'><img src='uploads/topright-E5F2FB.gif' width='8' height='4'></td>\n"
				."</tr>\n"
				."<tr><td colspan='3' bgcolor='#E5F2FB'>\n"
				."<table border='0' cellspacing='0' cellpadding='5' width='100%'><tr><td>\n";
				
		} else {
			$html = "";
		}
	} else {
		print $sql;
	}
	//print mysql_num_rows($result);
	return $html;
}

function docListTableEnd($pageid,$pagetypeid){
	$con = db_connect();
	$sql = "SELECT nPageContentId FROM cms_PageContent WHERE nPageId=".$pageid." AND nTemplateElementId=4";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
		
			$html = "</td></tr></table>\n"
				."</td></tr>\n"
				."<tr>\n"
				."<td width='8'><img src='uploads/bottomleft-E5F2FB.gif' width='8' height='4'></td>\n"
				."<td bgcolor='#E5F2FB'><img src='uploads/pix1.gif' width='1' height='1'></td>\n"
				."<td width='8'><img src='uploads/bottomright-E5F2FB.gif' width='8' height='4'></td>\n"
				."</tr>\n"
				."</table>\n";
				
		} else {
			$html = "";
		}
	} else {
		print $sql;
	}
	//print mysql_num_rows($result);
	return $html;
}

function makePagePath($pageid,$pagetypeid){

	// path is a string in the format
	// name \r id \r\r name \r id \r\r

	$db = db_connect();
    $path = getPageProperty($db, $pageid, "path");
    if ( $path <> "" ) {
    
    	$a_path = explode("\r\r",$path);
    	foreach($a_path AS $folder){
    		$a_folder = explode("\r",$folder);
    		
    		$var1 = explode("=",$a_folder[0]);
    		$var2 = explode("=",$a_folder[1]);
    		
    		$pk = $var1[1];
    		$name = $var2[1];
    		$uname = strtoupper($var2[1]);
    		
    		if ($html!=""){
    			$html.= "&gt;&nbsp;";
    		}
    		$html .= "<a href='index.php?pk=".$pk."' title='Go to ".$name." page'>".$uname."</a>&nbsp;";
    	}
    	
    } else {
    	$html = "";
    }
    return $html;
}


function checkNoFurtherFolders($pageid,$pagetypeid){
	// this function is designed to find out if the page has any
	// content in element 3 - if NOT, return some data, else return nothing
	
	$con = db_connect();
	$sql = "SELECT nPageContentId FROM cms_PageContent WHERE nPageId=".$pageid." AND nTemplateElementId=3";
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)==0){
			
			// HTML for the header of the document list
			$html = "<tr><td>There are no further folders below this level.</td></tr>";
    			
		} else {
			$html = "";
		}
	} else {
		print $sql;
	}
	//print mysql_num_rows($result);
	return $html;
}

function searchForm(){

	// output the form
	$html = "<form method='POST' action='index.php?pk=search'>\n"
		."<table border='0' cellspacing='0' cellpadding='2'>\n"
		."<tr>"
		."<td>Keywords</td>"
		."<td><input type='text' class='text' name='keywords' value='".$_POST["keywords"]."' size='17'></td>"
		."</tr>"
		."<tr>"
		."<td colspan='2' align='right'><input class='text' type='submit' value='Search'></td>"
		."</tr>\n"
		."</table>\n"
		."</form>\n";
		
	return $html;
	
}


function searchResults(){

	

}

?>