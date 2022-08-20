<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class UserRegistrationTest extends WebTestCase
{
    public function testRegistrationForm(): void
    {
        //$session = new Session(new MockFileSessionStorage('var/test/sessions'));

        $this->assertTrue(true);
    }
}
