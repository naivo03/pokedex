<?php

namespace Tests\Unit\Manager;

use PHPUnit\Framework\TestCase;
use App\Manager\PokemonManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pokemon;
use App\Repository\PokemonRepository;

class PokemonManagerTest extends TestCase
{
    private $emMock;
    private $pokemonRepository;

    public function setUp()
    {
        $this->emMock = $this->createMock(EntityManagerInterface::class);
        $this->pokemonRepository = $this->createMock(PokemonRepository::class);
    }

    public function testCreate()
    {
        // mocks
        $this->emMock->expects($this->once())->method('persist')->willReturn(null);
        $this->emMock->expects($this->once())->method('flush')->willReturn(null);

        $pokemonManager = new PokemonManager($this->emMock, $this->pokemonRepository);
        $pokemon = $pokemonManager->create('pikachu', 'elec');

        $this->assertInstanceOf(Pokemon::class, $pokemon);
    }
}