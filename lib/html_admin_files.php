<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : html_admin_files.php
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

// Function    : html_upload_list()
// Description : Used in the admin page to show a list of files in the upload folder
// Arguments   : None
// Returns     : html data
// Author      : Jonathan Beckett
// Last Change : 2004-07-29
function html_upload_list(){

	global $db_tableprefix;

	$html="<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' align='center'>\n"
		."  <tr><td bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'>Upload File</td></tr>\n"
		."  <tr><td bgcolor='#ffffff'>\n"
		."    <form enctype='multipart/form-data' action='admin_exec.php?action=upload_file' method='post'>\n"
		."    <input type='hidden' name='MAX_FILE_SIZE' value='1048576'>\n"
		."    <input name='userfile' type='file'>\n"
		."    <input class='cms_button' type='submit' value='Send File'>\n"
		."    </form>\n"
		."  </td></tr>\n"
		."</table>\n";

	// List the Uploads
	if ($handle = opendir('uploads')) {
		$html.="<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aaaabb' align='center'>\n"
			."  <tr><td colspan='2' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'>Uploaded Files</td></tr>\n";
		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($handle))) {
			if ($file!="." && $file!=".."){
				$html.="<tr>"
					."<td bgcolor='#ffffff' class='cms_small'><a href='uploads/".$file."' class='cms_link'>".$file."</a></td>"
					."<td bgcolor='#ffffff'><a href='admin.php?action=upload_delete&filename=".$file."' class='cms_button_thin'>Remove</a></td>"
					."</tr>\n";
			}
		}
		$html.="</table>\n";

		closedir($handle);
	} else {
		// cannot find uploads directory
	}
	return $html;
}


function html_filebrowse(){

	global $site_root;

	$html .= "<div style='padding:20px;'>\n";

	$html .= "<table border='0' cellspacing='0' cellpadding='5'><tr>\n"
		."<td><img src='images/icon_uploads.png' width='48' height='52' title='CMS File Browser'></td>\n"
		."<td class='cms_huge'>Browse Files</td>\n"
		."</tr></table>\n";

	// establish directory we want to show in format /var/html/uploads
	if ($_GET["path"]!=""){
		$path = $_GET["path"];
	} else {
		$path = $site_root."/uploads";
	}

	// clear the file state cache
	clearstatcache();

	if ($handle = opendir($path)) {

		$path = realpath($path);

		// Create path in the format http://sitename/filename
		$shortpath = ".".str_replace($site_root,"",$path);
		$i=0;
		while (($file = readdir($handle))!==false) {
			if (is_dir($path."/".$file)){
				// directory
				// exclude path back out of site root
				if ($path==$site_root && $file==".."){
					// exclude
				} else {
					$i++;
					$directories[$i] = $file;
				}
			} else {
				// file
				if ($file!="." && $file!=".."){
					$j++;
					$files[$j] = $file;
				} else {
					// for some reason '..' is detected as a file in safe mode
					if ($path==$site_root && $file==".."){
						// exclude
					} else {
						$i++;
						$directories[$i] = $file;
					}					
				}
			}
		}
		//closedir($handle);

		// sort the arrays
		if (count($directories)>0){
			sort($directories);
		}
		if (count($files)>0){
			sort($files);
		}

		// output the list of directories, then the list of files

		$html .= "<table border='0' cellspacing='1' cellpadding='3' bgcolor='#aaaabb'>\n"
			."  <tr><td colspan='5' bgcolor='#aaaabb' class='cms_small' background='images/grad_bg.gif'><b>File Browse</b></td></tr>\n"
			."  <tr><td colspan='5' bgcolor='#ffffff' class='cms_small'>Path : ".$path."</td></tr>\n"
			."  <tr><td colspan='5' bgcolor='#ffffff' class='cms_small'>Create Dir Here : "
			."    <form action='admin_exec.php?action=filebrowse_createdir' method='POST'>\n"
			."    <input name='path' type='hidden' value='".$path."'>\n"
			."    <input name='shortpath' type='hidden' value='".$shortpath."'>\n"
			."    <input name='directory' type='text'>\n"
			."    <input class='cms_button' type='submit' value='Create'>\n"
			."    </form>\n"
			."  </td></tr>\n";

		$html .= "  <tr><td colspan='5' bgcolor='#eeeeee' class='cms_small' background='images/grad_bg.gif'><b>Directories</b></td></tr>\n";
		for ($i=0;$i<count($directories);$i++){

			// determine controls
			if ($directories[$i]!="." && $directories[$i]!=".."){
				$controls = "<a href='admin_exec.php?action=filebrowse_delete_directory&directory=".$path."/".$directories[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
			} else {
				$controls = "";
			}
			$html.="<tr>"
				."<td bgcolor='#ffffff' width='16'><img src='images/file_icon_folder.png' width='16' height='16'></td>"
				."<td bgcolor='#ffffff' class='cms_small' colspan='3'><a href='admin.php?action=filebrowse&path=".$path."/".$directories[$i]."' class='cms_link'>".$directories[$i]."</a></td>"
				."<td bgcolor='#ffffff'>".$controls."</td>"
				."</tr>\n";
		}

		// work out destination for uploads
		$destination = $path;

		$html .= "  <tr><td colspan='5' bgcolor='#eeeeee' class='cms_small' background='images/grad_bg.gif'><b>Files</b></td></tr>\n"
			."<tr>"
			."<td bgcolor='#ffffff' class='cms_small' colspan='5'>Upload Here"
			."    <form enctype='multipart/form-data' action='admin_exec.php?action=file_upload&destination=".$destination."' method='POST'>\n"
			."    <input type='hidden' name='MAX_FILE_SIZE' value='8388608'>\n"
			."    <input name='userfile' type='file'>\n"
			."    <input class='cms_button' type='submit' value='Send File'>\n"
			."    </form>\n"
			."</td>"
			."</tr>\n"
			."<td width='16' bgcolor='#eeeeee' class='cms_small'>&nbsp;</td>"
			."<td bgcolor='#eeeeee' class='cms_small'>Filename</td>"
			."<td bgcolor='#eeeeee' class='cms_small'>Size (Bytes)</td>"
			."<td bgcolor='#eeeeee' class='cms_small'>Size (w x h)</td>"
			."<td bgcolor='#eeeeee' class='cms_small'>Controls</td>"
			."</tr>\n";

		for ($i=0;$i<count($files);$i++){
			// figure out which icon to use
			$controls = "";
			$icon = "";
			switch (strtolower(substr($files[$i],strlen($files[$i])-3,3))){
				case "php":
					$icon = "images/file_icon_script.png";
					$controls = "&nbsp;";
					break;
				case "fnc":
					$icon = "images/file_icon_script.png";
					$controls = "&nbsp;";
					break;
				case "css":
					$icon = "images/file_icon_script.png";
					$controls = "&nbsp;";
					break;
				case ".js":
					$icon = "images/file_icon_script.png";
					$controls = "&nbsp;";
					break;
				case "gif":
					$icon = "images/file_icon_image.png";
					$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
					break;
				case "jpg":
					$icon = "images/file_icon_image.png";
					$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
					break;
				case "png":
					$icon = "images/file_icon_image.png";
					$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
					break;
				case "inc":
					$icon = "images/file_icon_config.png";
					$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
					break;
				case "else":
					$icon = "images/file_icon_script.png";
					$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
			}
			if ($icon==""){
				$icon = "images/file_icon_script.png";
				$controls .= "<a href='admin_exec.php?action=filebrowse_delete_file&file=".$path."/".$files[$i]."&path=".$path."' class='cms_button_thin'>Remove</a>";
			}
			// prepare filename (anchor or not)
			if (substr($files[$i],strlen($files[$i])-3,3)=="php" || substr($files[$i],strlen($files[$i])-2,2)=="js"){
				$filename = $files[$i];
			} else {
				$filename = "<a href='".$shortpath."/".$files[$i]."' class='cms_link'>".$files[$i]."</a>";
			}

			// prepare size if its an image
			if ($files[$i]!="." && $files[$i]!=".."){
				$asize =@ getimagesize($shortpath."/".$files[$i]);
			} else {
				$asize = false;
			}
			if ($asize!=false){
				$size = $asize[0]." x ".$asize[1];
			} else {
				$size = "&nbsp;";
			}

			$html.="<tr>"
				."<td bgcolor='#ffffff' width='16'><img src='".$icon."' width='16' height='16'></td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$filename."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".number_format(filesize($shortpath."/".$files[$i]))."</td>"
				."<td bgcolor='#ffffff' class='cms_small'>".$size."</td>"
				."<td bgcolor='#ffffff'>".$controls."</td>"
				."</tr>\n";
		}

		$html.="</table>\n";

		closedir($handle);
	} else {
		// cannot find uploads directory
	}
	return $html;

}

?>