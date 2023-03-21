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
use App\Repository\ExperienceProfileRepository;
use App\Repository\ProfileRepository;
use App\Repository\ExperienceRepository;
use App\Entity\ExperienceProfile;


class ExperienceProfileController extends AbstractController
{
    #[Route('/api/experience/profiles', name: 'app_experience_profile_all', methods:['GET'])]
    public function getAllExperienceProfile(
        SerializerInterface $serializer,
        ExperienceProfileRepository $experienceProfileRepository): JsonResponse
    {
        $experienceProfiles = $experienceProfileRepository->findAll();
        $jsonExperienceProfiles = $serializer->serialize($experienceProfiles, 'json', ['groups'=>'getExperiencesProfile']);
        return new JsonResponse($jsonExperienceProfiles, Response::HTTP_OK, [], true);
    }

    #[Route('/api/experience/profiles/{idProfile}', name: 'app_experience_profile_detail', methods:['GET'])]
    public function getExperienceProfileCandidate(
        int $idProfile,
        SerializerInterface $serializer,
        ExperienceProfileRepository $experienceProfileRepository): JsonResponse
    {
        $experienceProfileFound = $experienceProfileRepository->findByProfile($idProfile);
        if ($experienceProfileFound) {
            $jsonExperienceProfile = $serializer->serialize($experienceProfileFound, 'json', ['groups'=>'getExperiencesProfile']);
            return new JsonResponse($jsonExperienceProfile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/experience/profiles', name: 'app_experience_profile_create', methods:['POST'])]
    public function addExperienceProfile(
        Request $request, 
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        ExperienceProfileRepository $experienceProfileRepository,
        ProfileRepository $profileRepository,
        ExperienceRepository $experienceRepository): JsonResponse
    {
        $xpProfile = $serializer->deserialize($request->getContent(), ExperienceProfile::class, 'json');
        $paramsArray = $request->toArray();
        $profile = $profileRepository->find($paramsArray['idProfile']);
        $experience = $experienceRepository->find($paramsArray['idExperience']);
        $xpProfile->setProfile($profile);
        $xpProfile->setExperience($experience);
        $experienceProfileRepository->save($xpProfile, true);
        $jsonXPProfile = $serializer->serialize($xpProfile, 'json', ['groups'=>'getExperiencesProfile']);
        $location = $urlGenerator->generate('app_experience_profile_detail', ['id' => $xpProfile->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonXPProfile, Response::HTTP_CREATED, ['location' => $location], true);
    }

    #[Route('/api/experience/profiles/{id}', name: 'app_experience_profile_delete', methods:['DELETE'])]
    public function deleteExperienceProfile(
        ExperienceProfile $experienceProfile,
        SerializerInterface $serializer,
        ExperienceProfileRepository $experienceProfileRepository): JsonResponse
    {
        $experienceProfileRepository->remove($experienceProfile, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
