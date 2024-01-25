<?php

namespace App\Controller;

use App\Entity\Brand;
use OpenApi\Attributes as OA;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api', name: 'api_')]
class BrandController extends AbstractController
{
    #[Route('/brand', name: 'app_brand', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne toutes les marques.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Brand::class, groups: ['pen:read']))
        )
    )]
    #[OA\Tag(name: 'Brand')]
    #[Security(name: 'Bearer')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();

        return $this->json([
            'brands' => $brands,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/brand/{id}', name: 'app_brand_get', methods: ['GET'])]
    #[OA\Tag(name: 'Brand')]
    public function get(Brand $brand): Response
    {
        return $this->json([
            'brand' => $brand,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/brand', name: 'app_brand_add', methods: ['POST'])]
    #[OA\Tag(name: 'Brand')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $brand = new Brand();
        $brand->setName($data['name']);

        $em->persist($brand);
        $em->flush();

        return $this->json([
            'brand' => $brand,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/brand/{id}', name: 'app_brand_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Brand')]
    public function update(Request $request, Brand $brand, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $brand->setName($data['name']);

        $em->persist($brand);
        $em->flush();

        return $this->json([
            'brand' => $brand,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/brand/{id}', name: 'app_brand_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Brand')]
    public function delete(Brand $brand, EntityManagerInterface $em): Response
    {
        $em->remove($brand);
        $em->flush();

        return $this->json([
            'message' => 'Marque supprimée',
        ]);
    }
}
