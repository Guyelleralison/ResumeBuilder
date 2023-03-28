<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\CandidateRepository;
use App\Repository\ProfileRepository;
use App\Entity\Candidate;

class CandidateController extends AbstractController
{
    #[Route('/api/candidates', name: 'app_candidate', methods: ['GET'])]
    public function getCandidates(CandidateRepository $candidateRepository, SerializerInterface $serializer): JsonResponse
    {
        $candidateList = $candidateRepository->findAll();
        $candidatesJson = $serializer->serialize($candidateList, 'json', ['groups'=>'getCandidates']);
        return new JsonResponse($candidatesJson, Response::HTTP_OK, ['Access-Control-Allow-Origin'], true);
    }

    #[Route('/api/candidates/{id}', name: 'app_candidate_detail', methods: ['GET'])]
    public function getCandidateDetail(Candidate $candidate, SerializerInterface $serializer): JsonResponse
    {
        if ($candidate) {
            $candidateJson = $serializer->serialize($candidate, 'json', ['groups'=>'getCandidates']);
            return new JsonResponse($candidateJson, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/candidates', name: 'app_create_candidate', methods: ['POST'])]
    public function createCandidate(
        Request $request,
        CandidateRepository $candidateRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UrlGeneratorInterface $urlGenerator,
    ): JsonResponse {
        $candidate = $serializer->deserialize($request->getContent(), Candidate::class, 'json');
        $errors = $validator->validate($candidate);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $candidateRepository->save($candidate, true);
        $jsonCandidate = $serializer->serialize($candidate, 'json', ['groups' => 'getCandidates']);

        $location = $urlGenerator->generate('app_candidate_detail', ['id' => $candidate->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCandidate, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
