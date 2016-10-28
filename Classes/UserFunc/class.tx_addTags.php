<?php

class tx_addTags {
    
    /**
     * @var array Holds the table names as array
     */
    public $tableArr = [];

    /**
     * This method reads the extension configuration and adds table names to the list of items
     *
     * @param   array   $config: content element configuration
     *
     * @return  array   content element configuration with dynamically added items
     */
    public function getTables(&$config)
    {
        global $TCA;
        $extensionConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pits_tagcloud']);
        $tableArr = [];
        $tableArr = $this->tableList($extensionConf);       
        $elements = array();
        if(isset($extensionConf['tableList'])&&($extensionConf['tableList'])!='')
            foreach ($tableArr as $tableKey) {
                $tname=$tableKey['tablename'][0];
                $elements[] = array($GLOBALS['LANG']->sL($TCA[$tname]['ctrl']['title']), $tname);
            }
        $config['items'] = array_merge($config['items'], $elements);
        return $config;
    }

    public function tableList($extensionConf)
    {   
        $tableArr = [];
        $tableList = explode(' | ',trim($extensionConf['tableList']));
        foreach ($tableList as $tableD) {
            $table['tablename'] = explode(':', $tableD, -1);
            $strTable = preg_split('/[:]/', $tableD);
            $table['fields'] = explode(",",trim($strTable[1]));
            $tableArr[] = $table;
        }
        return $tableArr;
    }

    /**
     * This method reads the fields for a given table and adds all table fields to the list of items
     *
     * @param   array   $config: content element configuration
     *
     * @return  array   content element configuration with dynamically added items
     */
    public function getFields($config)
    {
        global $TCA;
        $ver = \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version();
        $table = '';

        if ($ver < '7.0.0') {
            $flexform = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($config['row']['pi_flexform']);
            $table = $flexform['data']['display']['lDEF']['settings.referenceTable']['vDEF'];

        } else {
            if (!empty($config['row']['settings.referenceTable'])) {
                $table = $config['row']['settings.referenceTable'][0];
            }
        }

        if (!empty($table)) {
            $elements = array();
            $extensionConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pits_tagcloud']);
            $tableArr = [];
            $tableArr = $this->tableList($extensionConf);       
                foreach ($tableArr as $tableKey) {
                    if ($tableKey['tablename'][0] == $table) { 
                        foreach ($tableKey['fields'] as $key) {
                            $keyLabel = $TCA[$table]['columns'][$key]['label'];
                            $elements[] = array($GLOBALS['LANG']->sL($keyLabel), $key);
                        }
                    break;
                    }
                }
            $config['items'] = array_merge($config['items'], $elements);
        }
        return $config;
    }

}
?>