<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 InteRa srl - David Denicol� <typo3@intera.it>
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
 * Plugin 'Call for Papers' for the 'intera_callforpapers' extension.
 *
 * @author	InteRa srl - David Denicolò <typo3@intera.it>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_interacallforpapers_pi1 extends tslib_pibase {
	var $prefixId = 'tx_interacallforpapers_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_interacallforpapers_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'intera_callforpapers';	// The extension key.
	var $pi_checkCHash = TRUE;
	var $abstractRoot;
	/**
	 * Main method of your PlugIn
	 *
	 * @param	string		$content: The content of the PlugIn
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	The content that should be displayed on the website
	 */
	function main($content,$conf)	{
	
		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
		$this->init($conf);
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		$this->abstractRoot = $this->extConf['beCatRoot'];

		//$content = (is_array($this->formData[tx_interacallforpapers][DATA]))?t3lib_div::view_array($this->formData):'';
				
		$content .= (is_array($this->errorFormDataArr))?$this->errMsg:'';
				
		$content .= $this->submitView($content,$conf);
				
		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * read the template file, fill in global wraps and markers and write the result
	 * to '$this->templateCode'
	 *
	 * @return	void
	 */
	function checkFormData() {
		//$this->formData;
		$err_msg = '';
		if (is_array($this->formData[tx_interacallforpapers][DATA])){
			foreach($this->formData[tx_interacallforpapers][DATA] as $key => $value){
				switch( (string)$key ){
					case 'experience':
					case 'technical-contribution':
					case 'economic-contribution':
					case 'innovation':
					case 'place-date-of-publication':
					//case 'experience':
					case 'tel':
					case 'fax':
					case 'co-authors':
						//no check these fields
					break;
					case 'email':
						if (!eregi("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $value)) {
							$this->errorFormDataArr[] = (string)$key;
							$err_msg .= '<div class='.$this->pi_getClassName('err').'>'.$this->pi_getLL('err_'.(string)$key).'</div>';
						}
					break;
					case 'chr1':
						if (intval($value) > 0 AND (intval($value) < 200 OR intval($value) > 400)) {
							$this->errorFormDataArr[] = (string)$key;
							$err_msg .= '<div class='.$this->pi_getClassName('err').'>'.$this->pi_getLL('err_'.(string)$key).'</div>';
						}
					break;
					case 'chr2':
					case 'chr3':
						if (intval($value) > 80) {
							$this->errorFormDataArr[] = (string)$key;
							$err_msg .= '<div class='.$this->pi_getClassName('err').'>'.$this->pi_getLL('err_'.(string)$key).'</div>';
						}
					break;
					case 'primary-category':
						list($p_cat, $s_cat) = explode(".", $value);
						if (!(intval($p_cat) > 0)){
							$this->errorFormDataArr[] = (string)$key;
							$err_msg .= '<div class='.$this->pi_getClassName('err').'>'.$this->pi_getLL('err_cat').'</div>';
						}
					break;
					default:
						if (!strcmp($value,'')) {
							$this->errorFormDataArr[] = (string)$key;
							$err_msg .= '<div class='.$this->pi_getClassName('err').'>'.sprintf($this->pi_getLL('err_generic'), (string)$key).'</div>';
						}
					break;
				}//end switch
			}//end foreach
		}//end if
		return $err_msg;
	}
	/**
	 * Init Function: here all the needed configuration values are stored in class variables..
	 *
	 * @param	array		$conf : configuration array from TS
	 * @return	void
	 */
	function init($conf) {
		$this->conf = $conf; //store configuration
		$this->pi_loadLL(); // Loading language-labels
		$this->pi_setPiVarDefaults(); // Set default piVars from TS
		
		
		
		$this->errorFormDataArr = array();
		
		$this->formData = t3lib_div::_POST();
		if ($this->formData){
			$this->errMsg = $this->checkFormData();
		}
		//echo t3lib_div::view_array($this->errorFormDataArr);
		//$this->piVars = $this->formData;

		$this->initTemplate();

		// Configure caching
		$this->allowCaching = $this->conf['allowCaching']?1:0;
		if (!$this->allowCaching) {
			$GLOBALS['TSFE']->set_no_cache();
		}
	}

	/**
	 * read the template file, fill in global wraps and markers and write the result
	 * to '$this->templateCode'
	 *
	 * @return	void
	 */
	function initTemplate() {
		// read template-file and fill and substitute the Global Markers
		//$templateflex_file = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'template_file', 's_template');
		$this->templateCode = $this->cObj->fileResource($this->conf['templateFile']);
		//$this->templateFile =   //'/opt/lampp/htdocs/omc/typo3conf/ext/intera_callforpapers/pi1/papers_template.html';
		//$this->templateCode = $this->templateFile;// $this->cObj->fileResource($templateflex_file?'uploads/tx_ttnews/' . $templateflex_file:$this->conf['templateFile']);
		//$splitMark = md5(microtime());
		//$this->templateCode = $this->cObj->substituteMarkerArray($this->templateCode, $globalMarkerArray);
	}
	
	/**
	 * Shows the submit form
	 *
	 * @param	string		$content: content of the PlugIn
	 * @param	array		$conf: PlugIn Configuration
	 * @return	HTML list of table entries
	 */
	function submitView(){
		if (is_array($this->errorFormDataArr) AND count($this->errorFormDataArr)>0){
			$templateName = '###TEMPLATE_SUBMITTED_WRONG###';
			$tmpl = $this->cObj->getSubpart($this->templateCode, $templateName);

			if (is_array($this->formData[tx_interacallforpapers][DATA])){
				foreach($this->formData[tx_interacallforpapers][DATA] as $key => $value){
					$tmpl = $this->cObj->substituteMarker($tmpl, '###'.(string)$key.'###', $value);
				}//end foreach
			}
			
			$tmpl = $this->cObj->substituteMarker($tmpl, '###CATEGORIES_SCRIPT###', $this->cat_script());
			
		}else{
			if (is_array($this->formData[tx_interacallforpapers][DATA])){
			
				$tstamp = mktime(); 
				$this->formData[tx_interacallforpapers][DATA]['tstamp'] = $tstamp ;
				$this->formData[tx_interacallforpapers][DATA]['crdate'] = $tstamp ;
				$this->formData[tx_interacallforpapers][DATA]['protocolnumber'] = date("Y", $tstamp).'/'.$tstamp;
				
				//insert data in the db
				$this->insertDB();
				//include(t3lib_extMgm::extPath('intera_callforpapers').'categories.php');
				
				$topLevel = $GLOBALS["TSFE"]->sys_page->getMenu($this->abstractRoot);

				$category=Array();
				$category[0] = ''; 
				$combo{intval(0)}=Array();
				$combo{intval(0)}[0] = ''; 
		
				foreach($topLevel as $page) {
		
				//$content .= '['.$page["uid"].']'.$page["title"].'<br>';
		
					$category[$page["uid"]] = $page["title"];
					//$combo{$page["uid"]}=Array();
					//$combo{$page["uid"]}[0] = 'Select the subject secondary category'; //0.0
	
					$subLevel = $GLOBALS["TSFE"]->sys_page->getMenu($page["uid"]);
	
					//echo t3lib_div::view_array( $subLevel );
					if (is_array($subLevel) AND !empty($subLevel)){
						foreach($subLevel as $subpage) {
							//$content .= '&nbsp;&nbsp;&nbsp;['.$subpage["uid"].']'.$subpage["title"].'<br>';
							$combo{intval($page["uid"])}[intval($subpage["uid"])] = $subpage["title"];
						}
					// echo t3lib_div::view_array( $combo{$page["uid"]} );
					}
				}
				
				//compose email message
				$msg = array();
				$msg[] = $this->pi_getLL("subj");				
				$msg[] = $this->pi_getLL('email_salutation');
				
				foreach($this->formData[tx_interacallforpapers][DATA] as $key => $value){
					//$msg[] = (eregi('chr',(string)$key))?'words: '. $value : (string)$key .': '. $value;
					//$msg[] = '';
					
					
					
					switch( (string)$key ){
					case 'chr1':
					case 'chr2':
					case 'chr3':
						$msg[] = 'parole: '. $value;
					break;
					case 'primary-category':
						list($p_cat, $s_cat) = explode(".", $value);
						$msg[] = '';
						$msg[] = 'categoria principale' .': '. $category[$p_cat];
						$msg[] = '';
					break;	
					case 'tstamp':
					case 'crdate':
						//no check these fields
					break;
					case 'secondary-category':
						list($p_cat, $s_cat) = explode(".", $value);
						$msg[] = '';
						$msg[] = 'primary-category' .': '. $category[$p_cat];
						$msg[] = '';
						//$comboKarr = "combo{$p_cat}";
						//echo t3lib_div::view_array($$comboKarr);
						//echo $s_cat;
						//echo ${$comboKarr}[$s_cat];//
						$msg[] = 'secondary-category' .': '. $combo{intval($p_cat)}[intval($s_cat)];
					break;
					default:
						$msg[] = '';
						$msg[] = $this->pi_getLL('listFieldHeader_'.(string)$key,(string)$key) .': '. $value;
					break;
					}//end switch
				}
				
				//echo $pippo = implode("\n",$msg);
				
				
				$this->cObj->sendNotifyEmail(implode("\n",$msg), $this->formData[tx_interacallforpapers][DATA][email], "",$this->conf["fromAddr"],$this->conf["fromName"]);
				$this->cObj->sendNotifyEmail(implode("\n",$msg), $this->conf["fromAddr"], "",$this->conf["fromAddr"],$this->conf["fromName"]);
				
				//$this->cObj->sendNotifyEmail(implode("\n",$msg), $this->formData[tx_interacallforpapers][DATA][email], "",$this->conf["fromAddr"],$this->conf["fromName"]);
				
				$templateName = '###TEMPLATE_SUBMITTED_GOOD###';
				$tmpl = $this->cObj->getSubpart($this->templateCode, $templateName);
			}else{
				$templateName = '###TEMPLATE_SUBMIT###';
				$tmpl = $this->cObj->getSubpart($this->templateCode, $templateName);	
				$tmpl = $this->cObj->substituteMarker($tmpl, '###CATEGORIES_SCRIPT###', $this->cat_script());
			}
		}
		
		$formURL = $this->pi_linkTP_keepPIvars_url(array('pointer' => null, 'cat' => null), 0, 1) ;
		$tmpl = $this->cObj->substituteMarker($tmpl, '###FORM_URL###', $formURL);
		
		return $tmpl;
	}
	
	function cat_script(){
	
	//echo t3lib_extMgm::extPath('intera_callforpapers').'categories.php';
		//include('typo3conf/ext/intera_callforpapers/'.'categories.php');
		//include(t3lib_extMgm::extPath('intera_callforpapers').'categories.php');
		//include(t3lib_extMgm::extPath('intera_callforpapers').'categories.php');
		
		// ----
		// categories David

		$topLevel = $GLOBALS["TSFE"]->sys_page->getMenu($this->abstractRoot);

		$category=Array();
		$category[0] = ''; 
		$combo{intval(0)}=Array();
		$combo{intval(0)}[0] = ''; 

		foreach($topLevel as $page) {

		//$content .= '['.$page["uid"].']'.$page["title"].'<br>';

				$category[$page["uid"]] = $page["title"];
				//$combo{$page["uid"]}=Array();
				//$combo{$page["uid"]}[0] = 'Select the subject secondary category'; //0.0

				$subLevel = $GLOBALS["TSFE"]->sys_page->getMenu($page["uid"]);

				//echo t3lib_div::view_array( $subLevel );
				if (is_array($subLevel) AND !empty($subLevel)){
					foreach($subLevel as $subpage) {
						//$content .= '&nbsp;&nbsp;&nbsp;['.$subpage["uid"].']'.$subpage["title"].'<br>';
						$combo{intval($page["uid"])}[intval($subpage["uid"])] = $subpage["title"];
					}
				// echo t3lib_div::view_array( $combo{$page["uid"]} );
				}
		}

		//echo t3lib_div::view_array( $category);
		
		// -----
		
		//echo t3lib_div::view_array( $category);
		//echo t3lib_div::view_array( $combo0 );
				
		$categories_script = '
		
		
<script type="text/javascript" language="JavaScript"><!--
var menu=document.callforpapers.primary;
var i =0;
';
		foreach($category as $key => $value){
				
			$categories_script .= '
			menu.options[i]= new Option("'.$value.'","'.$key.'");
			i++;
';


					//echo $key;

		 	//$comboKarr = "combo{$key}";
			//$this->checkVar($key);
			//$this->checkVar($comboKarr);
			//$this->checkVar($$comboKarr);
			//$this->checkVar($combo{$key});
			
			//echo $combo{$key}; echo "[$key]"..": $category[$key] -> combo{$key}<br>";
			//echo t3lib_div::view_array($combo{$key});
			
			/*foreach($combo{$key} as $combokey => $combovalue){
			//foreach($$comboKarr as $combokey => $combovalue){
				//echo $combo{$key}[$combokey];
				$categories_script .= '
combo'.$key.'['.$combokey.']=new Option("'.$combovalue.'","'.$key.'.'.$combokey.'")';
			}*/
		}

		$categories_script .= '


//-->
</script>
';
		
		return $categories_script;
	
	}


	function insertDB(){
		if (is_array($this->formData[tx_interacallforpapers][DATA])){
		$insertDataArr = array();
			foreach($this->formData[tx_interacallforpapers][DATA] as $key => $value){

				switch( (string)$key ){
					case 'chr1':
					case 'chr2':
					case 'chr3':
					case 'secondary-category':
						//no check these fields
					break;
					case 'primary-category':
						list($p_cat, $s_cat) = explode(".", $value);
						$this->insertDataArr['primarycategory'] = $p_cat;
						$this->insertDataArr['pid'] = $p_cat;
					break;
					default:
						$this->insertDataArr[eregi_replace('-','',(string)$key)] = $value;
					break;
				}//end switch
				
			}//end foreach
			//echo t3lib_div::view_array($this->insertDataArr);
		$insertQuery = $GLOBALS['TYPO3_DB']->INSERTquery('tx_interacallforpapers_abstract', $this->insertDataArr);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $insertQuery);
		}
	}
	
	function checkVar($varname){
		
		echo '<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="1"><tr><td><strong>';
		echo $varname;
		echo '</strong></td></tr><tr><td><pre>';
		var_dump($varname);
		echo '</pre></td></tr></table><br>';
	
	}
	

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/pi1/class.tx_interacallforpapers_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/pi1/class.tx_interacallforpapers_pi1.php']);
}

?>