<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Handle CRUD
 */
class PokemonManager 
{
    private $em;

    private $pokemonRepository;

    public function __construct(EntityManagerInterface $em, PokemonRepository $pokemonRepository)
    {
        $this->em = $em;
        $this->pokemonRepository = $pokemonRepository;
    }

    public function create(string $name, string $summary): Pokemon
    {
        $pokemon = new Pokemon();
        $pokemon->setName($name);
        $pokemon->setSummary($summary);

        $this->em->persist($pokemon);
        $this->em->flush();

        return $pokemon;
    }

    public function update(string $name, string $summary, int $id): Pokemon
    {
        $pokemon = $this->pokemonRepository->find($id);

        if (!$pokemon) {
            throw new HttpException(404, "The pokemon with id $id does not exist.");
        }

        $pokemon->setName($name);
        $pokemon->setSummary($summary);

        $this->em->persist($pokemon);
        $this->em->flush();

        return $pokemon;
    }

    public function delete(int $id)
    {
        $pokemon = $this->pokemonRepository->find($id);

        $this->em->remove($pokemon);
        $this->em->flush();
    }
}