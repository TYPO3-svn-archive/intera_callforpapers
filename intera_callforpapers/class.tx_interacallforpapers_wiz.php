<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Richard Piacentini (ricp@nuxos.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Random Password generator wizard
 *
 * @author Richard Piacentini <ricp@nuxos.com>
 * @package TYPO3
 * @subpackage tx_nuxosrandompassword
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_nuxosrandompassword_wiz
 *   58:     function includeLocalLang()
 *   70:     function main($PA, $pObj)
 *  344:     function getPasswordJS($passwordArray, $varName, $varImgName, $varImgTitle)
 *  183:     function makePasswords($seedArray, $count = 1, $length = 6, $strength = 4, $prefix = '')
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_interacallforpapers_wiz {

	var $extKey = 'intera_callforpapers'; // The extension key.

	/**
	 * Main function for TCEforms wizard.
	 *
	 * @param	array		Parameter array for "userFunc" wizard type
	 * @param	object		Parent object
	 * @return	string		Returns HTML for the wizard.
	 */
	function main() {

		//echo $content.="GET:".t3lib_div::view_array($_GET)."<br />"."POST:".t3lib_div::view_array($_POST)."<br />"."BE_USER:".t3lib_div::view_array($GLOBALS['BE_USER']->user)."<br />";
	
		//echo $content.="GET:".t3lib_div::view_array($_GET);
		
		//list($edit_uid,$fooo) = $_GET['edit']['tx_interacallforpapers_abstract'];
		
		//echo $content.="<br>Dav-GET:".t3lib_div::view_array($_GET['edit']['tx_interacallforpapers_abstract']);
		
		//echo $content.="<br>Dav-GET:".$edit_uid;
		//var_dump($_GET['edit']['tx_interacallforpapers_abstract']);
		
		$edit_uid = key($_GET['edit']['tx_interacallforpapers_abstract']);
	
		$onclick = 'window.open(\''.t3lib_extMgm::extRelPath($this->extKey).'mod1/index.php?theCMD=popup&theUID='.$edit_uid.'\',\'_blank\',\'width=360,height=450 ,toolbar=no, location=no,status=yes,menubar=no,scrollbars=auto,resizable=yes\')';
//		$onclick = 'window.open(\''.t3lib_extMgm::extRelPath($this->extKey).'mod1/index.php?theCMD=popup&theUID='.$edit_uid.'\',\'_blank\')';

		$output .= '<a href="#" onclick="'.$onclick.'">'. '<img src="'.t3lib_extMgm::extRelPath($this->extKey).'evaluate.gif" width="137" height="23" hspace="8" class="absmiddle" /></a>';

		return $output;
	}

} // end class

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/class.tx_interacallforpapers_wiz.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/class.tx_interacallforpapers_wiz.php']);
}
?>
