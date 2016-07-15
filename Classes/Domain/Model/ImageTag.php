<?php
namespace Pits\PitsTagcloud\Domain\Model;

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
 * ImageTag
 */
class ImageTag extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image = null;
    
    /**
     * width
     *
     * @var int
     */
    protected $width = 0;
    
    /**
     * height
     *
     * @var int
     */
    protected $height = 0;
    
    /**
     * linkUrl
     *
     * @var string
     */
    protected $linkUrl = '';
    
    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }
    
    /**
     * Returns the width
     *
     * @return int $width
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * Sets the width
     *
     * @param int $width
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
    
    /**
     * Returns the height
     *
     * @return int $height
     */
    public function getHeight()
    {
        return $this->height;
    }
    
    /**
     * Sets the height
     *
     * @param int $height
     * @return void
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
    
    /**
     * Returns the linkUrl
     *
     * @return string $linkUrl
     */
    public function getLinkUrl()
    {
        return $this->linkUrl;
    }
    
    /**
     * Sets the linkUrl
     *
     * @param string $linkUrl
     * @return void
     */
    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;
    }

}