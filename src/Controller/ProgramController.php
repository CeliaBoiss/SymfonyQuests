<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    * @Route("/new", name="new")
    */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('wewelcome.test@gmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramMail.html.twig', ['program' => $program]));
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="by_id")
     * @return Response a response instance
    */
    public function show(Program $program): Response
    {
        return $this->render('program/programbyid.html.twig', ['program' => $program]);
    }

    /**
     * @Route("/{programSlug}/season/{seasonId}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", options={"mapping" : {"programSlug" : "slug"} })
     * @ParamConverter("season", options={"id" = "seasonId"})
     * @return Response a response instance
    */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season]);
    }

    /**
     * @Route("/{programSlug}/seasons/{seasonId}/episodes/{episodeSlug}", methods={"GET"}, name="episode_show")
     * @ParamConverter("program", options={"mapping" : {"programSlug" : "slug"} })
     * @ParamConverter("season", options={"id" = "seasonId"})
     * @ParamConverter("episode", options={"mapping" : {"episodeSlug" : "slug"} })
     * @return Response a response instance
    */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', ['program' => $program, 'season' => $season, 'episode' => $episode]);
    }
}