<?php

namespace OCA\AdminRightSubgranting\Tests\Unit\Controller;

use PHPUnit_Framework_TestCase;

use OCP\AppFramework\Http\TemplateResponse;

use OCA\AdminRightSubgranting\Controller\AuthorizedGroupController;


class PageControllerTest extends PHPUnit_Framework_TestCase {
	private $controller;
	private $userId = 'john';

	public function setUp() {
		$request = $this->getMockBuilder('OCP\IRequest')->getMock();

		$this->controller = new AuthorizedGroupController(
			'adminrightsubgranting', $request, $this->userId
		);
	}

	public function testIndex() {
		$result = $this->controller->index();

		$this->assertEquals('index', $result->getTemplateName());
		$this->assertTrue($result instanceof TemplateResponse);
	}

}
