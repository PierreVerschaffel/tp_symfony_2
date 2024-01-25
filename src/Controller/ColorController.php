<?php

namespace App\Controller;

use App\Entity\Color;
use OpenApi\Attributes as OA;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColorController extends AbstractController
{
    #[Route('/color', name: 'app_color', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne toutes les couleurs.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Color::class, groups: ['pen:read']))
        )
    )]
    #[OA\Tag(name: 'Color')]
    #[Security(name: 'Bearer')]
    public function index(ColorRepository $colorRepository): Response
    {
        $colors = $colorRepository->findAll();

        return $this->json([
            'colors' => $colors,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/color/{id}', name: 'app_color_get', methods: ['GET'])]
    #[OA\Tag(name: 'Color')]
    public function get(Color $color): Response
    {
        return $this->json([
            'color' => $color,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/color', name: 'app_color_add', methods: ['POST'])]
    #[OA\Tag(name: 'Color')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $color = new Color();
        $color->setName($data['name']);

        $em->persist($color);
        $em->flush();

        return $this->json([
            'color' => $color,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/color/{id}', name: 'app_color_update', methods: ['PUT','PATCH'])]
    #[OA\Tag(name: 'Color')]
    public function update(Request $request, Color $color, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $color->setName($data['name']);

        $em->persist($color);
        $em->flush();

        return $this->json([
            'color' => $color,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/color/{id}', name: 'app_color_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Color')]
    public function delete(Color $color, EntityManagerInterface $em): Response
    {
        $em->remove($color);
        $em->flush();

        return $this->json([
            'message' => 'Couleur supprimée',
        ]);
    }
}
