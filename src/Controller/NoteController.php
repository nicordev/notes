<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/notes", name="notes")
     */
    public function index(NoteRepository $noteRepository, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $user);

        $noteForm = $this->handleNoteCreation($request, $manager, $user);
        $notes = $noteRepository->findBy(['author' => $user], ['id' => 'DESC']);

        return $this->render('note/index.html.twig', [
            'noteForm' => $noteForm->createView(),
            'notes' => $notes,
        ]);
    }

    /**
     * Make a form to create a new note and saves a new note in the database if needed.
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleNoteCreation(Request $request, EntityManagerInterface $manager, Member $author)
    {
        $note = new Note();
        $noteForm = $this->handleNoteForm($note, $request);

        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $note->setAuthor($author);
            $manager->persist($note);
            $manager->flush();
            $this->addFlash("success", "A note has been created.");
        }

        return $noteForm;
    }

    /**
     * @Route(
     *     "/notes/{id}",
     *     name="notes_show",
     *     requirements={"id": "\d+"}
     * )
     */
    public function show(Note $note)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());

        return $this->render("note/show.html.twig", ["note" => $note]);
    }

    /**
     * @Route(
     *     "/notes/edit/{id}",
     *     name="notes_edit",
     *     requirements={"id": "\d+"}
     * )
     */
    public function edit(Note $note, Request $request, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());

        $form = $this->handleNoteForm($note, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash("success", "A note has been updated.");

            return $this->redirectToRoute("notes");
        }

        return $this->render("note/edit.html.twig", [
            "noteForm" => $form->createView()
        ]);
    }

    /**
     * @Route(
     *     "/notes/delete/{id}",
     *     name="notes_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function delete(Note $note, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());

        $manager->remove($note);
        $manager->flush();

        $this->addFlash("notice", "A note has been deleted.");

        return $this->redirectToRoute("notes");
    }

    /**
     * Create a form for a note.
     *
     * Also handle the Request object.
     *
     * @param Note $note
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleNoteForm(Note $note, Request $request)
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        return $form;
    }
}
