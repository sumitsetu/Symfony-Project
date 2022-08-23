<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class UserRegistrationTest extends WebTestCase
{
    protected $client;
    protected $container;
    protected Session $session;
    protected Request $request;
    protected RequestStack $requestStack;

    public function testRegistrationForm(): void
    {
        $this->client = static::createClient();
        $this->container = static::getContainer();

        //$session = new Session(new MockFileSessionStorage('var/test/sessions'));
        $session = $this->container->get('session.factory')->createSession();   
        $this->session = $session;
        $this->request = new Request();
        $this->session->start();
        $this->request->setSession($this->session);
        $this->requestStack = new RequestStack();
        $this->container->get('request_stack')->push($this->request);
        
        $tokenId = 'user_registration';
        $csrfToken = $this->container->get('security.csrf.token_generator')->generateToken();
        $this->session->set(SessionTokenStorage::SESSION_NAMESPACE . "/$tokenId", $csrfToken);
        $this->session->save();
       
        //$this->container->get('security.csrf.token_storage')->setToken('user_registration',  $csrfToken);

        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $this->client->getCookieJar()->set($cookie);
        $gettoken = $this->container->get('security.csrf.token_manager')->getToken('user_registration')->getValue();
        //dd($gettoken);
        //$gettoken = new CsrfToken($tokenId, $csrfToken);
        //dd($gettoken);
        $registration_data = array(
            '_user' => 'sumogo11', 
            'email' => "sumogo11@cc.com",
            'password' => array(
                "password" => 123456,
                "confirm_password" => "123456"
            ),
            "agreement" => "1",
            "register" => "",
            '_token' => $gettoken
        );
        $crawler = $this->client->request('POST', '/registration', array('user_registration' => $registration_data));
        
        $crawler = $this->client->followRedirect();
       
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Sign In")')->count(), 'actual is not greater then expected'
        ); 
    }
}
