<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ExperienceRepository;
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
    public function deleteExeperience(Experience $experience, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($experience);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/experiences', name:"app_experience_create", methods: ['POST'])]
    public function createExperience(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $experience = $serializer->deserialize($request->getContent(), Experience::class, 'json');
        $em->persist($experience);
        $em->flush();

        $jsonExperience = $serializer->serialize($experience, 'json', ['groups' => 'getExperiences']);
        
        $location = $urlGenerator->generate('app_experience_detail', ['id' => $experience->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonExperience, Response::HTTP_CREATED, ["Location" => $location], true);
   }
}
