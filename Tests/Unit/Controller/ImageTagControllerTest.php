<?php
namespace Pits\PitsTagcloud\Tests\Unit\Controller;
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
 * Test case for class Pits\PitsTagcloud\Controller\ImageTagController.
 *
 * @author Minu Thomas <minu.ts@pitsolutions.com>
 */
class ImageTagControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \Pits\PitsTagcloud\Controller\ImageTagController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('Pits\\PitsTagcloud\\Controller\\ImageTagController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllImageTagsFromRepositoryAndAssignsThemToView()
	{

		$allImageTags = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$imageTagRepository = $this->getMock('Pits\\PitsTagcloud\\Domain\\Repository\\ImageTagRepository', array('findAll'), array(), '', FALSE);
		$imageTagRepository->expects($this->once())->method('findAll')->will($this->returnValue($allImageTags));
		$this->inject($this->subject, 'imageTagRepository', $imageTagRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('imageTags', $allImageTags);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenImageTagToView()
	{
		$imageTag = new \Pits\PitsTagcloud\Domain\Model\ImageTag();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('imageTag', $imageTag);

		$this->subject->showAction($imageTag);
	}
}
