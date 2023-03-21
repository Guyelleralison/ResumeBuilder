<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\SkillCategoryRepository;
use App\Entity\SkillCategory;

class SkillCategoryController extends AbstractController
{
    #[Route('/api/skill/category', methods: ['GET'],  name: 'app_skill_category')]
    public function getSkillCategories(SkillCategoryRepository $skillCategoryRepository, SerializerInterface $serializer): JsonResponse
    {
        $skillCategoryList = $skillCategoryRepository->findAll();
        $jsonSkillCategoryList = $serializer->serialize($skillCategoryList, 'json', ['groups' => "getCategory"]);
        return new JsonResponse($jsonSkillCategoryList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/skill/category/{id}', methods: ['GET'],  name: 'app_skill_category_detail')]
    public function getSkillCategoryDetail(SkillCategory $skillCategory, SkillCategoryRepository $skillCategoryRepository, SerializerInterface $serializer): JsonResponse
    {
        if ($skillCategory) {
            $skillCategoryJson = $serializer->serialize($skillCategory, 'json', ['groups'=>'getCategory']);
            return new JsonResponse($skillCategoryJson, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/skill/category/{id}', name: 'app_skill_category_delete', methods: ['DELETE'])]
    public function deleteExeperience(SkillCategory $skillCategory, SkillCategoryRepository $skillCategoryRepository): JsonResponse 
    {
        $skillCategoryRepository->remove($skillCategory, true);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/skill/category', name:"app_create_skill_category", methods: ['POST'])]
    public function addSkillCategory(
        Request $request, 
        SerializerInterface $serializer, 
        SkillCategoryRepository $skillCategoryRepository, 
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse 
    {

        $skillCategory = $serializer->deserialize($request->getContent(), SkillCategory::class, 'json');
        $errors = $validator->validate($skillCategory);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
     
        $skillCategoryRepository->save($skillCategory, true);

        $jsonskillCategory = $serializer->serialize($skillCategory, 'json', ['groups' => 'getCategory']);

        $location = $urlGenerator->generate('app_skill_category_detail', ['id' => $skillCategory->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonskillCategory, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/skill/category/{id}', name:"app_update_skill_category", methods:['PUT'])]
    public function updateSkillCategory(
        Request $request, 
        SerializerInterface $serializer, 
        SkillCategory $currentSkillCategory, 
        SkillCategoryRepository $skillCategoryRepository,
    ): JsonResponse 
    {
        $updatedSkillCategory = $serializer->deserialize($request->getContent(), 
                SkillCategory::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSkillCategory]);
        
        $skillCategoryRepository->save($updatedSkillCategory, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
