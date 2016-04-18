<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'MinuThomas.' . $_EXTKEY,
	'Tagcloud',
	'Pits Tag cloud Extbase'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Pits Tag Cloud');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pitstagcloud_domain_model_tag', 'EXT:pits_tagcloud/Resources/Private/Language/locallang_csh_tx_pitstagcloud_domain_model_tag.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pitstagcloud_domain_model_tag');

$pluginSignature = str_replace('_','',$_EXTKEY) . '_tagcloud';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]='select_key';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/Flexform/flexform.xml');


 
//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . "Classes/UserFunc/class.tx_addTags.php";


 
include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . "Classes/UserFunc/class.tx_addTags.php");