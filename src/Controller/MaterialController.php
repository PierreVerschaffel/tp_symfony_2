<?php

namespace App\Controller;

use App\Entity\Material;
use OpenApi\Attributes as OA;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class MaterialController extends AbstractController
{
    #[Route('/material', name: 'app_material', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne tous les matériels.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Material::class, groups: ['pen:read']))
        )
    )]
    #[OA\Tag(name: 'Material')]
    #[Security(name: 'Bearer')]
    public function index(MaterialRepository $materialRepository): Response
    {
        $materials = $materialRepository->findAll();

        return $this->json([
            'materials' => $materials,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/material/{id}', name: 'app_material_get', methods: ['GET'])]
    #[OA\Tag(name: 'Material')]
    public function get(Material $material): Response
    {
        return $this->json([
            'material' => $material,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/material', name: 'app_material_add', methods: ['POST'])]
    #[OA\Tag(name: 'Material')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $material = new Material();
        $material->setName($data['name']);

        $em->persist($material);
        $em->flush();

        return $this->json([
            'material' => $material,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/material/{id}', name: 'app_material_update', methods: ['PUT','PATCH'])]
    #[OA\Tag(name: 'Material')]
    public function update(Request $request, Material $material, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $material->setName($data['name']);

        $em->persist($material);
        $em->flush();

        return $this->json([
            'material' => $material,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/material/{id}', name: 'app_material_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Material')]
    public function delete(Material $material, EntityManagerInterface $em): Response
    {
        $em->remove($material);
        $em->flush();

        return $this->json([
            'message' => 'Materiel supprimé',
        ]);
    }
}
