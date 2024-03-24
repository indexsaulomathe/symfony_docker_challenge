<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CitizenControllerTest extends WebTestCase
{
    public function testNewCitizenFormSubmission(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

   
        $submitButton = $crawler->selectButton('Cadastrar'); 
        $form = $submitButton->form([
            'citizen[name]' => 'John Doe',
        ]);
        
        $client->submit($form);
        $client->followRedirect(); 

   
        $this->assertSelectorTextContains('h1', 'Cidadão Cadastrado com Sucesso');
        $this->assertSelectorTextContains('.form-group', 'O NIS gerado para o cidadão');
        $this->assertSelectorTextContains('.form-group', 'NIS:');
    }
}