<?php
class WikiaPollTest extends WikiaBaseTest {

	public function setUp() {
		$this->setupFile =  dirname(__FILE__) . '/../WikiaPoll_setup.php';
		parent::setUp();
	}

	public function testWikiaPollAjaxCreate() {
		$poll = new WikiaPollAjax;

		$mockTitle = $this->getMock('Title', array('exists'));
		$mockTitle->expects($this->any())
				->method('exists')
				->will($this->returnValue(false));

		$this->proxyClass('Title', $mockTitle, 'newFromText');
		$this->mockGlobalVariable('wgTitle', $mockTitle);

		$mockArticle = $this->getMock('Article', array('doEdit', 'getTitle'), array($mockTitle));
		$mockArticle->expects($this->once())
				->method('doEdit')
				->with($this->equalTo("*One\n*Two\n*Three\n"), $this->anything(), $this->anything(), $this->anything())
				->will($this->returnValue(true));
		$mockArticle->expects($this->once())
				->method('getTitle')
				->will($this->returnValue($mockTitle));

		$this->proxyClass('Article', $mockArticle);

		$wgRequest = $this->getMock('WebRequest', array('getVal', 'getArray'));
		$wgRequest->expects($this->any())
				->method('getArray')
				->with($this->equalTo('answer'))
				->will($this->returnValue(array("One", "Two", "Three")));
		$this->mockGlobalVariable('wgRequest', $wgRequest);

		$this->mockApp();

		$result = $poll->create();

		$this->assertEquals(true, $result["success"], "Create Poll failed. Error: " . (isset($result['error']) ? $result['error'] : 'unknown - error message not set'));

		// Test code path for title==null (invalid question, etc)
		$this->proxyClass('Title', null);

		$result = $poll->create();

		$this->assertEquals(false, $result["success"], 'Create Poll with null title failed. Error' . (isset($result['error']) ? $result['error'] : 'unknown - error message not set'));
		$this->assertContains("Question text is invalid", $result["error"], 'Create Poll with null title failed to return the expected error string');

	}

	public function testWikiaPollAjaxGet() {

		// Second part of test is to see if we can "get" the same poll we created
		$poll = new WikiaPollAjax;

		$wgRequest = $this->getMock('WebRequest', array('getVal'));
		$wgRequest->expects($this->at(0))
				->method('getVal')
				->with($this->equalTo('pollId'))
				->will($this->returnValue(9999));
		$this->mockGlobalVariable('wgRequest', $wgRequest);

		$mockTitle = $this->getMock('Title', array('exists'));
		$mockTitle->expects($this->any())
				->method('exists')
				->will($this->returnValue(true));

		$this->proxyClass('Title', $mockTitle, 'newFromText');

		$mockArticle = $this->getMock('Article', array('getTitle'), array($mockTitle));
		$mockArticle->expects($this->any())
				->method('getTitle')
				->will($this->returnValue($mockTitle));

		$this->proxyClass('Article', $mockArticle, 'newFromID');

		$this->mockApp();

		$result = $poll->get();

		$this->assertType("array", $result, "Get result is an array");
		$this->assertEquals(true, $result["exists"], "Get a poll that exists");

	}

	public function testWikiaPollAjaxUpdate() {
		// Third part of test is to update the poll we've got and see if that works too

		$wgRequest = $this->getMock('WebRequest', array('getInt', 'getArray'));
		$wgRequest->expects($this->any())
				->method('getInt')
				->with($this->equalTo('pollId'))
				->will($this->returnValue(9999));
		$wgRequest->expects($this->any())
				->method('getArray')
				->with($this->equalTo('answer'))
				->will($this->returnValue(array("One", "Two", "Three")));

		$this->mockGlobalVariable('wgRequest', $wgRequest);

		$mockPoll = $this->getMock("WikiaPoll", array('exists'), array(9999));
		$mockPoll->expects($this->once())
				->method("exists")
				->will($this->returnValue(true));
		$this->proxyClass("WikiaPoll", $mockPoll, 'newFromId');

		$mockTitle = $this->getMock('Title');

		$mockArticle = $this->getMock('Article', array('doEdit', 'getTitle'), array($mockTitle));
		$mockArticle->expects($this->any())
				->method('doEdit')
				->with($this->equalTo("*One\n*Two\n*Three\n"), $this->anything(), $this->anything(), $this->anything())
				->will($this->returnValue(true));
		$mockArticle->expects($this->any())
				->method('getTitle')
				->will($this->returnValue($mockTitle));

		$this->proxyClass('Article', $mockArticle, 'newFromID');


		$this->mockApp();
		$poll = new WikiaPollAjax;
		$result = $poll->update();

		$this->assertType("array", $result, "Update result is array");
		$this->assertEquals(true, $result["success"], "Update result is success");

}

	public function testWikiaPollAjaxVote() {
		// Fourth part of test is to register a vote for an item

		$wgRequest = $this->getMock('WebRequest', array('getVal'));
		$wgRequest->expects($this->at(0))
				->method('getVal')
				->with($this->equalTo('pollId'))
				->will($this->returnValue(9999));
		$wgRequest->expects($this->at(1))
				->method('getVal')
				->with($this->equalTo('answer'))
				->will($this->returnValue(2));
		$this->mockGlobalVariable('wgRequest', $wgRequest);

		$mockPoll = $this->getMock("WikiaPoll", array('exists', 'renderEmbedded', 'hasVoted', 'vote'), array(9999));
		$mockPoll->expects($this->any())
				->method("exists")
				->will($this->returnValue(true));
		$mockPoll->expects($this->once())
				->method('renderEmbedded')
				->will($this->returnValue("<HTML>1 person voted</HTML>"));
		$mockPoll->expects($this->once())
				->method("vote");
		$mockPoll->expects($this->once())
				->method("hasVoted")
				->will($this->returnValue(true));
		$this->proxyClass("WikiaPoll", $mockPoll, 'newFromId');

		$mockTitle = $this->getMock('Title', array('getNamespace'));
		$mockTitle->expects($this->any())
				->method('getNamespace')
				->will($this->returnValue(false));

		$this->mockGlobalVariable('wgTitle', $mockTitle);

		$mockArticle = $this->getMock('Article', array(), array($mockTitle));
		$this->proxyClass('Article', $mockArticle, 'newFromID');

		$this->mockApp();
		$poll = new WikiaPollAjax;

		$result = $poll->vote();

		$this->assertType("array", $result, "Vote result is array");
		$this->assertType("string", $result["html"], "Vote result[html] is a block of HTML");
		$this->assertRegExp('/1 person voted/', $result["html"], "Vote result[html] says someone voted");

		// Fifth part of test is for checking to see if the user has voted

		$result = $poll->hasVoted();
		$this->assertType("array", $result, "HasVoted result is array");
		$this->assertEquals(true, $result['hasVoted'], "HasVoted result is true");

	}

	function testDuplicateCreate() {
		$poll = new WikiaPollAjax();

		$mockTitle = $this->getMock('Title', array('exists'));
		$mockTitle->expects($this->any())
				->method('exists')
				->will($this->returnValue(true));

		$this->proxyClass('Title', $mockTitle, 'newFromText');

		$this->mockApp();

		// If the poll exists, it's a dupe
		$result = $poll->create();

		$this->assertType("array", $result, "Create duplicate result is array");
		$this->assertEquals(false, $result["success"], "Create duplicate Poll success flag is false");
		$this->assertType("string", $result["error"], "Create duplicate Poll results in an error");

	}
}
