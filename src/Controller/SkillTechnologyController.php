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
use App\Repository\SkillTechnologyRepository;
use App\Entity\SkillTechnology;

class SkillTechnologyController extends AbstractController
{
    #[Route('/api/skill/technology', methods: ['GET'],  name: 'app_skill_technology')]
    public function getSkillTechnologies(SkillTechnologyRepository $skillTechnologyRepository, SerializerInterface $serializer): JsonResponse
    {
        $skillTechnologyList = $skillTechnologyRepository->findAll();
        $jsonskillTechnologyList = $serializer->serialize($skillTechnologyList, 'json', ['groups' => "getTechnology"]);
        return new JsonResponse($jsonskillTechnologyList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/skill/technology/{id}', methods: ['GET'],  name: 'app_skill_technology_detail')]
    public function getskillTechnologyDetail(
        SkillTechnology $skillTechnology, 
        SkillTechnologyRepository $skillTechnologyRepository, 
        SerializerInterface $serializer
        ): JsonResponse
    {
        if ($skillTechnology) {
            $skillTechnologyJson = $serializer->serialize($skillTechnology, 'json', ['groups'=>'getTechnology']);
            return new JsonResponse($skillTechnologyJson, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/skill/technology/{id}', name: 'app_skill_technology_delete', methods: ['DELETE'])]
    public function deleteExeperience(SkillTechnology $skillTechnology, SkillTechnologyRepository $skillTechnologyRepository): JsonResponse 
    {
        $skillTechnologyRepository->remove($skillTechnology, true);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/skill/technology', name:"app_create_skill_technology", methods: ['POST'])]
    public function addskillTechnology(
        Request $request, 
        SerializerInterface $serializer, 
        SkillTechnologyRepository $skillTechnologyRepository, 
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse 
    {

        $skillTechnology = $serializer->deserialize($request->getContent(), skillTechnology::class, 'json');
        $errors = $validator->validate($skillTechnology);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
     
        $skillTechnologyRepository->save($skillTechnology, true);

        $jsonskillTechnology = $serializer->serialize($skillTechnology, 'json', ['groups' => 'getTechnology']);

        $location = $urlGenerator->generate('app_skill_technology_detail', ['id' => $skillTechnology->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonskillTechnology, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/skill/technology/{id}', name:"app_update_skill_technology", methods:['PUT'])]
    public function updateSkillTechnology(
        Request $request, 
        SerializerInterface $serializer, 
        SkillTechnology $currentSkillTechnology, 
        SkillTechnologyRepository $skillTechnologyRepository,
    ): JsonResponse 
    {
        $updatedSkillTechnology = $serializer->deserialize($request->getContent(), 
                SkillTechnology::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSkillTechnology]);
        
        $skillTechnologyRepository->save($updatedSkillTechnology, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
