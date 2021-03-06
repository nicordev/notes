<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
        $noteForm = $this->handleNewNoteForm($request, $manager);

        if ($this->getParameter('app.simple_mod')) {
            return $this->showAll($noteRepository, $noteForm);
        }

        $anonymousNotes = $noteRepository->findBy(['author' => null], ['id' => 'DESC']);
        $user = $this->getUser();

        if ($this->isGranted('ROLE_USER', $user)) {
            $userNotes = $noteRepository->findBy(['author' => $user], ['id' => 'DESC']);
        }

        return $this->render('home/index.html.twig', [
            'noteForm' => $noteForm->createView(),
            'anonymousNotes' => $anonymousNotes,
            'userNotes' => $userNotes ?? null
        ]);
    }

    public function showAll(NoteRepository $noteRepository, FormInterface $noteForm)
    {
        $notes = $noteRepository->findAll();

        return $this->render('home/index.html.twig', [
            'noteForm' => $noteForm->createView(),
            'anonymousNotes' => $notes,
            'userNotes' => null
        ]);
    }

    private function handleNewNoteForm(Request $request, EntityManagerInterface $manager)
    {
        $note = new Note();
        $noteForm = $this->createForm(
            NoteType::class,
            $note
        );
        $noteForm->handleRequest($request);

        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $manager->persist($note);
            $manager->flush();
            $this->addFlash("success", "A note has been created.");
        }

        return $noteForm;
    }
}
