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
    private $em;

    private $pokemonRepository;
    private $serializer;

    private $pokemonManager;

    public function __construct(EntityManagerInterface $em, PokemonRepository $pokemonRepository, PokemonManager $pokemonManager)
    {
        $this->em = $em;
        $this->pokemonRepository = $pokemonRepository;
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    
        $this->pokemonManager = $pokemonManager;
    }

    /** 
     * List all pokemons
     * 
     * @Route("", methods={"GET"})
    */
    public function index()
    {
        $pokemons = $this->pokemonRepository->findAll();
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
        $this->pokemonManager->delete($id);

        return new Response("pokemon supprimer");
    }
}
