#
# Table structure for table 'tx_interacallforpapers_abstract'
#
CREATE TABLE tx_interacallforpapers_abstract (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	papertitle tinytext NOT NULL,
	author tinytext NOT NULL,
	position tinytext NOT NULL,
	companyaffiliation tinytext NOT NULL,
	mailingaddress text NOT NULL,
	city tinytext NOT NULL,
	postcode tinytext NOT NULL,
	country tinytext NOT NULL,
	tel tinytext NOT NULL,
	fax tinytext NOT NULL,
	coauthors text NOT NULL,
	email tinytext NOT NULL,
	publication int(11) DEFAULT '0' NOT NULL,
	copyrightclearance int(11) DEFAULT '0' NOT NULL,
	primarycategory int(11) DEFAULT '0' NOT NULL,
	secondarycategory int(11) DEFAULT '0' NOT NULL,
	abstract text NOT NULL,
	experience text NOT NULL,
	technicalcontribution text NOT NULL,
	economiccontribution text NOT NULL,
	innovation text NOT NULL,
	cv text NOT NULL,
	placedateofpublication tinytext NOT NULL,
	slot tinyint(4) DEFAULT '0' NOT NULL,
	protocolnumber tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_interacallforpapers_vote'
#
CREATE TABLE tx_interacallforpapers_vote (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	closed tinyint(4) DEFAULT '0' NOT NULL,
	votername int(11) DEFAULT '0' NOT NULL,
	abstract int(11) DEFAULT '0' NOT NULL,
	vote int(11) DEFAULT '0' NOT NULL,
	slottime tinyint(4) DEFAULT '0' NOT NULL,
	slottimeadmin tinyint(4) DEFAULT '0' NOT NULL,
	note text NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_interacallforpapers_permess'
#
CREATE TABLE tx_interacallforpapers_permess (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
	 deleted tinyint(4) NOT NULL default '0',
    hidden tinyint(4) NOT NULL default '0',
    votername int(11) DEFAULT '0' NOT NULL,
	 pages blob NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);