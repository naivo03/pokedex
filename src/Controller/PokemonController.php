<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Manager\PokemonManager;

/**
 * @Route("/pokemons")
 */
class PokemonController
{
    private $pokemonManager;
    private $serializer;

    public function __construct(PokemonManager $pokemonManager)
    {
        $this->pokemonManager = $pokemonManager;

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /** 
     * List all pokemons
     * 
     * @Route("", methods={"GET"})
    */
    public function list()
    {
        $pokemons = $this->pokemonManager->getAll();
        $jsonContent = $this->serializer->serialize($pokemons, 'json');

        return new Response($jsonContent);
    }

    /**
     * Create a pokemon
     * 
     * @Route("", methods={"POST"})
     */
    public function create(Request $request)
    {
        $name = $request->request->get('name');
        $summary = $request->request->get('summary');

        $pokemon = $this->pokemonManager->create($name, $summary);
        $json = $this->serializer->serialize($pokemon, 'json');

        return new Response($json);
    }

    /**
     * Update a Pokemon
     * 
     * @Route("/{id<\d+>}", methods={"PUT"})
     */
    public function update(Request $request, int $id)
    {
        $name = $request->request->get('name');
        $summary = $request->request->get('summary');

        $pokemon = $this->pokemonManager->update($name, $summary, $id);
        $json = $this->serializer->serialize($pokemon, 'json');

        return new Response($json);
    }

    /**
     * Delete a Pokemon
     * 
     * @Route("/{id<\d+>}", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        $pokemon = $this->pokemonManager->delete($id);
        $json = $this->serializer->serialize($pokemon, 'json');

        return new Response($json);
    }
}
