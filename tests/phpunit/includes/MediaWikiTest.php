<?php
/**
 * Test class for MediaWiki.
 * Generated by PHPUnit on 2011-02-06 at 11:41:23.
 */
class MediaWikiTest extends MediaWikiTestCase {
	/**
	 * @var MediaWiki
	 */
	protected $object;

	protected function setUp() {
		$this->object = new MediaWiki;
	}

	protected function tearDown() {
		$this->object = NULL;
	}

	/**
	 * Test case insentiveness for get / set
	 */
	public function testSetGetValKeyInsentiveness() {

		// set with lower case key 
		$value = 'SomeValue';
		$this->object->setVal( 'foobar', $value );

		$this->assertEquals(
			$this->object->getVal( 'foobar' ), 'SomeValue',
			'lower case key set, getting lower case key'
		);
		$this->assertEquals(
			$this->object->getVal( 'FOOBAR' ), 'SomeValue',
			'lower case key set, getting upper case key'
		);

		// set with Mixed case key 
		$value = 'SomeValue2';
		$this->object->setVal( 'FooBar', $value );

		$this->assertEquals(
			$this->object->getVal( 'foobar' ), 'SomeValue2',
			'mixed case key set, getting lower case key'
		);
		$this->assertEquals(
			$this->object->getVal( 'FOOBAR' ), 'SomeValue2',
			'mixed case key set, getting upper case key'
		);
	}

	public function testGetValWithDefault() {
		$this->assertEmpty(
			$this->object->getVal( 'NonExistent' ),
			'Non existent key return empty string'
		);
		$this->assertEquals(
			$this->object->getVal( 'NonExistent2', 'Default Value' ), 'Default Value',
			'Non existent key with default given, should give default'
		);
	}

	/**
	 * @todo Implement testPerformRequestForTitle().
	 */
	public function testPerformRequestForTitle() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testCheckMaxLag().
	 */
	public function testCheckMaxLag() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testCheckInitialQueries().
	 */
	public function testCheckInitialQueries() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testPreliminaryChecks().
	 */
	public function testPreliminaryChecks() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testHandleSpecialCases().
	 */
	public function testHandleSpecialCases() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testArticleFromTitle().
	 */
	public function testArticleFromTitle() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetAction().
	 */
	public function testGetAction() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testInitializeArticle().
	 */
	public function testInitializeArticle() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testFinalCleanup().
	 */
	public function testFinalCleanup() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testDoJobs().
	 */
	public function testDoJobs() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testRestInPeace().
	 */
	public function testRestInPeace() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testPerformAction().
	 */
	public function testPerformAction() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}
}
