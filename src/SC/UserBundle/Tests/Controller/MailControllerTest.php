<?php

// src/UserBundle/Tests/Controller/MailControllerTest.php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailControllerTest extends WebTestCase
{
    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Hello Email', $message->getSubject());
        $this->assertEquals('send@example.com', key($message->getFrom()));
        $this->assertEquals('recipient@example.com', key($message->getTo()));
        //$this->assertEquals('You should see me from the profiler!', $message->getBody()
        //);
    }
}
