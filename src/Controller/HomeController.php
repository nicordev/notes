<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        NoteRepository $noteRepository,
        Request $request,
        EntityManagerInterface $manager
    ) {
        $note = new Note();
        $noteForm = $this->createForm(
                NoteType::class, 
            $note
        );
        $noteForm->handleRequest($request);
        $notes = $noteRepository->findBy(['author' => null], ['id' => 'DESC']);

        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $manager->persist($note);
            $manager->flush();
            $this->addFlash("success", "A note has been created.");
        }

        return $this->render('home/index.html.twig', [
            'noteForm' => $noteForm->createView(),
            'notes' => $notes
        ]);
    }
}
