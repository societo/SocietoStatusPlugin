<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace SocietoPlugin\Societo\StatusPlugin\Tests\Controller;

use Societo\BaseBundle\Test\WebTestCase;

class StatusControllerTest extends WebTestCase
{
    public $em, $token;

    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(array(
            'Societo\BaseBundle\Tests\Fixtures\LoadAccountData',
            'SocietoPlugin\Societo\StatusPlugin\Tests\Fixtures\LoadStatusFormGadgetData',
        ));
    }

    public function testGetPostAction()
    {
        $client = $this->init();
        $client->request('GET', '/status/post/1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPostActionToInvalidGadget()
    {
        $client = $this->init();
        $client->request('POST', '/status/post/2');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPostActionCsrf()
    {
        $client = $this->init();

        $crawler = $client->request('POST', '/status/post/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/The CSRF token is invalid. Please try to resubmit the form/', $client->getResponse()->getContent());
        $this->assertEquals(0, $this->getNumberOfStatus());
    }

    public function testPostActionEmpty()
    {
        $client = $this->init();
        $client->request('POST', '/status/post/1', array('societo_status_plugin_status' => array(
            'body' => '',
            '_token' => $this->token,
        )));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $this->getNumberOfStatus());
    }

    public function testPostActionTooLong()
    {
        $client = $this->init();
        $client->request('POST', '/status/post/1', array('societo_status_plugin_status' => array(
            'body' => 'aaaaaaaaaaa',
            'redirect_to' => '/',
            '_token' => $this->token,
        )));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/Too long value/', $client->getResponse()->getContent());
        $this->assertEquals(0, $this->getNumberOfStatus());
    }

    public function testPostActionSuccess()
    {
        $client = $this->init();
        $client->request('POST', '/status/post/1', array('societo_status_plugin_status' => array(
            'body' => 'a',
            'redirect_to' => '/',
            '_token' => $this->token,
        )));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $this->getNumberOfStatus());
    }

    public function init()
    {
        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $account = $this->em->find('SocietoAuthenticationBundle:Account', 1);
        $this->login($client, $account);

        $crawler = $client->request('POST', '/status/post/1');
        $this->token = $crawler->filter('#societo_status_plugin_status__token')->attr('value');

        return $client;
    }

    private function getNumberOfStatus()
    {
        return count($this->em->getRepository('SocietoStatusPlugin:Status')->findAll());
    }
}
