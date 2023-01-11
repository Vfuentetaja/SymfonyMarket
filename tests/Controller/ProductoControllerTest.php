<?php

namespace App\Test\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProductoRepository $repository;
    private string $path = '/producto/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Producto::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Producto index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'producto[nombre]' => 'Testing',
            'producto[precio]' => 'Testing',
            'producto[descripcion]' => 'Testing',
            'producto[cantidad]' => 'Testing',
            'producto[categoria]' => 'Testing',
            'producto[destacado]' => 'Testing',
            'producto[oferta]' => 'Testing',
            'producto[novedad]' => 'Testing',
        ]);

        self::assertResponseRedirects('/producto/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setCantidad('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setDestacado('My Title');
        $fixture->setOferta('My Title');
        $fixture->setNovedad('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Producto');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setCantidad('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setDestacado('My Title');
        $fixture->setOferta('My Title');
        $fixture->setNovedad('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'producto[nombre]' => 'Something New',
            'producto[precio]' => 'Something New',
            'producto[descripcion]' => 'Something New',
            'producto[cantidad]' => 'Something New',
            'producto[categoria]' => 'Something New',
            'producto[destacado]' => 'Something New',
            'producto[oferta]' => 'Something New',
            'producto[novedad]' => 'Something New',
        ]);

        self::assertResponseRedirects('/producto/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNombre());
        self::assertSame('Something New', $fixture[0]->getPrecio());
        self::assertSame('Something New', $fixture[0]->getDescripcion());
        self::assertSame('Something New', $fixture[0]->getCantidad());
        self::assertSame('Something New', $fixture[0]->getCategoria());
        self::assertSame('Something New', $fixture[0]->getDestacado());
        self::assertSame('Something New', $fixture[0]->getOferta());
        self::assertSame('Something New', $fixture[0]->getNovedad());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setCantidad('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setDestacado('My Title');
        $fixture->setOferta('My Title');
        $fixture->setNovedad('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/producto/');
    }
}
