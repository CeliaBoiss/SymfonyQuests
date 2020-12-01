<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;

/**
* @Route("/programs", name="program_")
*/
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response a response instance
     */    
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', ['website' => 'Wild Séries', 'programs' => $programs]);
    }

    /**
     * @Route("/{id<\d+>}", methods={"GET"}, name="by_id")
     * @return Response a response instance
    */
    public function show(Program $program): Response
    {
        if(!$program) {
            throw $this->createNotFoundException('No program with id : '.$id.' found in program\'s table.');
        }

        return $this->render('program/programbyid.html.twig', ['program' => $program]);
    }
}