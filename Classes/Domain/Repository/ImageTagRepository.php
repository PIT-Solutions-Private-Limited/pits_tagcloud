<?php
namespace Pits\PitsTagcloud\Domain\Repository;

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
 * The repository for ImageTags
 */
class ImageTagRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{



    public $resultArray = array();
    
    public $styleArray = array();
    
    public $limit = null;
    
 
    /**
     * @param $settings
     * @param $storagePid
     */
    function findImageTags($settings, $storagePid)
    {
        //The tags selected from the list in flexform
        if (isset($settings['selectedImageTags']) && $settings['selectedImageTags'] != '') {
            $tagIds = $settings['selectedImageTags'];
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

        //To fetch random records
        if (isset($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] != '' && is_numeric($settings['checkboxRandomTags']) && $settings['checkboxRandomTags'] > 0) {
            $result = $query->execute(TRUE);
            $resultCount = count($result);
            $limit = $limit > $resultCount ? $resultCount : $limit;
            $resultRandom = array_rand($result, $limit);
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
            return $query->execute(TRUE);
        }
    }
    
	/**
     * @param $settings
     */
    function getStyle($settings)
    {
        // All chosen style options and its values added to the script code
        $textColor = $settings['inputTextColor'] != '' ? $settings['inputTextColor'] : '#ff0000';
        $outlineColour = $settings['outlineColour'] != '' ? $settings['outlineColour'] : '#000000';
        $maxSpeed = $settings['inputRotationSpeed'] != '' ? $settings['inputRotationSpeed'] : '0.09';
        $bgColour = $settings['inputBgColor'] != '' ? $settings['inputBgColor'] : '#ffffff';
        $canvasWidth = $settings['inputWidth'] != '' ? $settings['inputWidth'] : '300';
        $canvasHeight = $settings['inputHeight'] != '' ? $settings['inputHeight'] : '300';
        $canvasbg = $settings['canvasBg'] != '' ? $settings['canvasBg'] : '#ffffff';
        $Scriptcontent = '      
   		window.onload = function() {
      		try {
        		TagCanvas.Start(\'myCanvas\',\'weightTags\',{
          		textColour: \'' . $textColor . '\',
         		outlineColour: \'' . $outlineColour . '\',
          		reverse: true,
          		depth: 0.8,
          		maxSpeed: ' . $maxSpeed . ',
          		bgColour: \'' . $bgColour . '\',
          		weight: true,
          		hideTags: true
        	});
      		} catch(e) {
			// something went wrong, hide the canvas container
       		document.getElementById(\'myCanvasContainer\').style.display = \'none\';
      	}
    	};';
        $styleArray['script'] = $Scriptcontent;
        $styleArray['canvasHeight'] = $canvasHeight;
        $styleArray['canvasWidth'] = $canvasWidth;
        $styleArray['canvasbg'] = $canvasbg;
        return $styleArray;
    }

    
    /**
     * @param $image uid
     * @param $tablename
     */
    function getImageUidLocal($imgUid,$table){
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid_local', 'sys_file_reference', 'uid_foreign='.$imgUid.' AND tablenames = "'.$table.'" AND fieldname="image" AND deleted = 0 AND hidden = 0', '', '');
    	if($res){
       		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
            	if($row['uid_local'])
                	return $row['uid_local'];
        	} 
    	}
  	}

    
    /**
     * @param $image uid_local
     *
     */
    function getImagefile($uid){
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*,concat("fileadmin",sys_file.identifier) as file_name ', 'sys_file', 'uid='.$uid, '', '');
    	if($res){
        	while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
            	if($row['file_name'])
                	return $row['file_name']; 
        	}
    	}
    }


}