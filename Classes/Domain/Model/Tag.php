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
 * Tag
 */
class Tag extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * link
     *
     * @var string
     */
    protected $link = '';
    
    /**
     * linkurl
     *
     * @var string
     */
    protected $linkurl = '';
    
    /**
     * linksize
     *
     * @var int
     */
    protected $linksize = 0;
    
    /**
     * Returns the link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * Sets the link
     *
     * @param string $link
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
    
    /**
     * Returns the linkurl
     *
     * @return string $linkurl
     */
    public function getLinkurl()
    {
        return $this->linkurl;
    }
    
    /**
     * Sets the linkurl
     *
     * @param string $linkurl
     * @return void
     */
    public function setLinkurl($linkurl)
    {
        $this->linkurl = $linkurl;
    }
    
    /**
     * Returns the linksize
     *
     * @return int $linksize
     */
    public function getLinksize()
    {
        return $this->linksize;
    }
    
    /**
     * Sets the linksize
     *
     * @param int $linksize
     * @return void
     */
    public function setLinksize($linksize)
    {
        $this->linksize = $linksize;
    }

}