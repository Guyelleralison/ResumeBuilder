<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\CandidateSkillRepository;
use App\Entity\CandidateSkill;

class CandidateSkillController extends AbstractController
{
    #[Route('/api/candidate/skill', name: 'app_candidate_skill', methods: ['GET'])]
    public function getCandidateSkillList(
        CandidateSkillRepository $candidateSkillRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $candidateSkills = $candidateSkillRepository->findAll();
        $jsonCandidateSkills = $serializer->serialize($candidateSkills, 'json', ['groups'=>'getCandidateSkills']);
        return new JsonResponse($jsonCandidateSkills, Response::HTTP_OK, [], true);
    }

    #[Route('/api/candidate/skill/{id}', name: 'app_candidate_skill_detail', methods: ['GET'])]
    public function getCandidateSkillDetail(
        CandidateSkill $candidateSkill,
        SerializerInterface $serializer
    ): JsonResponse
    {
        if($candidateSkill) {
            $jsonCandidateSkill = $serializer->serialize($candidateSkill, 'json', ['groups'=>'getCandidateSkills']);
            return new JsonResponse($jsonCandidateSkill, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/candidate/skill', name: 'app_create_candidate_skill', methods: ['POST'])]
    public function createCandidateSkill(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UrlGeneratorInterface $urlGenerator,
        CandidateSkillRepository $candidateSkillRepository
    ): JsonResponse
    {
        $newCandidateSkill = $serializer->deserialize($request->getContent(), CandidateSkill::class, 'json');
        $errors = $validator->validate($newCandidateSkill);
        if($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $candidateSkillRepository->save($newCandidateSkill, true);
        $jsonCandidateSkill = $serializer->serialize($newCandidateSkill, 'json', ['groups' => 'getCandidateSkills']);

        $location = $urlGenerator->generate('app_candidate_skill_detail', ['id' => $newCandidateSkill->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCandidateSkill, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/candidate/skill/{id}', name:"app_update_candidate_skill", methods:['PUT'])]
    public function updateCandidateSkill(
        Request $request, 
        SerializerInterface $serializer, 
        CandidateSkill $candidateSkill, 
        CandidateSkillRepository $candidateSkillRepository
    ): JsonResponse 
    {
        $updatedCandidateSkill = $serializer->deserialize($request->getContent(), 
                CandidateSkill::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $candidateSkill]);
        
        $candidateSkillRepository->save($updatedCandidateSkill, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
