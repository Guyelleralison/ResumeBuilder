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
use App\Repository\ProfileRepository;
use App\Entity\Profile;

class ProfileController extends AbstractController
{
    #[Route('/api/profiles', name: 'app_profile_all', methods:['GET'])]
    public function getAllProfile(
        SerializerInterface $serializer,
        ProfileRepository $profileRepository): JsonResponse
    {
        $profiles = $profileRepository->findAll();
        $jsonProfiles = $serializer->serialize($profiles, 'json', ['groups'=>"getProfiles"]);
        return new JsonResponse($jsonProfiles, Response::HTTP_OK, [], true);
    }

    #[Route('/api/profiles/{id}', name: 'app_profile_detail', methods:['GET'])]
    public function getProfile(
        Profile $profile,
        SerializerInterface $serializer,
        ProfileRepository $profileRepository): JsonResponse
    {
        $profile = $profileRepository->find($profile);
        if ($profile) {
            $jsonProfile = $serializer->serialize($profile, 'json', ['groups'=>'getProfiles']);
            return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/profiles', name: 'app_profile_create', methods:['POST'])]
    public function addProfile(
        Request $request, 
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        ProfileRepository $profileRepository): JsonResponse
    {
        $profile = $serializer->deserialize($request->getContent(), Profile::class, 'json');
        $profileRepository->save($profile, true);
        $jsonProfile = $serializer->serialize($profile, 'json', ['groups'=>'getProfiles']);
        $location = $urlGenerator->generate('app_profile_detail', ['id' => $profile->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonProfile, Response::HTTP_CREATED, ['location' => $location], true);
    }

    #[Route('/api/profiles/{id}', name: 'app_profile_update', methods:['PUT'])]
    public function updateProfile(
        Request $request,
        Profile $profile,
        SerializerInterface $serializer,
        ProfileRepository $profileRepository): JsonResponse
    {
        $updatedProfile = $serializer->deserialize(
            $request->getContent(), 
            Profile::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $profile]);

        $profileRepository->save($updatedProfile, true);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/candidates/profiles/list', name: 'app_candidate_profile_list', methods:['GET'])]
    public function getCandidateProfileList(
        SerializerInterface $serializer,
        ProfileRepository $profileRepository): JsonResponse
    {
        $profile = $profileRepository->getCandidateProfileList();
        $jsonProfile = $serializer->serialize($profile, 'json');
        return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
    }

    #[Route('/api/profiles/candidate/{id}', name: 'app_candidate_profile', methods:['GET'])]
    public function getCandidateProfile(
        int $id,
        SerializerInterface $serializer,
        ProfileRepository $profileRepository): JsonResponse
    {
        $profile = $profileRepository->getCandidateProfile($id);
        if ($profile) {
            $jsonProfile = $serializer->serialize($profile, 'json');
            return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
