<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class UserLoginTest extends WebTestCase
{
    protected $container;
    protected $client;
    protected Request $request;
    protected RequestStack $requeststack;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->container = static::getContainer();
        $this->session = $this->container->get('session.factory')->createSession();       
        $this->request = new Request();
        $this->request->setSession($this->session);
        $this->container->get(RequestStack::class)->push($this->request);
      
    }

    public function testUserLoginUsingPost()
    {  
     
        $tokenId = "authenticate";
        $csrfToken = $this->container->get('security.csrf.token_generator')->generateToken();
        $this->session->set(SessionTokenStorage::SESSION_NAMESPACE . "/$tokenId", $csrfToken);
        $this->session->save();

        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $this->client->getCookieJar()->set($cookie);

        $getToken = $this->container->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        $csrfdata = array('login'=>'', '_token'=>$getToken);
        $crawler = $this->client->request('POST', '/login', array('_username' => 'sumogo13@cc.com', '_password' => "123456",  'user_login' => $csrfdata));
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("DashboardController")')->count(), 'actual is not greater then expected'
        ); 

        $this->client->restart();
    } 

    public function testUserLoginUsingGet()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('user_login[login]');
        $form = $buttonCrawlerNode->form();
        $data = array('_username' => 'sumogo13@cc.com','_password' => '123456');
        $this->client->submit($form,$data);
        $crawler = $this->client->followRedirect();
        $crawler = $this->client->request('GET', '/dashboard');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("DashboardController")')->count(), 'actual is not greater then expected'
        ); 

        $this->client->restart();
    }

}