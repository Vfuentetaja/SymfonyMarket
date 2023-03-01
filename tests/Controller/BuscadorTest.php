<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuscadorTest extends WebTestCase
{
    /**
     * @dataProvider additionProvider2
     */
    public function testBusquedaContent($a, $busqueda): void
    {
        $this->assertStringContainsString($a,$busqueda[0]);
    }
    
    /**
     * @dataProvider additionProvider2
     */
    public function testBusquedaContent2($a, $busqueda): void
    {
        $this->assertStringContainsString($a,$busqueda[1]);
    }

    public function additionProvider2(): array
    {
        return [
            ["sombrero",["sombrero","pantalon"]],
            ["s",["sombrero","pantalon"]],
            ["o",["sombrero","pantalon"]],
            [3,["sombrero","pantalon"]],
            ["",["sombrero","pantalon"]],
            [" ",["sombrero","pantalon"]],
            ["!",["sombrero","pantalon"]]
        ];
    }
/*     public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    } */
}
