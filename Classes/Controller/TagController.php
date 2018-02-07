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
 * TagController
 */
class TagController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * tagRepository
     *
     * @var \Pits\PitsTagcloud\Domain\Repository\TagRepository
     * @inject
     */
    protected $tagRepository = NULL;
    
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
        $tags = $this->tagRepository->findTags($settings, $storagePid);
        $this->view->assign('tags', $tags);
        $style = $this->tagRepository->getStyle($settings, $contentId);
        $this->view->assign('styles', $style);
    }
    
    /**
     * action show
     *
     * @param \Pits\PitsTagcloud\Domain\Model\Tag $tag
     * @return void
     */
    public function showAction()
    {
        $this->contentObj = $this->configurationManager->getContentObject();
        $contentId = $this->contentObj->data['uid'];
        $storagePid = $this->contentObj->data['pages'];
        $settings = $this->settings;
        $tags = $this->tagRepository->findTagsFromTable($settings, $storagePid);
        $excludeIds = $settings['tabletags'][$settings['referenceTable']];
        $pid_arr = explode(',', trim($excludeIds['excludeRecords']));
        $i = 0;
        $numRows = count($tags);
        foreach ($tags as $tag) {
            if ($i + 1 != $numRows) {
                $j = 0;
                foreach ($tag as $key) {
                    $uid = isset($key['uid']) ? $key['uid'] : NULL;
                    if (in_array($uid, $pid_arr)) {
                        unset($tags[$i][$j]);
                    }
                    $j++;
                }
            }
            $i++;
        }
        $this->view->assign('tags', $tags);
        $style = $this->tagRepository->getStyle($settings, $contentId);
        $this->view->assign('styles', $style);
    }

}