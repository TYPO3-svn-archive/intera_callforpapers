<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=="BE")	{
	t3lib_extMgm::addModule("web","txinteracallforpapersM1","",t3lib_extMgm::extPath($_EXTKEY)."mod1/");
	t3lib_extMgm::addModule("web","txinteracallforpapersM2","",t3lib_extMgm::extPath($_EXTKEY)."mod2/");
	//t3lib_extMgm::addModule("txinteracallforpapersMAdmin","","",t3lib_extMgm::extPath($_EXTKEY)."modadmin/");
	//t3lib_extMgm::addModule("txinteracallforpapersMAdmin","txinteracallforpapersM2","",t3lib_extMgm::extPath($_EXTKEY)."mod2/");

}
/*
if (TYPO3_MODE=="BE")    {
   $GLOBALS["TBE_MODULES_EXT"]["xMOD_alt_clickmenu"]["extendCMclasses"][]=array(
      "name" => "tx_interacallforpapers_cm1",
      "path" => t3lib_extMgm::extPath($_EXTKEY)."class.tx_interacallforpapers_cm1.php"
   );
}*/


t3lib_extMgm::allowTableOnStandardPages("tx_interacallforpapers_abstract");

t3lib_extMgm::allowTableOnStandardPages("tx_interacallforpapers_vote");

//if (TYPO3_MODE=="BE")	include_once(t3lib_extMgm::extPath("intera_callforpapers")."class.tx_interacallforpapers_tx_interacallforpapers_abstract_primarycategory.php");
//if (TYPO3_MODE=="BE")	include_once(t3lib_extMgm::extPath("intera_callforpapers")."class.tx_interacallforpapers_tx_interacallforpapers_abstract_secondarycategory.php");

$TCA["tx_interacallforpapers_abstract"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract',		
		'label' => 'papertitle',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"default_sortby" => "ORDER BY papertitle",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_interacallforpapers_abstract.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, papertitle, author, position, companyaffiliation, mailingaddress, city, postcode, country, tel, fax, coauthors, email, publication, copyrightclearance, primarycategory, secondarycategory, abstract, experience, technicalcontribution, economiccontribution, innovation, cv, placedateofpublication, slot, protocolnumber, vote",
	)
);

$TCA["tx_interacallforpapers_vote"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote',		
		'label' => 'uid',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		//'readOnly' => 1,
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_interacallforpapers_vote.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "votername, abstract, vote, slottime, note, closed",
	)
);

$TCA["tx_interacallforpapers_permess"] = Array (
    "ctrl" => Array (
        'title' => 'LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_permess',        
        'label' => 'votername',    
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        "default_sortby" => "ORDER BY crdate",    
        "dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
        "iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_interacallforpapers_permess.gif",
    ),
    "feInterface" => Array (
        "fe_admin_fieldList" => "votername, pages",
    )
);

/**/

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


/*
$wizConfig = array(
		'type' => 'userFunc',
		'userFunc' => 'EXT:intera_callforpapers/class.tx_interacallforpapers_wiz.php:tx_interacallforpapers_wiz->main',
		//'userFunc' => 'EXT:intera_callforpapers/mod1/index.php:tx_interacallforpapers_module1->link',
		//'params' => array('type' => 'password')
	);
	
	$confField = 'tx_intera_callforpapers';

t3lib_div::loadTCA('tx_interacallforpapers_abstract');

$TCA["tx_interacallforpapers_abstract"]['columns']['vote']['config']['wizards'][$confField]= $wizConfig;

*/


t3lib_extMgm::addPlugin(Array('LLL:EXT:intera_callforpapers/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_interacallforpapers_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_interacallforpapers_pi1_wizicon.php';

?>