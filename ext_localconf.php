<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Pits.' . $_EXTKEY,
	'Tagcloud',
	array(
		'Tag' => 'list, show',
		'ImageTag' => 'list',
		
	),
	// non-cacheable actions
	array(
		'Tag' => 'list, show',
		'ImageTag' => 'list',
		
	)
);
