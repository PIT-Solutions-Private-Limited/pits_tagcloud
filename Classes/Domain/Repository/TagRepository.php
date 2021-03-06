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

    public $resultArray = array();
    
    public $styleArray = array();
    
    public $limit = null;
    
    public $words = null;
    
    public $link = array();
    
    /**
     * @param $settings
     * @param $storagePid
     */
    function findTags($settings, $storagePid)
    {
        //The tags selected from the list in flexform
        if (isset($settings['selectedTags']) && $settings['selectedTags'] != '') {
            $tagIds = $settings['selectedTags'];
        }
        $tagIds = explode(',', $tagIds);
        //To find the limit value
        if (isset($settings['numberTags']) && $settings['numberTags'] != '' && is_numeric($settings['numberTags']) && $settings['numberTags'] > 0) {
            $limit = intval($settings['numberTags']);
        } else {
            $limit = 100;
        }
        $storagePageIds = explode(',', $storagePid);
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($storagePageIds);
        if (!empty($tagIds) && $tagIds[0] != '') {
            $query->matching($query->in('uid', $tagIds));
        }
        if (isset($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] != '' && is_numeric($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] > 0) {
            $result = $query->execute();
            $resultCount = count($result);
            $limit = $limit > $resultCount ? $resultCount : $limit;
            $resultRandom = array_rand($result->toArray(), $limit);
            if ($resultCount > 1) {
                foreach ($resultRandom as $key) {
                    $resultArray[] = $result[$key];
                }
            } else {
                $resultArray[] = $result[$resultRandom];
            }
            return $resultArray;
        } else {
            $query->setLimit($limit);
            return $query->execute();
        }
    }
    
    /**
     * @param $settings
     * @param $storagePid
     */
    function findTagsFromTable($settings, $storagePid)
    {
        $storagePageIds = explode(',', $storagePid);
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($storagePageIds);
        // To find the limit value
        if (isset($settings['numberTags']) && $settings['numberTags'] != '' && is_numeric($settings['numberTags']) && $settings['numberTags'] > 0) {
            $limit = $settings['numberTags'];
        } else {
            $limit = 100;
        }
        //To fetch extended where clause from typoscript
        $andwhere = $settings['tabletags'][$settings['referenceTable']]['andWhere'];
        $where = $andwhere ? ' and ' . $andwhere : '';
        //To fetch the link configuration frm typoscript
        $this->link = $settings['tabletags'][$settings['referenceTable']]['links'];
        if ($this->link['parameter'] == '') {
            $this->link['parameter'] = 'pid';
        }
        $parameter = $this->link['parameter'];
        // All chosen fields of the table
        $table = $settings['referenceTable'];
        $fields = explode(',', $settings['referenceFields']);
        //To get the max word limit for a tag
        if (isset($settings['numberWords']) && $settings['numberWords'] > 0 && $settings['numberWords'] != '') {
            $words = $settings['numberWords'] + 1;
        }
        //Query to be executed according to the random option status
        if (isset($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] > 0 && $settings['checkboxRandomTags'] != '') {
            //To check each field one by one with the count of occurances as weight
            foreach ($fields as $fieldname) {
                if ($fieldname != '' || $fieldname != 0) {
                    $query->statement('select uid, count(' . $fieldname . ') as fieldCount, ' . $fieldname . ' as field, ' . $parameter . ' as link from  ' . $table . ' where deleted = \'0\' and hidden = \'0\' ' . $where . ' GROUP BY ' . $fieldname . ' ORDER BY rand() limit ' . $limit);
                    $sql =  $query->execute(1);
                    $res = $this->getValues($sql, $words, $settings['referenceTable']);
                    if (isset($res)) {
                        $resultArray[] = $res;
                    }
                }
            }
        } else {
            //To check each field one by one with the count of occurances as weight
            foreach ($fields as $fieldname) {
                if ($fieldname != '' || $fieldname != 0) {
                    $query->statement('select uid, count(' . $fieldname . ') as fieldCount, ' . $fieldname . '  as field , ' . $parameter . ' as link from  ' . $table . ' where deleted = \'0\' and hidden = \'0\' ' . $where . ' GROUP BY ' . $fieldname . ' limit ' . $limit);
                    $sql =  $query->execute(1);
                    $res = $this->getValues($sql, $words, $settings['referenceTable']);
                    if (isset($res)) {
                        $resultArray[] = $res;
                    }
                }
            }
        }
        if (isset($settings['targetPage']) && $settings['targetPage'] != ' ') {
            $resultArray['target'] = $settings['targetPage'];
        } else {
            $resultArray['target'] = '';
        }
        return $resultArray;
    }
    
    /**
     * @param $sql
     * @param $words
     * @param $table
     */
    function getValues($sql, $words, $table)
    {   
        $query = $this->createQuery();
        //To fetch the values and store to array
        foreach ($sql as $row) {
            if ($row['field'] != '0' && $row['field'] != '') {
                if ($this->link['parameter'] == 'pid' || $table == 'pages') {
                    $llink = '';
                    $query->statement('select doktype from pages where uid='.intval($row['link']));
                    $res = $query->execute(1);
                    foreach ($res as $r) {
                        $llink = $r['doktype'];
                    }
                    $result['taglink'] = $llink != '1' ? intval($GLOBALS['TSFE']->id) : $row['link'];
                } else {
                    $result['taglink'] = $row['link'];
                }
                //To calculate pixel size
                $result['size'] = $row['fieldCount'] < 10 ? 10 + $row['fieldCount'] : ($row['fieldCount'] > 100 ? 100 : $row['fieldCount']);
                //To fetch tag with max word limit only
                if (str_word_count($row['field']) >= $words) {
                    $str = explode(' ', $row['field'], $words);
                    $result['tag'] = str_replace($str[$words - 1], '', $row['field']);
                } else {
                    $result['tag'] = $row['field'];
                }
                //To check for additional parameters to be inserted to URL
                if (isset($this->link['additionalParams'])) {
                    $query->statement('select '.$this->link['attributeValue'].' from '.$table.' where uid = '. intval($row['uid']));
                    $res = $query->execute(1);
                    foreach ($res as $r) {
                        $attributeVal = $r[$this->link['attributeValue']];
                    }
                    $result['additionalParams'] = array(
                        $this->link['additionalParams'] => $attributeVal
                    );
                }
                $result['uid'] = $row['uid'];
                $resultFinal[] = $result;
            }
        }
        return $resultFinal;
    }
    
    /**
    * @param $settings
    * @param $contentID
    */
    function getStyle($settings, $contentId)
    {
        $element_ID = 'myCanvas_'.$contentId;
        $tag_ID = 'tags_'.$contentId;
        $container_ID = "myCanvasContainer_".$contentId;
        // All chosen style options and its values added to the script code
        $textColor = $settings['inputTextColor'] != '' ? $settings['inputTextColor'] : '#ff0000';
        $outlineColour = $settings['outlineColour'] != '' ? $settings['outlineColour'] : '#000000';
        $maxSpeed = $settings['inputRotationSpeed'] != '' ? $settings['inputRotationSpeed'] : '0.09';
        $bgColour = $settings['inputBgColor'] != '' ? $settings['inputBgColor'] : '#ffffff';
        $canvasWidth = $settings['inputWidth'] != '' ? $settings['inputWidth'] : '300';
        $canvasHeight = $settings['inputHeight'] != '' ? $settings['inputHeight'] : '300';
        $canvasbg = $settings['canvasBg'] != '' ? $settings['canvasBg'] : '#ffffff';
        $Scriptcontent = '      
        document.addEventListener("DOMContentLoaded", function(event) { 
            try {
            TagCanvas.Start(\'' . $element_ID . '\',\'' . $tag_ID . '\',{
              textColour: \'' . $textColor . '\',
              outlineColour: \'' . $outlineColour . '\',
              reverse: true,
              depth: 0.8,
              maxSpeed: ' . $maxSpeed . ',
              bgColour: \'' . $bgColour . '\',
              weight: true,
              hideTags: true,
              shape: "sphere"
            });
           
          } catch(e) {
        // something went wrong, hide the canvas container
            document.getElementById(\'' . $container_ID . '\').style.display = \'none\';
          }
        });';
        $styleArray['element_ID'] = $element_ID;
        $styleArray['tag_ID'] = $tag_ID;
        $styleArray['container_ID'] = $container_ID;
        $styleArray['script'] = $Scriptcontent;
        $styleArray['canvasHeight'] = $canvasHeight;
        $styleArray['canvasWidth'] = $canvasWidth;
        $styleArray['canvasbg'] = $canvasbg;
        return $styleArray;
    }

}