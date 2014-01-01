<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group comment
 */
class CommentTest extends BaseIntegrationTest
{

    public function prepareTestItem()
    {
        $command = self::$client->getCommand('NewFitnessActivity', array(
            "type" => "Running",
            "start_time" => date(DATE_RFC1123),
            "duration" => rand(600, 10000),
            "total_distance" => (rand(10000, 200000) / 10),
            "notes" => "Unit test"
        ));
        $result = $command->execute();
        self::$creations[] = $result->get('location');

        $command = self::$client->getCommand('GetFitnessActivity', array('uri' => $result->get('location')));
        $result = $command->execute();

        return $result->get('comments');
    }

    public function testNewComment()
    {
        $uri = $this->prepareTestItem();
        $command = self::$client->getCommand('NewComment', array(
            'uri' => $uri,
            'comment' => 'Test comment'
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('uri'));
        $this->assertCount(1, $result->get('comments'));

        return $result->get('uri'); ;
    }

    /**
     * @depends testNewComment
     */
    public function testAnotherComment($uri)
    {
        $command = self::$client->getCommand('NewComment', array(
            'uri' => $uri,
            'comment' => 'Another test comment'
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('uri'));
        $this->assertCount(2, $result->get('comments'));

        return $result->get('uri'); ;
    }

    /**
     * @depends testNewComment
     */
    public function testGetCommentThread($uri)
    {
        $command = self::$client->getCommand('GetCommentThread', array('uri' => $uri));
        $result = $command->execute();

        $this->assertNotNull($result->get('uri'));
        $this->assertCount(2, $result->get('comments'));
    }

}
