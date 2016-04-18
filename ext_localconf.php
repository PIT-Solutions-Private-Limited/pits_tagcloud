<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'MinuThomas.' . $_EXTKEY,
	'Tagcloud',
	array(
		'Tag' => 'list, show',
		
	),
	// non-cacheable actions
	array(
		'Tag' => 'list, show',
		
	)
);
