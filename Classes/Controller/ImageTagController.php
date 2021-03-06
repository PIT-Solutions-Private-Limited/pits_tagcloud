<?php
namespace Pits\PitsTagcloud\Controller;
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
 * ImageTagController
 */
class ImageTagController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * imageTagRepository
     *
     * @var \Pits\PitsTagcloud\Domain\Repository\ImageTagRepository
     * @inject
     */
    protected $imageTagRepository = NULL;
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $this->contentObj = $this->configurationManager->getContentObject();
        $contentId = $this->contentObj->data['uid'];
        $storagePid = $this->contentObj->data['pages'];
        $settings = $this->settings;
        $tags = $this->imageTagRepository->findImageTags($settings, $storagePid);
        foreach ($tags as $key => $tag) {
            if($tag['image']==1){
                $uid_local = $this->imageTagRepository->getImageUidLocal($tag['uid'], 'tx_pitstagcloud_domain_model_imagetag');
                $tags[$key]['imagefile']=$this->imageTagRepository->getImageFile($uid_local);
                
            }
        }
       
        $this->view->assign('imageTags', $tags);
        $style = $this->imageTagRepository->getStyle($settings, $contentId);
        $this->view->assign('styles', $style);
        $this->view->assign('content_ID',$contentId);
    }
    
}