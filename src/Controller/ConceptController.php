<?php

namespace App\Controller;

use App\Entity\Concept;
use App\Form\ConceptType;
use App\Repository\ConceptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/concept")
 */
class ConceptController extends AbstractController
{
    /**
     * @Route("/", name="concept_index", methods={"GET"})
     */
    public function index(ConceptRepository $conceptRepository): Response
    {
        return $this->render('concept/index.html.twig', [
            'concepts' => $conceptRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="concept_show", methods={"GET"})
     */
    public function show(Concept $concept): Response
    {
        return $this->render('concept/show.html.twig', [
            'concept' => $concept,
        ]);
    }

}
