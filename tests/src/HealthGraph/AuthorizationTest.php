<?php

namespace HealthGraph;


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-11-29 at 20:49:08.
 */
class AuthorizationTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Authorization
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->object = new Authorization;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    
  }

  /**
   * @covers HealthGraph\Authorization::getAuthorizationButton
   */
  public function testGetAuthorizationButton() {
    $button = $this->object->getAuthorizationButton($GLOBALS['client_id'], 'redirect');
    $this->assertEquals("https://runkeeper.com/apps/authorize?client_id=" . $GLOBALS['client_id'] . "&response_type=code&redirect_uri=redirect", $button['link']);
    $this->assertEquals("http://static1.runkeeper.com/images/assets/connect-blue-white-200x38.png", $button['image']);
    $this->assertContains('<a href', $button['html']);
    $this->assertContains('<img', $button['html']);
    
    $button = $this->object->getAuthorizationButton($GLOBALS['client_id'], 'redirect', 'login', 'black', 'black', 300);
    $this->assertEquals("https://runkeeper.com/apps/authorize?client_id=" . $GLOBALS['client_id'] . "&response_type=code&redirect_uri=redirect", $button['link']);
    $this->assertEquals("http://static1.runkeeper.com/images/assets/login-black-black-300x57.png", $button['image']);
    
    $button = $this->object->getAuthorizationButton($GLOBALS['client_id'], 'redirect', 'login', 'grey', 'white', 600);
    $this->assertEquals("https://runkeeper.com/apps/authorize?client_id=" . $GLOBALS['client_id'] . "&response_type=code&redirect_uri=redirect", $button['link']);
    $this->assertEquals("http://static1.runkeeper.com/images/assets/login-grey-white-600x114.png", $button['image']);
    
    $button = $this->object->getAuthorizationButton($GLOBALS['client_id'], 'redirect', 'foo', 'foo', 'foo', 'foo');
    $this->assertEquals("https://runkeeper.com/apps/authorize?client_id=" . $GLOBALS['client_id'] . "&response_type=code&redirect_uri=redirect", $button['link']);
    $this->assertEquals("http://static1.runkeeper.com/images/assets/connect-blue-white-200x38.png", $button['image']);
  }

  /**
   * @covers HealthGraph\Authorization::getAuthorizationLink
   */
  public function testGetAuthorizationLink() {
    $link = $this->object->getAuthorizationLink($GLOBALS['client_id'], 'redirect');
    $this->assertEquals("https://runkeeper.com/apps/authorize?client_id=" . $GLOBALS['client_id'] . "&response_type=code&redirect_uri=redirect", $link);
    
    $link = $this->object->getAuthorizationLink('example', 'example', 'http://example.com');
    $this->assertEquals("http://example.com?client_id=example&response_type=code&redirect_uri=example", $link);
  }

  /**
   * @covers HealthGraph\Authorization::authorize
   * @todo   Implement better testAuthorize().
   */
  public function testAuthorize() {
    $authorization_code = 'xxxxxxxxxxxxxxxxxxx';
    $token = $this->object->authorize($authorization_code, $GLOBALS['client_id'], $GLOBALS['client_secret'], $GLOBALS['redirect_url']);
    $this->assertFalse($token);
  }

  /**
   * @covers HealthGraph\Authorization::deauthorize
   * @todo   Implement better testDeauthorize().
   */
  public function testDeauthorize() {
    $access_token = 'yyyyyyyyyyyyyyyyyyyyy';
    $result = $this->object->deauthorize($access_token);
    $this->assertFalse($result);
  }

}
