<?php

namespace MinuThomas\PitsTagcloud\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Minu Thomas <minu.ts@pitsolutions.com>, PIT Solutions Pvt. Ltd, India
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \MinuThomas\PitsTagcloud\Domain\Model\Tag.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Minu Thomas <minu.ts@pitsolutions.com>
 */
class TagTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \MinuThomas\PitsTagcloud\Domain\Model\Tag
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \MinuThomas\PitsTagcloud\Domain\Model\Tag();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getLinkReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getLink()
		);
	}

	/**
	 * @test
	 */
	public function setLinkForStringSetsLink()
	{
		$this->subject->setLink('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'link',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinkurlReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getLinkurl()
		);
	}

	/**
	 * @test
	 */
	public function setLinkurlForStringSetsLinkurl()
	{
		$this->subject->setLinkurl('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'linkurl',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinksizeReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setLinksizeForIntSetsLinksize()
	{	}
}
