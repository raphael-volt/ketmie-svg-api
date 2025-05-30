<?php

namespace App\Tests\svg_symbols;

use App\Entity\SVGSymbol;
use App\Repository\SVGSymbolRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SVGSymbolsTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    use ReloadDatabaseTrait;

    /**
     * @return void
     * @throws ORMException
     */
    #[Test] public function loadSymbols()
    {
        $path = 'tests/data/symbols.json';
        $this->assertFileExists($path);
        $symbols = json_decode(file_get_contents($path), false);
        $this->assertIsArray($symbols);
        /** @var SVGSymbolRepository $repository */
        $repository = $this->entityManager->getRepository(SVGSymbol::class);
        $manager = $this->entityManager;


        foreach ($symbols as $symbol) {
            self::assertIsObject($symbol);
            $newSymbol = new SVGSymbol();
            $newSymbol->setId($symbol->id);
            $newSymbol->setName($symbol->name);
            $newSymbol->setData($symbol->data);
            $newSymbol->setHeight($symbol->height);
            $newSymbol->setWidth($symbol->width);
            $newSymbol->setHoles($symbol->holes);
            $newSymbol->setPathLength($symbol->pathLength);
            $manager->persist($newSymbol);
        }
        $manager->flush();
        $saved = $repository->findAll();
        self::assertCount(count($symbols), $saved);

    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}