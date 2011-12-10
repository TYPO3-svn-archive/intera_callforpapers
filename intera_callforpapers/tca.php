<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_interacallforpapers_abstract"] = Array (
	"ctrl" => $TCA["tx_interacallforpapers_abstract"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,papertitle,author,position,companyaffiliation,mailingaddress,city,postcode,country,tel,fax,coauthors,email,publication,copyrightclearance,primarycategory,secondarycategory,abstract,experience,technicalcontribution,economiccontribution,innovation,cv,placedateofpublication,slot,protocolnumber,vote"
	),
	"feInterface" => $TCA["tx_interacallforpapers_abstract"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"papertitle" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.papertitle",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "4",
				"eval" => "required",
			)
		),
		"author" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.author",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"position" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.position",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"companyaffiliation" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.companyaffiliation",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"mailingaddress" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.mailingaddress",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "2",
			)
		),
		"city" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.city",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"postcode" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.postcode",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",
			)
		),
		"country" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.country",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"tel" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.tel",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"fax" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.fax",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"coauthors" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.coauthors",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "4",
			)
		),
		"email" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.email",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"publication" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.publication",		
			"config" => Array (
				"type" => "radio",
				"items" => Array (
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.publication.I.0", "0"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.publication.I.1", "1"),
				),
			)
		),
		"copyrightclearance" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.copyrightclearance",		
			"config" => Array (
				"type" => "radio",
				"items" => Array (
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.copyrightclearance.I.0", "0"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.copyrightclearance.I.1", "1"),
				),
			)
		),
		"primarycategory" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.primarycategory",		
			"config" => Array (
				"type" => "none",
			)
		),
		"secondarycategory" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.secondarycategory",		
			"config" => Array (
				"type" => "none",
			)
		),
		"abstract" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.abstract",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "20",
			)
		),
		"experience" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.experience",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "2",
			)
		),
		"technicalcontribution" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.technicalcontribution",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "2",
			)
		),
		"economiccontribution" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.economiccontribution",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "2",
			)
		),
		"innovation" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.innovation",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "2",
			)
		),
		"cv" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.cv",		
			"config" => Array (
				"type" => "text",
				"cols" => "50",	
				"rows" => "6",
			)
		),
		"placedateofpublication" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.placedateofpublication",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"slot" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.slot",		
			"config" => Array (
				"type" => "radio",
				"items" => Array (
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.slot.I.1", "1"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.slot.I.2", "2"),
				),
			)
		),
		"protocolnumber" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.protocolnumber",		
			"config" => Array (
				"type" => "none",	
				"size" => "30",
			)
		),
		"vote" => Array (		
			"exclude" => 0,		
			"label" => "",		
			"config" => Array (
				"type" => "input",	
				"size" => "3",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, protocolnumber, papertitle, abstract, experience, technicalcontribution, economiccontribution, innovation, slot, publication;;;;2-2-2, placedateofpublication, copyrightclearance, author;;;;2-2-2, position, email, mailingaddress, city, postcode, country, tel, fax, coauthors, companyaffiliation, cv")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);



$TCA["tx_interacallforpapers_vote"] = Array (
	"ctrl" => $TCA["tx_interacallforpapers_vote"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "abstract,votername,vote,slottime,note,closed"
	),
	"feInterface" => $TCA["tx_interacallforpapers_vote"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"votername" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.votername",		
            "config" => Array (
                /*"type" => "group",    
                "internal_type" => "db",    
                "allowed" => "be_users",   */ 
				"type" => "select",    
                "items" => Array (
                    Array("",0),
                ),
				"foreign_table" => "be_users",
                "size" => 1,    
                "minitems" => 1,
                "maxitems" => 1,
            )
		),
		"abstract" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_abstract.papertitle",		
			"config" => Array (
                /*"type" => "group",*/
				"type" => "select",    
                "items" => Array (
                    Array("",0),
                ),
				"foreign_table" => "tx_interacallforpapers_abstract",    
                /*"internal_type" => "db",    
                "allowed" => "tx_interacallforpapers_abstract",*/    
                "size" => 1,    
                "minitems" => 0,
                "maxitems" => 1,
				'readOnly' => 1,
            )
		),
		"vote" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote.I.0", "1"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote.I.1", "2"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote.I.2", "3"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote.I.3", "4"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.vote.I.4", "5"),
				),
			)
		),
		"slottime" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.slottime",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.slottime.I.0", "rifiutato"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.slottime.I.1", "1"),
					Array("LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.slottime.I.2", "2"),
				),
			)
		),
		"note" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.note",		
			"config" => Array (
				"type" => "text",
				"cols" => "40",	
				"rows" => "6",
			)
		),
		"closed" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_vote.closelabel",		
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, votername, abstract, vote, slottime, note, closed")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);/**/

$TCA["tx_interacallforpapers_permess"] = Array (
    "ctrl" => $TCA["tx_interacallforpapers_permess"]["ctrl"],
    "interface" => Array (
        "showRecordFieldList" => "votername"
    ),
    "feInterface" => $TCA["tx_interacallforpapers_permess"]["feInterface"],
    "columns" => Array (
        "votername" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_permess.votername",        
            "config" => Array (
                "type" => "select",    
                "items" => Array (
                    Array("",0),
                ),
                "foreign_table" => "be_users",    
              //  "foreign_table_where" => "AND be_users.pid=###SITEROOT### AND usergroup=2 ORDER BY be_users.uid",    //select only correct usergroup
                "foreign_table_where" => "AND be_users.pid=###SITEROOT### ORDER BY be_users.username",    //select only correct usergroup
                "size" => 1,    
                "minitems" => 0,
                "maxitems" => 1,    
                "wizards" => Array(
                    "_PADDING" => 2,
                    "_VERTICAL" => 1,
                    "add" => Array(
                        "type" => "script",
                        "title" => "Create new record",
                        "icon" => "add.gif",
                        "params" => Array(
                            "table"=>"be_users",
                            "pid" => "###SITEROOT###",
                            "setValue" => "prepend"
                        ),
                        "script" => "wizard_add.php",
                    ),
                    "list" => Array(
                        "type" => "script",
                        "title" => "List",
                        "icon" => "list.gif",
                        "params" => Array(
                            "table"=>"be_users",
                            "pid" => "###SITEROOT###",
                        ),
                        "script" => "wizard_list.php",
                    ),
                    "edit" => Array(
                        "type" => "popup",
                        "title" => "Edit",
                        "script" => "wizard_edit.php",
                        "popup_onlyOpenIfSelected" => 1,
                        "icon" => "edit2.gif",
                        "JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
                    ),
                ),
            )
        ),
		"pages" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:intera_callforpapers/locallang_db.xml:tx_interacallforpapers_permess.pages",        
            "config" => Array (
                "type" => "group",    
                "internal_type" => "db",    
                "allowed" => "pages",    
                "size" => 15,    
                "minitems" => 0,
                "maxitems" => 1000,
            )
        ),
    ),
    "types" => Array (
        "0" => Array("showitem" => "votername;;;;1-1-1, pages")
    ),
    "palettes" => Array (
        "1" => Array("showitem" => "")
    )
);


?>