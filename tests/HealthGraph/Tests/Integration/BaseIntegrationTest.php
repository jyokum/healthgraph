<?php

namespace HealthGraph\Tests\Integration;

abstract class BaseIntegrationTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected static $client;
    protected static $creations;

    public static function setUpBeforeClass()
    {
        // An array of URIs that are created during testing
        self::$creations = array();
    }

    protected function setUp()
    {
        // We're only going to create and prime the client once
        if (!isset(self::$client)) {
            self::$client = $this->getServiceBuilder()->get('client');
            self::$client->getUser(array(
                'access_token' => $GLOBALS['access_token'],
                'token_type' => $GLOBALS['token_type'],
            ));
        }
    }

    public static function tearDownAfterClass()
    {
        // Make sure everything that was created gets destroyed
        foreach (self::$creations as $uri) {
            try {
                $command = self::$client->getCommand('abstract.delete', array('uri' => $uri));
                $command->execute();
            } catch (\Guzzle\Http\Exception\BadResponseException $e) {
                $status = $e->getResponse()->getStatusCode();
                switch ($status) {
                    case 404:
                    case 500:
                        // These errors are expected if the item has already been deleted
                        break;

                    default:
                        throw $e;
                }
            }
        }
    }

}
