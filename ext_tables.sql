CREATE TABLE tt_content
(
	tx_typo3styleguide_technicalheadlinetag varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'h2',
	tx_typo3styleguide_colors int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_fonts int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_icons_path varchar(255) DEFAULT '' NOT NULL,
	tx_typo3styleguide_images int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_tableofcontents_layout varchar(10) DEFAULT 'list' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_color
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	color varchar(7) DEFAULT '' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_font
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	font varchar(255) DEFAULT '' NOT NULL,
	font_weight varchar(30) DEFAULT '' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_image
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	path varchar(255) DEFAULT '' NOT NULL,
	caption varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE pages
(
	tx_typo3styleguide_ctype_icon varchar(255) DEFAULT '' NOT NULL,
);
