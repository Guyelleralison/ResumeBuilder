<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\CandidateRepository;
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
}
