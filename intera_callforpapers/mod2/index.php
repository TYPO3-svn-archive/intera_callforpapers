<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 InteRa srl - David Denicolo <typo3@intera.it>
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
 * Module 'Administrator' for the 'intera_callforpapers' extension.
 *
 * @author    InteRa srl - David Denicolo <typo3@intera.it>
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

//$LANG->includeLLFile("EXT:intera_callforpapers/mod2/locallang.xml");
$LANG->includeLLFile("EXT:intera_callforpapers/locallang_db.xml");
require_once (PATH_t3lib."class.t3lib_scbase.php");
$BE_USER->modAccess($MCONF,1);    // This checks permissions and exits if the users has no permission for entry.
    // DEFAULT initialization of a module [END]

class tx_interacallforpapers_module2 extends t3lib_SCbase {
    var $pageinfo;
	var $extKey = 'intera_callforpapers'; // The extension key.
	var $abstractRoot;// = 468;
    /**
     * Initializes the Module
     * @return    void
     */
    function init()    {
        global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

        parent::init();

        /*
        if (t3lib_div::_GP("clear_all_cache"))    {
            $this->include_once[]=PATH_t3lib."class.t3lib_tcemain.php";
        }
        */
    }

    /**
     * Adds items to the ->MOD_MENU array. Used for the function menu selector.
     *
     * @return    void
     */
    function menuConfig()    {
        global $LANG;
        $this->MOD_MENU = Array (
            "function" => Array (
                "1" => $LANG->getLL("function2.1"),
                "2" => $LANG->getLL("function2.2"),
              //  "3" => $LANG->getLL("function2.3"),
            )
        );
        parent::menuConfig();
    }

    /**
     * Main function of the module. Write the content to $this->content
     * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
     *
     * @return    [type]        ...
     */
    function main()    {
        global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
        $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
        $this->abstractRoot = $this->extConf['beCatRoot'];
		if(!$this->id) {
			$this->id=$this->abstractRoot;
		}
        // Access check!
        // The page will show only if there is a valid page and if this page may be viewed by the user
        $this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
        $access = is_array($this->pageinfo) ? 1 : 0;
		
        if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))    {

                // Draw the header.
            $this->doc = t3lib_div::makeInstance("bigDoc");
            $this->doc->backPath = $BACK_PATH;
            $this->doc->form='<form action="" method="POST">';

                // JavaScript
            $this->doc->JScode = '
                <script language="javascript" type="text/javascript">
                    script_ended = 0;
                    function jumpToUrl(URL)    {
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

            $this->content.=$this->doc->startPage($LANG->getLL("title2"));
            $this->content.=$this->doc->header($LANG->getLL("title2"));
            $this->content.=$this->doc->spacer(5);
            $this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
            $this->content.=$this->doc->divider(5);


            // Render content:	
            $this->moduleContent();


            // ShortCut
            if ($BE_USER->mayMakeShortcut())    {
                $this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
            }

            $this->content.=$this->doc->spacer(10);
        } else {
                // If no access or if ID == zero

            $this->doc = t3lib_div::makeInstance("mediumDoc");
            $this->doc->backPath = $BACK_PATH;

            $this->content.=$this->doc->startPage($LANG->getLL("title2"));
            $this->content.=$this->doc->header($LANG->getLL("title2"));
            $this->content.=$this->doc->spacer(5);
            $this->content.=$this->doc->spacer(10);
        }
    }

    /**
     * Prints out the module HTML
     *
     * @return    void
     */
    function printContent()    {

        $this->content.=$this->doc->endPage();
        echo $this->content;
    }

    /**
     * Generates the module content
     *
     * @return    void
     */
    function moduleContent()    {
       switch((string)$this->MOD_SETTINGS["function"])    {
            case 2:
			
			 //$content .= $this->getDataFromDB();
				$dircsv = 'typo3temp/';
				//ECHO t3lib_div::getIndpEnv(TYPO3_DOCUMENT_ROOT);
				$fileCSV = $dircsv.'abstract_rating_'.mktime().'_'.rand(5,15).'.csv';
				$contentCSV = $this->getDataRatingCSV($res);
				$dirwrite = t3lib_div::getIndpEnv(TYPO3_DOCUMENT_ROOT);
                                $file1 = t3lib_div::tempnam('abstract_rating_').'.csv';
				//t3lib_div::writeFile($dirwrite.'/'.$fileCSV,$contentCSV);
                                t3lib_div::writeFile($file1,$contentCSV);
                                //$Ifilename = substr($file1,'/usr/local/psa/home/vhosts/omc.it/httpdocs/2009/');

				$IfileAbsPath = substr($file1, 0, strrpos($file1, '/')+1);
				$Ifilename =  eregi_replace($IfileAbsPath,'',$file1);

				//debug('Writing: '.$fileCSV,1);
				//$this->content .='<BR><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).$fileCSV.'">'.$GLOBALS['LANG']->getLL('save').' <img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).'typo3/gfx/savedok.gif" width="21" height="16" alt="Salva la ricerca" border="0"></a>';
				$this->content .='<BR><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).$dircsv.$Ifilename.'">'.$GLOBALS['LANG']->getLL('save').' <img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).'typo3/gfx/savedok.gif" width="21" height="16" alt="Salva la ricerca" border="0"></a>';
				//$this->content .='<BR><a href="'.$file1.'">'.$GLOBALS['LANG']->getLL('save').' <img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).'typo3/gfx/savedok.gif" width="21" height="16" alt="Salva la ricerca" border="0"></a>';
				
				$content .= '<br>'.$this->getDataRating();
				
                $this->content.=$this->doc->section($this->MOD_MENU["function"][2],$content,0,1);
            break;
            case 1:
			default:
                //$content .= $this->getDataFromDB();
				$dircsv = 'typo3temp/';
				//ECHO t3lib_div::getIndpEnv(TYPO3_DOCUMENT_ROOT);
				$fileCSV = $dircsv.'abstract_list_'.mktime().'_'.rand(5,15).'.csv';
				$contentCSV = $this->getDataFromDBCSV($res);
				$dirwrite = t3lib_div::getIndpEnv(TYPO3_DOCUMENT_ROOT);
				//t3lib_div::writeFile($fileCSV,$contentCSV);

                                $file1 = t3lib_div::tempnam('abstract_list_').'.csv';
                                t3lib_div::writeFile($file1,$contentCSV);
				$IfileAbsPath = substr($file1, 0, strrpos($file1, '/')+1);
				$Ifilename =  eregi_replace($IfileAbsPath,'',$file1);

				//debug('Writing: '.$fileCSV,1);
				//$this->content .='<BR><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).$fileCSV.'">'.$GLOBALS['LANG']->getLL('save').' <img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).'typo3/gfx/savedok.gif" width="21" height="16" alt="Salva la ricerca" border="0"></a>';

				$this->content .='<BR><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).$dircsv.$Ifilename.'">'.$GLOBALS['LANG']->getLL('save').' <img src="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).'typo3/gfx/savedok.gif" width="21" height="16" alt="Salva la ricerca" border="0"></a>';				

				$content .= '<br>'.$this->getDataFromDB();
                $this->content.=$this->doc->section($this->MOD_MENU["function"][1],$content,0,1);
            break;
        }
    }
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getDataFromDB(){
		//echo "Latest = $latest";
		$theorder = 'crdate';
		if ($_GET['theOrder']){
			$theorder = $_GET['theOrder'];
		}
		$theorder2 = ' ASC';
		if ($_GET['theOrder2']){
			$theorder .= ' '.$_GET['theOrder'];
		}
		//$this->pid = $pageId;
		
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','crdate','');
		//$resINC = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid','tx_interacallforpapers_abstract as A,tx_interacallforpapers_vote as V',"A.hidden = 0 AND A.deleted = 0 AND V.hidden = 0 AND V.deleted = 0 AND  A.uid = V.abstract AND V.votername = $voterUid ",'','crdate','');
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid",'','crdate','');
		
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('A.protocolnumber, A.papertitle, A.author, A.coauthors, A.companyaffiliation, A.country, A.tel, A.email, P2.title as pcategory, P1.title as scategory, A.crdate','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');
       // echo $resP = $GLOBALS['TYPO3_DB']->SELECTquery('A.papertitle, A.author, A.coauthors, A.companyaffiliation, A.country, A.tel, A.email, P2.title as pcategory, P1.title as scategory, A.protocolnumber','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');

		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0 ','','crdate','');
        
		
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		/*var_dump ($resC);
		var_dump ($resA);*/
		$i= $rowC;
		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"
		$datafromdb = '';
		$datafromdbHead = '';
		$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
		$datafromdbHead .= '<tr bgcolor="#ffcc99">';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			foreach ($row as $key => $value) {
				switch ((string)$key){
					case 'pcategory':
					case 'scategory':
						$datafromdbHead2 .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
					case 'crdate':
						$datafromdbHead2 .= '<td><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'mod2/index.php?theOrder='.$key.'">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</a></td>';
						$value = date('d/m/Y',$value);
						$datafromdbBody .= "<td>$value</td>";
					break;
					default:
						$datafromdbHead2 .= '<td><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'mod2/index.php?theOrder='.$key.'">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</a></td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
				}
			}
			
			//$datafromdbHead .= $datafromdbHead2.'</tr>';
			$datafromdbBody = '<tr>'.$datafromdbBody.'</tr>';
			//$datafromdbBody2 .= $datafromdbBody;
			$datafromdbHead3 = $datafromdbHead2;
			$datafromdbHead2 ='';
			$datafromdb .= $datafromdbBody;
			//$datafromdbHead = '';
			$datafromdbBody = '';
			//$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdbHead3.$datafromdb. '</table>';
		return $datafromdb;
	}
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getDataFromDBCSV(){
		//echo "Latest = $latest";
		$theorder = 'crdate';
		if ($_GET['theOrder']){
			$theorder = $_GET['theOrder'];
		}
		$theorder2 = ' ASC';
		if ($_GET['theOrder2']){
			$theorder .= ' '.$_GET['theOrder'];
		}
		//$this->pid = $pageId;
		
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','crdate','');
		//$resINC = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid','tx_interacallforpapers_abstract as A,tx_interacallforpapers_vote as V',"A.hidden = 0 AND A.deleted = 0 AND V.hidden = 0 AND V.deleted = 0 AND  A.uid = V.abstract AND V.votername = $voterUid ",'','crdate','');
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid",'','crdate','');
		
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('A.protocolnumber, A.papertitle, A.author, A.companyaffiliation, A.country, A.tel, A.email, P2.title as pcategory, P1.title as scategory, A.crdate','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');
       // echo $resP = $GLOBALS['TYPO3_DB']->SELECTquery('A.papertitle, A.author, A.coauthors, A.companyaffiliation, A.country, A.tel, A.email, P2.title as pcategory, P1.title as scategory, A.protocolnumber','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');

		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0 ','','crdate','');
        
		
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		/*var_dump ($resC);
		var_dump ($resA);*/
		$i= $rowC;
		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"
		$datafromdb = '';
		$datafromdbHead = '';
		//$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
		//$datafromdbHead .= '<tr bgcolor="#ffcc99">';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			foreach ($row as $key => $value) {
					$datafromdbHead2 .= $GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).';';
					if ($key == 'crdate'){
						$value = date('d/m/Y',$value);
					}
					if ($key == 'Tel'){
						$value = 'Tel: '.$value;
					}
					$value = ereg_replace(';', ' - ', $value);
					$value = ereg_replace("chr(10)", ' . ', $value);
					$datafromdbBody .= "$value;";
					//echo "<div style='border: 1px solid; width: 300; padding: 10;'>$valueM</div><br>";
			}
			
			//$datafromdbHead .= $datafromdbHead2.'</tr>';
			$datafromdbBody = $datafromdbBody.chr(10);
			//echo "<hr>";
			//$datafromdbBody2 .= $datafromdbBody;
			$datafromdbHead3 = $datafromdbHead2;
			$datafromdbHead2 ='';
			$datafromdb .= $datafromdbBody;
			//$datafromdbHead = '';
			$datafromdbBody = '';
			//$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdbHead3.chr(10).$datafromdb;
		return $datafromdb;
	}
	
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getDataRating(){
		//echo "Latest = $latest";
		$theorder = 'crdate';
		if ($_GET['theOrder']){
			$theorder = $_GET['theOrder'];
		}
		$theorder2 = ' ASC';
		if ($_GET['theOrder2']){
			$theorder .= ' '.$_GET['theOrder'];
		}
		$beUsername = array();
		//echo $GLOBALS['TYPO3_DB']->SELECTquery('BE.username,realName','be_users AS BE'," BE.disable =0 AND BE.deleted =0 AND BE.usergroup =2",'','','');
		$resBE = $GLOBALS['TYPO3_DB']->exec_SELECTquery('BE.username,realName','be_users AS BE'," BE.disable =0 AND BE.deleted =0 AND BE.usergroup =2",'','','');
		while ($rowBE = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resBE)) {
			$username = 'be_'.$rowBE['username'];
			$realName = $rowBE['realName'];
			$beUsername["$username"] = '';
			$beUserrealName["$username"] = $realName;
		}
		//echo "<br>". t3lib_div::view_array($beUsername);
		
		//$this->pid = $pageId;
		
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','crdate','');
		//$resINC = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid','tx_interacallforpapers_abstract as A,tx_interacallforpapers_vote as V',"A.hidden = 0 AND A.deleted = 0 AND V.hidden = 0 AND V.deleted = 0 AND  A.uid = V.abstract AND V.votername = $voterUid ",'','crdate','');
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid",'','crdate','');
		
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('A.uid, A.protocolnumber, A.crdate, A.papertitle, P2.title AS pcategory, P1.title AS scategory, A.author, A.companyaffiliation, A.country','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');
       //echo $resP = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid, A.protocolnumber, A.papertitle, P2.title AS pcategory, P1.title AS scategory, A.author, A.companyaffiliation, A.country','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');

		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0 ','','crdate','');
		
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		/*var_dump ($resC);
		var_dump ($resA);*/
		$i= $rowC;
		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"
		$datafromdb = '';
		$datafromdbHead = '';
		$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
		$datafromdbHead .= '<tr bgcolor="#ffcc99">';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			$abstractUid = $row['uid'];
			$resV = $GLOBALS['TYPO3_DB']->exec_SELECTquery('AVG(V.vote), Count(V.vote)','tx_interacallforpapers_vote AS V'," V.hidden =0 AND V.deleted =0 AND V.abstract = $abstractUid ",'','','');

			
			
			//var_dump($resV);
			
			$rowV = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resV);
			//var_dump($rowV);
			$row['avgrating'] = $rowV['AVG(V.vote)']? $rowV['AVG(V.vote)']:'';
			$row['numvotes'] = $rowV['Count(V.vote)']? $rowV['Count(V.vote)']:'';
			
			//echo $GLOBALS['TYPO3_DB']->SELECTquery('V.vote, BE.username','tx_interacallforpapers_vote AS V, be_users AS BE'," V.hidden =0 AND V.deleted =0 AND V.votername = BE.uid AND V.abstract = $abstractUid",'','','');
			$resVall = $GLOBALS['TYPO3_DB']->exec_SELECTquery('V.vote, BE.username','tx_interacallforpapers_vote AS V, be_users AS BE'," V.hidden =0 AND V.deleted =0 AND V.votername = BE.uid AND V.abstract = $abstractUid",'','','');

			

			$rowTemp = array();
			$rowVall = array();
			$row = array_merge ( $row , $beUsername);

			while ($rowVall = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resVall)){
				$userN = 'be_'.$rowVall['username'];
				$rowTemp["$userN"] = $rowVall['vote'];
				//var_dump($rowVall);
				$row["$userN"] = $rowVall['vote'];
			}
			
			
			
			//echo "<br>". t3lib_div::view_array($rowTemp);
			
			
			//while ($rowV = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resV)) {}
			
			foreach ($row as $key => $value) {
			//echo '-> '.(string)$key.'<br>';
				switch((string)$key){
					case 'uid':
					break;
					case 'crdate':
						$value = date('d/m/Y',$value);
						$datafromdbHead2 .= '<td><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'mod2/index.php?theOrder='.$key.'">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</a></td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
					case 'pcategory':
					case 'scategory':
					case 'avgrating':
					case 'numvotes':
						$datafromdbHead2 .= '<td>'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</td>';
						$datafromdbBody .= "<td>$value</td>";
					break;
					default:
						if (substr((string)$key, 0, 3)== 'be_' ){
							//$datafromdbHead2 .= '<td>'.$beUserrealName[$key].'</td>';
							//$datafromdbBody .= "<td>$value</td>";
						}else{
							$datafromdbHead2 .= '<td><a href="'.t3lib_div::getIndpEnv(TYPO3_SITE_URL).t3lib_extMgm::siteRelPath($this->extKey).'mod2/index.php?theOrder='.$key.'">'.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).'</a></td>';
							$datafromdbBody .= "<td>$value</td>";
						}
					break;
				}
			}
			
			//$datafromdbHead .= $datafromdbHead2.'</tr>';
			$datafromdbBody = '<tr>'.$datafromdbBody.'</tr>';
			//$datafromdbBody2 .= $datafromdbBody;
			$datafromdbHead3 = $datafromdbHead2;
			$datafromdbHead2 ='';
			$datafromdb .= $datafromdbBody;
			//$datafromdbHead = '';
			$datafromdbBody = '';
			//$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdbHead3.$datafromdb. '</table>';
		return $datafromdb;
	}
	
	
	/**
	 * get Data From DB
	 *
	 * @return	data
	 */
	function getDataRatingCSV(){
		//echo "Latest = $latest";
		$theorder = 'crdate';
		if ($_GET['theOrder']){
			$theorder = $_GET['theOrder'];
		}
		$theorder2 = ' ASC';
		if ($_GET['theOrder2']){
			$theorder .= ' '.$_GET['theOrder'];
		}
		$beUsername = array();
		//echo $GLOBALS['TYPO3_DB']->SELECTquery('BE.username,realName','be_users AS BE'," BE.disable =0 AND BE.deleted =0 AND BE.usergroup =2",'','','');
		$resBE = $GLOBALS['TYPO3_DB']->exec_SELECTquery('BE.username,realName','be_users AS BE'," BE.disable =0 AND BE.deleted =0 AND BE.usergroup =2",'','','');
		while ($rowBE = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resBE)) {
			$username = 'be_'.$rowBE['username'];
			$realName = $rowBE['realName'];
			$beUsername["$username"] = '';
			$beUserrealName["$username"] = $realName;
		}
		//echo "<br>". t3lib_div::view_array($beUsername);
		
		//$this->pid = $pageId;
		
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0','','crdate','0,10');
		//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted = 0 AND pid = $this->pid",'','crdate','');
		//$resINC = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid','tx_interacallforpapers_abstract as A,tx_interacallforpapers_vote as V',"A.hidden = 0 AND A.deleted = 0 AND V.hidden = 0 AND V.deleted = 0 AND  A.uid = V.abstract AND V.votername = $voterUid ",'','crdate','');
		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract',"hidden = 0 AND deleted= 0 AND pid = $this->pid",'','crdate','');
		
		$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('A.uid, A.protocolnumber, A.papertitle, P2.title AS pcategory, P1.title AS scategory, A.author, A.companyaffiliation, A.country, A.crdate','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');
       //echo $resP = $GLOBALS['TYPO3_DB']->SELECTquery('A.uid, A.protocolnumber, A.papertitle, P2.title AS pcategory, P1.title AS scategory, A.author, A.companyaffiliation, A.country','tx_interacallforpapers_abstract as A, pages as P1, pages as P2',' A.hidden = 0 AND A.deleted = 0 AND A.pid = P1.uid AND P1.pid = P2.uid ','',"A.$theorder",'');

		//$resC = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_interacallforpapers_abstract','hidden = 0 AND deleted = 0 ','','crdate','');
		
		$rowC = $GLOBALS['TYPO3_DB']->sql_num_rows($resC);
		//echo "sql_num_rows = $rowC";
		/*var_dump ($resC);
		var_dump ($resA);*/
		$i= $rowC;
		//style="border: 1px solid gray; empty-cells : show;" bgcolor="white"
		$datafromdb = '';
		$datafromdbHead = '';
		//$datafromdbHead .= '<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: 1px solid white; empty-cells : show;">';
		//$datafromdbHead .= '<tr bgcolor="#ffcc99">';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resC)) {
			$abstractUid = $row['uid'];
			$resV = $GLOBALS['TYPO3_DB']->exec_SELECTquery('AVG(V.vote), Count(V.vote)','tx_interacallforpapers_vote AS V'," V.hidden =0 AND V.deleted =0 AND V.abstract = $abstractUid ",'','','');
			
			$rowV = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resV);
			//var_dump($rowV);
			$row['avgrating'] = $rowV['AVG(V.vote)']? $rowV['AVG(V.vote)']:'';
			$row['numvotes'] = $rowV['Count(V.vote)']? $rowV['Count(V.vote)']:'';
			
			//echo $GLOBALS['TYPO3_DB']->SELECTquery('V.vote, BE.username','tx_interacallforpapers_vote AS V, be_users AS BE'," V.hidden =0 AND V.deleted =0 AND V.votername = BE.uid AND V.abstract = $abstractUid",'','','');
			$resVall = $GLOBALS['TYPO3_DB']->exec_SELECTquery('V.vote, BE.username','tx_interacallforpapers_vote AS V, be_users AS BE'," V.hidden =0 AND V.deleted =0 AND V.votername = BE.uid AND V.abstract = $abstractUid",'','','');

			$rowTemp = array();
			$rowVall = array();
			$row = array_merge ( $row , $beUsername);

			while ($rowVall = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resVall)){
				$userN = 'be_'.$rowVall['username'];
				$rowTemp["$userN"] = $rowVall['vote'];
				//var_dump($rowVall);
				$row["$userN"] = $rowVall['vote'];
			}
			
			foreach ($row as $key => $value) {
				switch((string)$key){
					case 'uid':
					break;
					case 'crdate':
						$datafromdbHead2 .= ''.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).';';
						$value = date('d/m/Y',$value);
						$value = ereg_replace(';', ' - ', $value);
						$value = ereg_replace("chr(10)", ' . ', $value);
						$datafromdbBody .= "$value;";
					break;
					default:
						if (substr((string)$key, 0, 3)== 'be_' ){
							$datafromdbHead2 .= $beUserrealName[$key].';';
							$datafromdbBody .= "$value;";
						}else{
							$datafromdbHead2 .= ''.$GLOBALS['LANG']->getLL('tx_interacallforpapers_abstract.'.$key).';';
							$value = ereg_replace(';', ' - ', $value);
							$value = ereg_replace("chr(10)", ' . ', $value);
							$datafromdbBody .= "$value;";
						}
					break;
				}
			}
			
			//$datafromdbHead .= $datafromdbHead2.'</tr>';
			$datafromdbBody = $datafromdbBody.chr(10);
			//echo "<hr>";
			//$datafromdbBody2 .= $datafromdbBody;
			$datafromdbHead3 = $datafromdbHead2;
			$datafromdbHead2 ='';
			$datafromdb .= $datafromdbBody;
			//$datafromdbHead = '';
			$datafromdbBody = '';
			//$i--;
		}//end while
		$datafromdb = $datafromdbHead.$datafromdbHead3.chr(10).$datafromdb;
		return $datafromdb;
	}
	
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/mod2/index.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/intera_callforpapers/mod2/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_interacallforpapers_module2');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)    include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>