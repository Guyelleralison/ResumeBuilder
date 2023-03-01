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
use App\Repository\ExperienceRepository;
use App\Repository\CandidateRepository;
use App\Entity\Experience;

class ExperienceController extends AbstractController
{
    #[Route('/api/experiences', name: 'app_experience', methods: ['GET'])]
    public function getExperiences(ExperienceRepository $experienceRepository, SerializerInterface $serializer): JsonResponse
    {
        $experienceList = $experienceRepository->findAll();
        $experienceJson = $serializer->serialize($experienceList, 'json', ['groups' => 'getExperiences']);
        return new JsonResponse($experienceJson, Response::HTTP_OK, [], true);
    }

    #[Route('/api/experiences/{id}', name: 'app_experience_detail', methods: ['GET'])]
    public function getExperienceDetail(Experience $experience, SerializerInterface $serializer): JsonResponse
    {
        if ($experience) {
            $experienceJson = $serializer->serialize($experience, 'json', ['groups' => 'getExperiences']);
            return new JsonResponse($experienceJson, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/experiences/{id}', name: 'app_experience_delete', methods: ['DELETE'])]
    public function deleteExeperience(Experience $experience, ExperienceRepository $experienceRepository): JsonResponse 
    {
        $experienceRepository->remove($experience, true);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/experiences', name:"app_experience_create", methods: ['POST'])]
    public function createExperience(
        Request $request, 
        SerializerInterface $serializer, 
        ExperienceRepository $experienceRepository, 
        UrlGeneratorInterface $urlGenerator,
        CandidateRepository $candidateRepository
    ): JsonResponse 
    {

        $experience = $serializer->deserialize($request->getContent(), Experience::class, 'json');
        $content = $request->toArray();
        $idCandidate = $content['idCandidate'] ?? -1;

        $experience->setCandidate($candidateRepository->find($idCandidate));
     
        $experienceRepository->save($experience, true);

        $jsonExperience = $serializer->serialize($experience, 'json', ['groups' => 'getExperiences']);

        $location = $urlGenerator->generate('app_experience_detail', ['id' => $experience->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonExperience, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/experiences/{id}', name:"app_update_experience", methods:['PUT'])]
    public function updateExperience(
        Request $request, 
        SerializerInterface $serializer, 
        Experience $currentExperience, 
        ExperienceRepository $experienceRepository,
        CandidateRepository $candidateRepository
    ): JsonResponse 
    {
        $updatedExperience = $serializer->deserialize($request->getContent(), 
                Experience::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentExperience]);
        $content = $request->toArray();
        $idCandidate = $content['idCandidate'] ?? -1;
        $updatedExperience->setCandidate($candidateRepository->find($idCandidate));
        
        $experienceRepository->save($updatedExperience, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
