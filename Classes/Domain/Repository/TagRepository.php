<?php
namespace Pits\PitsTagcloud\Domain\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Minu Thomas <minu.ts@pitsolutions.com>, PIT Solutions Pvt. Ltd, India
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * The repository for Tags
 */
class TagRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    public $resultArray = [];
    public $styleArray = [];
    public $limit;
    public $words;
    public $link = [];
    
    function findTags($settings, $storagePid)
    {
        //The tags selected from the list in flexform
        if (isset($settings['selectedTags']) && $settings['selectedTags'] != '')
        $tagIds = $settings['selectedTags'];
        
        //To find the limit value
        if (isset($settings['numberTags']) && $settings['numberTags'] != '' && is_numeric($settings['numberTags']) && $settings['numberTags'] > 0) {
            $limit = $settings['numberTags'];
        } else {
            $limit = 100;
        }
        
        //Query to execute according to random option status
        if (isset($settings['checkboxRandomTags'])&& $settings['checkboxRandomTags'] != '' && is_numeric($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] > 0) {
            if($tagIds!=''){
            $sql = $GLOBALS['TYPO3_DB']->sql_query("select * from  tx_pitstagcloud_domain_model_tag where pid=" . $storagePid . " and deleted = '0' and hidden = '0' and uid IN (" . $tagIds . ") ORDER BY rand() limit " . $limit);
            }
            else{
             $sql = $GLOBALS['TYPO3_DB']->sql_query("select * from  tx_pitstagcloud_domain_model_tag where pid=" . $storagePid . " and deleted = '0' and hidden = '0' ORDER BY rand() limit " . $limit);
            }
        } else {
            if($tagIds!=''){
            $sql = $GLOBALS['TYPO3_DB']->sql_query("select * from  tx_pitstagcloud_domain_model_tag where pid=" . $storagePid . " and deleted = '0' and hidden = '0' and uid IN (" . $tagIds . ") limit " . $limit);
            }
            else{
            $sql = $GLOBALS['TYPO3_DB']->sql_query("select * from  tx_pitstagcloud_domain_model_tag where pid=" . $storagePid . " and deleted = '0' and hidden = '0' limit " . $limit);   
            }   
        }
        
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($sql)) {
            $resultArray[] = $row;
        }
        
        return $resultArray;
        
    }
            
    
    function findTagsFromTable($settings, $storagePid)
    {
        // To find the limit value
        if (isset($settings['numberTags']) && $settings['numberTags'] != '' && is_numeric($settings['numberTags']) && $settings['numberTags'] > 0) {
            $limit = $settings['numberTags'];
        } else {
            $limit = 100;
        }
        
        //To fetch extended where clause from typoscript
        $andwhere = $settings['tabletags'][$settings['referenceTable']]['andWhere'];
        $where = ($andwhere)? ' and '.$andwhere : '';

        //To fetch the link configuration frm typoscript
        $this->link = $settings['tabletags'][$settings['referenceTable']]['links'];
        if ($this->link['parameter'] == '')
            $this->link['parameter'] = 'pid';

        $parameter = $this->link['parameter'];
        // All chosen fields of the table
        $table = $settings['referenceTable'];
        $fields = explode(',', $settings['referenceFields']);

        //To get the max word limit for a tag
        if (isset($settings['numberWords']) && ($settings['numberWords'] > 0) && ($settings['numberWords'] != ''))
            $words = $settings['numberWords'] + 1;
        
        //Query to be executed according to the random option status
        if (isset($settings['checkboxRandomTags'])&& ($settings['checkboxRandomTags'] > 0) && ($settings['checkboxRandomTags'] != '')) {        
       
            //To check each field one by one with the count of occurances as weight
            foreach ($fields as $fieldname) {
                if(($fieldname!='')||($fieldname!=0)){
                    $sql = $GLOBALS['TYPO3_DB']->sql_query("select uid, count(" . $fieldname . ") as fieldCount, " . $fieldname . " as field, " . $parameter . " as link from  " . $table . " where deleted = '0' and hidden = '0' ".$where." GROUP BY " . $fieldname . " ORDER BY rand()  limit " . $limit);
                    $res= $this->getValues($sql, $words, $settings['referenceTable']);
                    if(isset($res)){
                        $resultArray[]=$res;
                    }
                }
            }
        } else {
            //To check each field one by one with the count of occurances as weight
            foreach ($fields as $fieldname) {  
               if(($fieldname!='')||($fieldname!=0)){         
                    $sql = $GLOBALS['TYPO3_DB']->sql_query("select uid, count(" . $fieldname . ") as fieldCount, " . $fieldname . "  as field , " . $parameter . " as link from  " . $table . " where deleted = '0' and hidden = '0' ".$where." GROUP BY " . $fieldname . " limit " . $limit);
                    $res = $this->getValues($sql, $words, $settings['referenceTable']);
                    if(isset($res)){

                        $resultArray[]=$res;
                    }
                }
            }
        }
      
        if (isset($settings['targetPage']) && $settings['targetPage']!=' ')
            $resultArray['target'] = $settings['targetPage'];
        else
            $resultArray['target'] = '';

        return $resultArray;
    }
    
    
    function getValues($sql, $words, $table)
    {   
        //To fetch the values and store to array
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($sql)) {
            if (($row['field'] != '0') && ($row['field'] != '')) {
               if (($this->link['parameter']=='pid')||($table=='pages')) { 
                $llink='';
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('doktype', 'pages', 'uid='.intval($row['link']));
                while ($r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {         
                    $llink=$r['doktype'];
                }

                $result['taglink'] = ($llink!="1")?intval($GLOBALS['TSFE']->id):$result['taglink'] = $row['link'];
                }
                else{
                     $result['taglink'] = $row['link'];
                }
                     
                //To calculate pixel size                
                $result['size'] = ($row['fieldCount'] < 10) ? (10 + $row['fieldCount']) : (($row['fieldCount'] > 100) ? 100 : $row['fieldCount']);
                
                //To fetch tag with max word limit only
                if (str_word_count($row['field']) >= $words) {
                    $str           = explode(' ', $row['field'], $words);
                    $result['tag'] = str_replace($str[$words - 1], '', $row['field']);
                } else {
                    $result['tag'] = $row['field'];
                }
               
                //To check for additional parameters to be inserted to URL
                if (isset($this->link['additionalParams'])) {

                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->link['attributeValue'], $table, 'uid='.intval($row['uid']));
                    while ($r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {         
                    $attributeVal=$r[$this->link['attributeValue']];
                    }

                    $result['additionalParams'] = array(
                        ($this->link['additionalParams']) => $attributeVal);
                }
                $result['uid'] = $row['uid'];
                
                $result2[] = $result;
            }
        }
        return $result2;
    }
    
    
    function getStyle($settings)
    {
        // All chosen style options and its values added to the script code
        $textColor     = ($settings['inputTextColor'] != '') ? $settings['inputTextColor'] : '#ff0000';
        $outlineColour = ($settings['outlineColour'] != '') ? $settings['outlineColour'] : '#000000';
        $maxSpeed      = ($settings['inputRotationSpeed'] != '') ? $settings['inputRotationSpeed'] : '0.09';
        $bgColour      = ($settings['inputBgColor'] != '') ? $settings['inputBgColor'] : '#ffffff';
        $canvasWidth   = ($settings['inputWidth'] != '') ? $settings['inputWidth'] : '300';
        $canvasHeight  = ($settings['inputHeight'] != '') ? $settings['inputHeight'] : '300';
        $canvasbg      = ($settings['canvasBg'] != '') ? $settings['canvasBg'] : '#ffffff';
        
        $Scriptcontent = "      
    window.onload = function() {
      try {
        TagCanvas.Start('myCanvas','weightTags',{
          textColour: '" . $textColor . "',
          outlineColour: '" . $outlineColour . "',
          reverse: true,
          depth: 0.8,
          maxSpeed: " . $maxSpeed . ",
          bgColour: '" . $bgColour . "',
          weight: true,
          hideTags: true
        });
      } catch(e) {
// something went wrong, hide the canvas container
        document.getElementById('myCanvasContainer').style.display = 'none';
      }
    };";
        
        $styleArray['script']       = $Scriptcontent;
        $styleArray['canvasHeight'] = $canvasHeight;
        $styleArray['canvasWidth']  = $canvasWidth;
        $styleArray['canvasbg']     = $canvasbg;
        
        return $styleArray;
    }
    
    
}