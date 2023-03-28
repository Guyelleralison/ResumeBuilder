<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\SkillRepository;
use App\Repository\SkillCategoryRepository;
use App\Repository\SkillTechnologyRepository;
use App\Entity\Skill;
use App\Entity\SkillCategory;
use App\Entity\SkillTechnology;

class SkillController extends AbstractController
{
    #[Route('/api/skills', name: 'app_skill', methods:['GET'])]
    public function getSkillList(
        SkillRepository $skillRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $skills = $skillRepository->findAll();
        $jsonSkills = $serializer->serialize($skills, 'json', ['groups'=>'getSkills']);
        return new JsonResponse($jsonSkills, Response::HTTP_OK, [], true);
    }

    #[Route('/api/skills/{id}', name: 'app_skill_detail', methods:['GET'])]
    public function getSkillDetail(
        Skill $skill,
        SkillRepository $skillRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        if($skill) {
            $jsonSkill = $serializer->serialize($skill, 'json', ['groups'=>'getSkills']);
            return new JsonResponse($jsonSkill, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/skills', name: 'app_create_skill', methods:['POST'])]
    public function createSkill(
        Request $request,
        SkillRepository $skillRepository,
        SkillCategoryRepository $skillCategoryRepository,
        SkillTechnologyRepository $skillTechnologyRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {
        $skill = $serializer->deserialize($request->getContent(), Skill::class, 'json');
        $errors = $validator->validate($skill);
        if($errors->count() > 0) {
            echo 'yes';
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $content = $request->toArray();
        $idSkillCategory = $content['idCategory'] ?? -1;
        $idSkillTechnology = $content['idTechnology'] ?? -1;
        $skillCategory = $skillCategoryRepository->find($idSkillCategory);
        $skillTechnology = $skillTechnologyRepository->find($idSkillTechnology);

        $skill->setCategory($skillCategory);
        $skill->setTechnology($skillTechnology);
        $skillRepository->save($skill, true);
        $jsonSkill = $serializer->serialize($skill, 'json', ['groups'=>'getSkills']);
        $location = $urlGenerator->generate('app_skill_detail', ['id' => $skill->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSkill, Response::HTTP_OK, ['Location' => $location], true);
    }

    #[Route('/api/skills/{id}', name:"app_update_skill", methods:['PUT'])]
    public function updateSkill(
        Request $request, 
        SerializerInterface $serializer, 
        Skill $currentSkill, 
        SkillRepository $skillRepository,
        SkillCategoryRepository $skillCategoryRepository,
        SkillTechnologyRepository $skillTechnologyRepository,
    ): JsonResponse 
    {
        $updatedSkill = $serializer->deserialize($request->getContent(), 
                Skill::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSkill]);
        
        $content = $request->toArray();
        $idSkillCategory = $content['idCategory'] ?? -1;
        $idSkillTechnology = $content['idTechnology'] ?? -1;
        $skillCategory = $skillCategoryRepository->find($idSkillCategory);
        $skillTechnology = $skillTechnologyRepository->find($idSkillTechnology);

        $updatedSkill->setCategory($skillCategory);
        $updatedSkill->setTechnology($skillTechnology);
        $skillRepository->save($updatedSkill, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
