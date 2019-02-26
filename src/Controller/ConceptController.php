<?php

namespace App\Controller;

use App\Entity\Concept;
use App\Form\ConceptType;
use App\Repository\ConceptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LastArticlesService;

/**
 * @Route("/concept")
 */
class ConceptController extends AbstractController
{
    /**
     * @var LastArticlesService
     */
    protected $lastArticlesService;

    public function __construct(LastArticlesService $lastArticlesService)
    {
        $this->lastArticlesService = $lastArticlesService;
    }


    /**
     * @Route("/", name="concept_index", methods={"GET"})
     */
    public function index(ConceptRepository $conceptRepository): Response
    {
        return $this->render('concept/index.html.twig', [
            'concepts' => $conceptRepository->findAll(),
        ]);
    }



}
