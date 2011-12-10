<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 InteRa srl - David Denicolò <typo3@intera.it>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Class that adds the wizard icon.
 *
 * @author	InteRa srl - David Denicolò <typo3@intera.it>
 */



class tx_interacallforpapers_pi1_wizicon {

	/**
	 * Processing the wizard items array
	 *
	 * @param	array		$wizardItems: The wizard items
	 * @return	Modified array with wizard items
	 */
	function proc($wizardItems)	{
		global $LANG;

		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_interacallforpapers_pi1'] = array(
			'icon'=>t3lib_extMgm::extRelPath('intera_callforpapers').'pi1/ce_wiz.gif',
			'title'=>'Call for Papers',
			'description'=> 'Abstract submission Form to collect abstract papers',
			'params'=>'&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=intera_callforpapers_pi1'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang.xml and returns the \$LOCAL_LANG array found in that file.
	 *
	 * @return	The array with language labels
	 */
	function includeLocalLang()	{
		global $LANG;

		$LOCAL_LANG = $LANG->includeLLFile('EXT:".$extKey."/locallang.xml',FALSE); //I don't know, but it don't work
		return $LOCAL_LANG;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/pi1/class.tx_interacallforpapers_pi1_wizicon.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/pi1/class.tx_interacallforpapers_pi1_wizicon.php']);
}

?>