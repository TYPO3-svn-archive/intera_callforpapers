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
 * Module 'Papers' for the 'intera_callforpapers' extension.
 *
 * @author	InteRa srl - David Denicolò <typo3@intera.it>
 */

	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require ("conf.php");
require ($BACK_PATH."init.php");
require ($BACK_PATH."template.php");

require_once (PATH_t3lib.'class.t3lib_basicfilefunc.php');
require_once (PATH_t3lib.'class.t3lib_browsetree.php');
require_once (PATH_t3lib.'class.t3lib_foldertree.php');
require_once (PATH_t3lib.'class.t3lib_tstemplate.php');
require_once (PATH_t3lib.'class.t3lib_loadmodules.php');
require_once (PATH_t3lib.'class.t3lib_tsparser_ext.php');
require_once (PATH_typo3.'class.alt_menu_functions.inc');

//$LANG->includeLLFile("EXT:intera_callforpapers/mod1/locallang.xml");
$LANG->includeLLFile("EXT:intera_callforpapers/locallang_db.xml");
require_once (PATH_t3lib."class.t3lib_scbase.php");

$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

class tx_interacallforpapers_module1 extends t3lib_SCbase {
	var $pageinfo;
	var $extKey = 'intera_callforpapers'; // The extension key.

   	var $abstractRoot; //= 344;
	/**
	 * Initializes the Module
	 * @return	void
	 */
	function init()	{
		//	echo "<br>". t3lib_div::view_array($BE_USER);
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		parent::init();
		//require_once(t3lib_extMgm::extPath('intera_callforpapers')."config.inc.php");
		/*
		if (t3lib_div::_GP("clear_all_cache"))	{
			$this->include_once[]=PATH_t3lib."class.t3lib_tcemain.php";
		}
		*/
	}

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return	void
	 */
	function menuConfig()	{
		global $LANG;
		$this->MOD_MENU = Array (
			"function" => Array (
				"1" => $LANG->getLL("function1"),
				"2" => $LANG->getLL("function2"),
				"3" => $LANG->getLL("function3"),
			)
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	 *
	 * @return	[type]		...
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		$this->init($conf);
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		$this->abstractRoot = $this->extConf['beCatRoot'];
		
		if(!$this->id) {
			$this->id=$this->abstractRoot;
		}

		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		//echo t3lib_div::view_array($this->pageinfo);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))	{

			// Draw the header.
			//$this->doc = t3lib_div::makeInstance("mediumDoc");
			
			if ($_GET['theCMD']=='popup') {
				$this->doc = t3lib_div::makeInstance("bigDoc");
			}else{
				$this->doc = t3lib_div::makeInstance("bigDoc");
			}
			
			
			$this->doc->backPath = $BACK_PATH;
			$this->doc->form='<form action="" method="POST">';

				// JavaScript
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script>
			';
			$this->doc->postCode='
				<script language="javascript" type="text/javascript">
					script_ended = 1;
					if (top.fsMod) top.fsMod.recentIds["web"] = 0;
				</script>
			';

			$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br />".$LANG->sL("LLL:EXT:lang/locallang_core.xml:labels.path").": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			


			// Render content:
			
			if ($_GET['theCMD']=='popup') {
				$this->popupContent();
			}else{
				$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
				$this->content.=$this->doc->divider(5);
				$this->moduleContent();
			}

			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}

			$this->content.=$this->doc->spacer(10);
		} else {
				// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent()	{

		$this->content.=$this->doc->endPage();
		echo $this->content;
	}

	/**
	 * Generates the module content
	 *
	 * @return	void
	 */
	function moduleContent()	{
                $content0.= '<a target="_blank" href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'guide.pdf"><img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'pdf.gif" width="16" height="16" align="absmiddle" /> open the guide</a><br>';
		switch((string)$this->MOD_SETTINGS["function"])	{
			case 2:
				if ($_GET[id]) {
					$content.=$this->getDataFromDB($_GET[id]);
				}
				$this->content.=$content0.$this->doc->section($this->MOD_MENU["function"][2],$content,0,1);
			break;
			case 3:
				$content.=$this->getRiepDataFromDB();
				$this->content.=$content0.$this->doc->section($this->MOD_MENU["function"][3],$content,0,1);
			break;
			case 1:
			default:
				if ($_GET[id]) {
					$pageid = $_GET[id];
				}else{
					$pageid = $this->abstractRoot;
				}
				$content .= $this->getDataFromDB($pageid,true);
				$this->content.=$content0.$this->doc->section($this->MOD_MENU["function"][1],$content,0,1);
		}
	}
	
	/**
	 * Generates the popup content
	 *
	 * @return	void
	 */
	function popupContent()	{
		//$content="<div align=center><strong>Menu item #3...</strong></div>";
		//$content.= 
		//$content.="GET:".t3lib_div::view_array($_GET)."<br />"."POST:".t3lib_div::view_array($_POST)."<br />";
		//echo $GLOBALS['BE_USER']->user['username'];
		/*
		$content.= $GLOBALS['BE_USER']->user['username'];
		$content.= $GLOBALS['BE_USER']->user['uid'];
		$content.= $GLOBALS['BE_USER']->user['realName'];
		$content.= $GLOBALS['BE_USER']->user['email'];
		$content.="BE_USER:".t3lib_div::view_array($GLOBALS['BE_USER']->user)."<br />";
		*/
		//$content.="GET:".t3lib_div::view_array($_GET);
		$content.= 'Welcome <strong>'.$GLOBALS['BE_USER']->user['realName'].'</strong>';
		$content.= ', your Username is <strong>'.$GLOBALS['BE_USER']->user['username'].'</strong>';
		$content.= ' and your email is <strong>'.$GLOBALS['BE_USER']->user['email'].'</strong>';
		
		
		if (t3lib_div::_POST('vote')>0){ //dati ricevuti
			//$content.= '<br><br><br>there is some POST:'.t3lib_div::view_array($_POST)."<br />";
			$content.= '
						<table cellspacing=0 cellpadding=4 border=1>
						<tr>
						    <td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.papertitle').' ['.t3lib_div::_POST('abstract').']</td>
						    <td><strong>'.$this->getSingleData('uid',t3lib_div::_POST('abstract'),'abstract','papertitle').'</strong></td>
						</tr>
						<tr>
						    <td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.votername').' ['.t3lib_div::_POST('votername').']</td>
						    <td><strong>'.$GLOBALS['BE_USER']->user['realName'].' ('.$GLOBALS['BE_USER']->user['username'].')</strong></td>
						</tr>
						<tr>
						    <td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote').'</td>
						    <td>'.t3lib_div::_POST('vote').'</td>
						</tr>
						<tr>
						    <td>Note</td>
						    <td>'.t3lib_div::_POST('note').'</td>
						</tr>	
						<tr>
						    <td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime').'</td>
						    <td>'.t3lib_div::_POST('slottime').'</td>
						</tr>
						<tr>
						    <td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.closelabel').'</td>
						    <td>'.t3lib_div::_POST('closed').'</td>
						</tr>
						</table><br><br><a href="#" onclick="self.close()">Close window</a>';
						$insertFields = array(
							'pid' => $this->abstractRoot,
							'crdate' => time(),
							'abstract' => intval(t3lib_div::_POST('abstract')),
							'votername' => intval(t3lib_div::_POST('votername')),
							'vote' => intval(t3lib_div::_POST('vote')),
							'note' => t3lib_div::_POST('note'),
							'slottime' => t3lib_div::_POST('slottime'),
							'closed' => t3lib_div::_POST('closed'),
						);
						//echo t3lib_div::_POST('mod');
						if (intval(t3lib_div::_POST('mod'))==1){
							$voteuid = t3lib_div::_POST('voteuid');
							$insertFields = array(
								'pid' => $this->abstractRoot,
								'tstamp' => time(),
								'abstract' => intval(t3lib_div::_POST('abstract')),
								'votername' => intval(t3lib_div::_POST('votername')),
								'vote' => intval(t3lib_div::_POST('vote')),
								'note' => t3lib_div::_POST('note'),
								'slottime' => t3lib_div::_POST('slottime'),
								'closed' => t3lib_div::_POST('closed'),
							);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_interacallforpapers_vote',"uid=$voteuid",$insertFields);
						}else{
							$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_interacallforpapers_vote',$insertFields);
						}
		}elseif(t3lib_div::_GET('theUID')){ //form iniziale il controllo su theUID è per sicurezza maggiore
			$abstractUid = t3lib_div::_GET('theUID');
			$voterUid = $GLOBALS['BE_USER']->user['uid'];
			$row = $this->getRowFromDB($abstractUid);
			//$content.= t3lib_div::view_array($row);
			
			//$content.= $this->checkPermess($abstractUid,$voterUid);
			
			$datafromdb .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
			foreach ($row as $key => $value) {
				switch ((string)$key){
					case 'abstract':
					case 'experience':
					//case 'innovation':
					//case 'technicalcontribution':
					//case 'economiccontribution':
					case 'cv':
					case 'position':
					case 'city':
					case 'position':
					case 'country':
					case 'papertitle':
					case 'protocolnumber':
					case 'author':
					case 'companyaffiliation':
					case 'slot':
						$datafromdb .= '<tr>';
						$datafromdb .= '<td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdb .= "<td>$value</td>";
						$datafromdb .= '</tr>';
					break;
					default:
				}
			}
			$datafromdb .= '</table>';
			$content.= $datafromdb;
			
			if ($this->checkPermess($abstractUid,$voterUid)){
			
				$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_vote',"hidden = 0 AND deleted = 0 AND votername = $voterUid AND abstract = $abstractUid ",'','crdate','');
				$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
				
				if ($rowC > 0 ){ // voto già esistente
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC);
					if ($row['closed']==1){
						$content.= '<br><br><strong><font color="red">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.closed').'</font></strong><br>';
					}else{
						$content.= '<br><br><strong><font color="red">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.yetvalued').'</font></strong><br>';
						$content.= '<br><br><form action="'.t3lib_extMgm::extPath($this->extKey).'mod1/index.php?theCMD=popup" method="post">
									<input type="hidden" name="votername" value="'.$GLOBALS['BE_USER']->user['uid'].'">
									<input type="hidden" name="abstract" value="'.$abstractUid.'">
									<input type="hidden" name="mod" value="1">
									<input type="hidden" name="voteuid" value="'.$row['uid'].'">
									<table cellspacing=0 cellpadding=4 border=1>
									<tr>
									    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.papertitle').'</td>
									    <td><strong>'.$this->getSingleData('uid',$abstractUid,'abstract','papertitle').'</strong></td>
									</tr>
									<tr>
									    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.author').'</td>
									    <td>'.$this->getSingleData('uid',$abstractUid,'abstract','author').'</td>
									</tr>
									<tr>
									    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote').'</td>
									    <td>
										 		<table cellspacing="0" cellpadding="0" border="0" width="100%">
												<tr>
												    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="1" ';
													($row['vote']==1)? $content.= 'checked':$content.= '';
													$content.= '></td>
												    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="2" ';
													($row['vote']==2)? $content.= 'checked':$content.= '';
													$content.= '></td>
												    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="3" ';
													($row['vote']==3)? $content.= 'checked':$content.= '';
													$content.= '></td>
												    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="4" ';
													($row['vote']==4)? $content.= 'checked':$content.= '';
													$content.= '></td>
													<td align="center" valign="top" width="20%"><input type="radio" name="vote" value="5" ';
													($row['vote']==5)? $content.= 'checked':$content.= '';
													$content.= '></td>
												</tr>
												<tr>
												    <td align="center" valign="top">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote.I.0').'</td>
												    <td align="center" valign="top">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote.I.1').'</td>
												    <td align="center" valign="top">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote.I.2').'</td>
												    <td align="center" valign="top">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote.I.3').'</td>
												    <td align="center" valign="top">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote.I.4').'</td>
												</tr>
												</table>
										</td>
									</tr>
									<tr>
									    <td bgcolor="#ffcc99">Note</td>
									    <td><textarea cols="30" rows="5" name="note">'.$row['note'].'</textarea></td>
									</tr>	
									<tr>
									    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime').'</td>
									    <td><input type="radio" name="slottime" value="0" ';
												($row['slottime']==0)? $content.= 'checked':$content.= '';
												$content.= '>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.0').'&nbsp;&nbsp;&nbsp;
												<input type="radio" name="slottime" value="1" ';
												($row['slottime']==1)? $content.= 'checked':$content.= '';
												$content.= '>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.1').'&nbsp;&nbsp;&nbsp;
												<input type="radio" name="slottime" value="2" ';
												($row['slottime']==2)? $content.= 'checked':$content.= '';
												$content.= '>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.2').'
										</td>
									</tr>
									<tr>
									    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.closelabel').'</td>
									    <td><input type="checkbox" name="closed" value="1"></td>
									</tr>
									</table><br>
									<input type="submit" value="save">
									</form>';
						}
				}else{
					if (t3lib_div::_POST('abstract')>0){
						$content.= '<br><br><strong><font color="red">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.required').'</font></strong><br>';
					}
					$content.= '<br><br><form action="'.t3lib_extMgm::extPath($this->extKey).'mod1/index.php?theCMD=popup" method="post">
								<input type="hidden" name="votername" value="'.$GLOBALS['BE_USER']->user['uid'].'">
								<input type="hidden" name="abstract" value="'.$abstractUid.'">
								<input type="hidden" name="mod" value="0">
								<table cellspacing=0 cellpadding=4 border=1>
								<tr>
								    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.papertitle').'</td>
								    <td><strong>'.$this->getSingleData('uid',$abstractUid,'abstract','papertitle').'</strong></td>
								</tr>
								<tr>
								    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.author').'</td>
								    <td>'.$this->getSingleData('uid',$abstractUid,'abstract','author').'</td>
								</tr>
								<tr>
								    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote').'</td>
								    <td>
									 		<table cellspacing="0" cellpadding="0" border="0" width="100%">
											<tr>
											    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="1"></td>
											    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="2"></td>
											    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="3"></td>
											    <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="4"></td>
												 <td align="center" valign="top" width="20%"><input type="radio" name="vote" value="5"></td>
											</tr>
											<tr>
											    <td align="center" valign="top">1 (min)</td>
											    <td align="center" valign="top">2</td>
											    <td align="center" valign="top">3</td>
											    <td align="center" valign="top">4</td>
											    <td align="center" valign="top">5 (max)</td>
											</tr>
											</table>
									</td>
								</tr>
								<tr>
								    <td bgcolor="#ffcc99">Note</td>
								    <td><textarea cols="30" rows="5" name="note"></textarea></td>
								</tr>	
								<tr>
								    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime').'</td>
								    <td><input type="radio" name="slottime" value="0">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.0').'&nbsp;&nbsp;&nbsp;
											<input type="radio" name="slottime" value="1">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.1').'&nbsp;&nbsp;&nbsp;
											<input type="radio" name="slottime" value="2">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.slottime.I.2').'
									</td>
								</tr>
								<tr>
								    <td bgcolor="#ffcc99">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.closelabel').'</td>
								    <td><input type="checkbox" name="closed" value="1"></td>
								</tr>
								</table><br>
								<input type="submit" value="save">
								</form>';
					}//fine else ($rowC > 0)
			}else{//fine if checkpermess
				$content.= '<br><br><strong><font color="red">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.nopermess').'</font></strong><br>';
			}
		}else{ // errore di non ricezione dell'uid
			$content.= 'no Abstract uid was received from precedent page';
		}
		$this->content.=$this->doc->section('abstract evaluation',$content,0,1);
	}//end popupContent
	
	
	/**
	 * check permess
	 *
	 * @return	data
	 */
	function checkPermess($abstractUid,$voterUid){
		$abstractPid = $this->getSingleData('uid',$abstractUid,'abstract','pid');
		$voterpages = $this->getSingleData('votername',$voterUid,'permess','pages');
		$allPagesArr = array();
		if ((string)$voterpages!=''){
			$voterAllPagesArr = explode (',',$voterpages);
			foreach ($voterAllPagesArr as $value){
				$voterAllPages .= ','.$this->RecSearchSubPages($value);
			}
			//$allPagesArr = explode(',',$voterAllPages,-1);
			$allPagesArr = explode(',',$voterAllPages);
		}
		$out = (in_array($abstractPid,$allPagesArr))? 1:0;
		/*if (!$out){
			echo $voterAllPages;echo t3lib_div::view_array($allPagesArr);
		}*/
		return $out;
	}
	
	function RecSearchSubPages($aPid){
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','pages',"hidden = 0 AND deleted = 0 AND pid = $aPid",'','','');
		//echo $GLOBALS['TYPO3_DB']->SELECTquery('*','pages',"hidden = 0 AND deleted = 0 AND pid = $aPid",'','','');;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
		//echo 'test';
			if ($this->hadSubPages($row['uid'])){
				$rPid .= ','.$this->RecSearchSubPages($row['uid']);
			}else{
				$rPid .= ','.$row['uid'];
			}
		}
		return $aPid.$rPid;
	}
	
	function hadSubPages($uid){
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','pages',"hidden = 0 AND deleted = 0 AND pid = $uid",'','','');
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getDataFromDB($pageId,$latest=0){
		//echo "Latest = $latest";
		$this->pid = $pageId;
		$datafromdb = '';
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','crdate','');
		if ($latest){
			$voterUid = $GLOBALS['BE_USER']->user['uid'];
			$resINC = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid','tx_interacallforpapers_abstract as A,tx_interacallforpapers_vote as V',"A.hidden = 0 AND A.deleted = 0 AND V.hidden = 0 AND V.deleted = 0 AND  A.uid = V.abstract AND V.votername = $voterUid ",'','A.crdate','');
			if ($this->pid == $this->abstractRoot){
				$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND uid NOT IN ($resINC) ",'','crdate','');
			}else{
				$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid AND uid NOT IN ($resINC) ",'','crdate','');
			}
		}else{
			$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid",'','crdate','');
		}
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		//var_dump ($rowC);
		$i= $rowC;
		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			if ($latest){
				$nc = 11;
			}else{
				$nc = 10;
			}
			list($showLink,$votedLink,$showLinkTitle,$rightsIcon,$lockIcon) = $this->voteLink($row['uid']);
			$datafromdbHead = '';
			$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
			$datafromdbHead .= '<tr><td colspan="'.$nc.'"></td></tr><tr bgcolor="#ffcc99"><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.state').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.openlab').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.right').'</td>';
			$datafromdbBody .= '<td align="center">'.$showLink.'</td><td align="center">'.$votedLink.'</td><td align="center">'.$lockIcon.'</td><td align="center">'.$rightsIcon.'</td>';
			foreach ($row as $key => $value) {
				switch ((string)$key){
					
					case 'papertitle':
						$datafromdbHead .= '<td width="40%">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.papertitleand').'</td>';
						$datafromdbBody .= '<td width="40%">'.eregi_replace('###title###',$value,$showLinkTitle).'</td>';
					break;
					case 'secondarycategory':
						if ($latest){
						$this->pageArrInfo = t3lib_BEfunc::readPageAccess($row['pid'],$this->perms_clause);
						$category = eregi_replace('/','<br>',eregi_replace('/Abstracts/','',$this->pageArrInfo['_thePathFull']));
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.category').'</td>';
						$datafromdbBody .= '<td>'.$category.'</td>';
						}
					break;
					case 'crdate':
						$datevalue = date ("d M Y",$value );
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$datevalue</td>";
					break;
					case 'author':
					case 'companyaffiliation':
					case 'slot':
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
					default:
				}
			}
					$datafromdbHead .= '</tr>';
					$datafromdbBody .= '</tr>';
					$datafromdb .= $datafromdbBody;
					//$datafromdbHead = '';
					$datafromdbBody = '';
					$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdb. '</table>';
		return $datafromdb;
	}
	
		/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getRiepDataFromDB(){
		$latest=1;
		//echo "Latest = $latest";
		$datafromdb = '';
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','','');
		$voterUid = $GLOBALS['BE_USER']->user['uid'];
		$resINC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pages','tx_interacallforpapers_permess',"hidden = 0 AND deleted = 0 AND votername = $voterUid ",'','','');


		$rowPages = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resINC);

                if ($rowPages['pages'] == ''){
                	return '';
                }
		//echo 'test'; echo ' - $rowPages : '. $rowPages['pages'];

		$pagesArr = array();

		$pagesArr = explode (',',$rowPages['pages'] );
		foreach ($pagesArr as $value){
			$pages .= ','.$this->RecSearchSubPages($value);
		}

		$pages = substr($pages,1);//= $this->RecSearchSubPages($rowPages['pages']);

		//var_dump($rowPages );
		//echo $resINC[0];
		//var_dump($resINC);
		//echo t3lib_div::view_array($rowPages );


		//echo 'test';

		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid IN ($pages) ",'','crdate','');

		//echo 'test';

		//echo $GLOBALS['TYPO3_DB']->SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid IN ($pages) ",'','crdate','');

		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);

		//echo "sql_num_rows = $rowC";

		//var_dump ($rowC);

		$i= $rowC;

		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"

	

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			if ($latest){
				$nc = 11;
			}else{
				$nc = 10;
			}
			
			$abstractUid = $row['uid'];
			
			$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_vote',"hidden = 0 AND deleted= 0 AND abstract = $abstractUid AND votername = $voterUid ",'','','');
			$row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2);


			
			list($showLink,$votedLink,$showLinkTitle,$rightsIcon,$lockIcon) = $this->voteLink($row['uid']);
			$datafromdbHead = '';
			$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
			$datafromdbHead .= '<tr><td colspan="'.$nc.'"></td></tr><tr bgcolor="#ffcc99"><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.state').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.openlab').'</td><td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.right').'</td>';
			$datafromdbBody .= '<td align="center"><strong>'.$row2['vote'].'</strong></td><td align="center">'.$votedLink.'</td><td align="center">'.$lockIcon.'</td><td align="center">'.$rightsIcon.'</td>';
			foreach ($row as $key => $value) {
				switch ((string)$key){
					
					case 'papertitle':
						$datafromdbHead .= '<td width="40%">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.papertitleand').'</td>';
						$datafromdbBody .= '<td width="40%">'.eregi_replace('###title###',$value,$showLinkTitle).'</td>';
					break;
					case 'secondarycategory':
						if ($latest){
						$this->pageArrInfo = t3lib_BEfunc::readPageAccess($row['pid'],$this->perms_clause);
						$category = eregi_replace('/','<br>',eregi_replace('/Abstracts/','',$this->pageArrInfo['_thePathFull']));
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.category').'</td>';
						$datafromdbBody .= '<td>'.$category.'</td>';
						}
					break;
					case 'crdate':
						$datevalue = date ("d M Y",$value );
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$datevalue</td>";
					break;
					case 'author':
					case 'companyaffiliation':
					case 'slot':
						$datafromdbHead .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
					default:
				}
			}
					$datafromdbHead .= '</tr>';
					$datafromdbBody .= '</tr>';
					$datafromdb .= $datafromdbBody;
					//$datafromdbHead = '';
					$datafromdbBody = '';
					$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdb. '</table>';
		return $datafromdb;
	}
	
	
	function voteLink($abstractUid){
		$this->abstractUid = $abstractUid;
		$voterUid = $GLOBALS['BE_USER']->user['uid'];
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_vote',"hidden = 0 AND deleted = 0 AND votername = $voterUid AND abstract = $abstractUid ",'','crdate','');
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		//echo t3lib_div::view_array($row);
		$outArr = array();
		//$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC);
		$onclick = 'window.open(\''.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'mod1/index.php?theCMD=popup&theUID='.$abstractUid.'\',\'_blank\',\'width=780,height=450 ,toolbar=no, location=no,status=yes,menubar=no,scrollbars=yes,resizable=yes\')';
		//$output .=  '<img src="'.t3lib_extMgm::extRelPath($this->extKey).'evaluate.gif" width="137" height="23" hspace="8" class="absmiddle" /></a>';
		$out0 = '<a href="#" onclick="'.$onclick.'">'.'<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'show.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.vote').'" border="0" align="absmiddle" hspace="3"></a>';
		if ($rowC > 0){ // voto gi� esistente
			$out1 =  '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'vote_present.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.state1').'" border="0">';
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC);
			if ($row['closed']==1){
				$out4 =  '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'locked.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.closed').'" border="0">';
			}else{
				$out4 =  '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'unlocked.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.open').'" border="0">';
			}
		}else{
			$out1 = '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'vote_absent.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.state2').'" border="0">';
			$out4 =  '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'unlocked.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.open').'" border="0">';
		}
		$out2 = '<a href="#" onclick="'.$onclick.'">###title###</a>';
		$outArr[0] = $out0;
		$outArr[1] = $out1;
		$outArr[2] = $out2;
		
		if ($this->checkPermess($abstractUid,$voterUid)){
			$outArr[3] = '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'rights.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.rights').'" border="0" align="absmiddle" hspace="3">';
		}else{
			//echo '<br>abstractUid: '.$abstractUid.' - voterUid: '.$voterUid;
			$outArr[3] = '<img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'norights.gif" width="12" height="12" alt="'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_vote.norights').'" border="0" align="absmiddle" hspace="3">';
		}
		
		$outArr[4] = $out4;
		
		return $outArr;
	}
	
	
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getSingleData($theSelField='uid',$theUid,$theTable,$thefield)	{//hidden = 0 AND deleted = 0 AND 
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',"tx_interacallforpapers_$theTable","hidden = 0 AND deleted= 0 AND $theSelField = $theUid",'','crdate','');
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		if ($rowC > 0){ //esiste
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC);
		}else{
			$row = array();
		}
		if (array_key_exists($thefield, $row)) {
	   		return $row["$thefield"];
	   	}else{
			return 'NULL';
			}
	}

	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getRowFromDB($abstractUid)	{
		$this->abstractUid = $abstractUid;
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND uid = $this->abstractUid",'','crdate','');
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		if ($rowC > 0){ //esiste
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC);
		}else{
			$row = array();
		}
  		return $row;
	}
	
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/mod1/index.php']);
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_interacallforpapers_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>