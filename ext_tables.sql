CREATE TABLE tt_content
(
	tx_typo3styleguide_technicalheadlinetag varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'h2',
	tx_typo3styleguide_colors int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_colors_layout varchar(10) DEFAULT 'swatches' NOT NULL,
	tx_typo3styleguide_colors_contrast_light varchar(7) DEFAULT '#FFFFFF' NOT NULL,
	tx_typo3styleguide_colors_contrast_dark varchar(7) DEFAULT '#000000' NOT NULL,
	tx_typo3styleguide_fonts int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_fonts_layout varchar(10) DEFAULT 'specimen' NOT NULL,
	tx_typo3styleguide_fonts_fallback varchar(255) DEFAULT '' NOT NULL,
	tx_typo3styleguide_fonts_sample varchar(255) DEFAULT '' NOT NULL,
	tx_typo3styleguide_fonts_download varchar(1024) DEFAULT '' NOT NULL,
	tx_typo3styleguide_icons_path varchar(255) DEFAULT '' NOT NULL,
	tx_typo3styleguide_icons_exclude varchar(255) DEFAULT '' NOT NULL,
	tx_typo3styleguide_images int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_images_downloads tinyint(4) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_guidelines int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_tableofcontents_layout varchar(10) DEFAULT 'list' NOT NULL,
	tx_typo3styleguide_gallery_images int(11) unsigned DEFAULT 0 NOT NULL,
	tx_typo3styleguide_gallery_columns varchar(2) DEFAULT '4' NOT NULL,
	tx_typo3styleguide_gallery_downloads tinyint(4) unsigned DEFAULT 0 NOT NULL,
);

CREATE TABLE tx_typo3styleguide_color
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	color varchar(7) DEFAULT '' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
	cmyk varchar(50) DEFAULT '' NOT NULL,
	pms varchar(100) DEFAULT '' NOT NULL,
	variable varchar(100) DEFAULT '' NOT NULL,
	role varchar(20) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_font
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	font varchar(255) DEFAULT '' NOT NULL,
	font_weight varchar(30) DEFAULT '' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
	purpose varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_image
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	path varchar(255) DEFAULT '' NOT NULL,
	caption varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_typo3styleguide_guideline
(
	parentid int(11) DEFAULT 0 NOT NULL,
	parenttable varchar(255) DEFAULT '' NOT NULL,
	type varchar(10) DEFAULT 'do' NOT NULL,
	text text,
	path varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE pages
(
	tx_typo3styleguide_ctype_icon varchar(255) DEFAULT '' NOT NULL,
);
