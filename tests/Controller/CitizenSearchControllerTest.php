<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CitizenSearchControllerTest extends WebTestCase
{
    public function testCitizenSearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search');

        $form = $crawler->filter('form[name="form"]')->form();
        $form['form[nis]'] = '12345678901';
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Pesquisa de Cidadão');
        if ($crawler->filter('.alert.alert-success')->count() > 0) {
            $this->assertSelectorTextContains('.alert.alert-success', 'Cidadão encontrado:');
        } elseif ($crawler->filter('.alert.alert-danger')->count() > 0) {
            $this->assertSelectorTextContains('.alert.alert-danger', 'Cidadão não encontrado.');
        }

        $this->assertSelectorExists('a.back-link');
    }
}