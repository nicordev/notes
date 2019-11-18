<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/notes", name="notes")
     */
    public function index(NoteRepository $noteRepository)
    {
        $notes = $noteRepository->findAll();

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
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
        return $this->render("note/show.html.twig", ["note" => $note]);
    }

    /**
     * @Route(
     *     "/notes/create",
     *     name="notes_create"
     * )
     */
    public function create(EntityManagerInterface $manager)
    {
        // TODO
    }

    /**
     * @Route(
     *     "/notes/edit/{id}",
     *     name="notes_edit",
     *     requirements={"id": "\d+"}
     * )
     */
    public function edit(Note $note, EntityManagerInterface $manager)
    {
        // TODO
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
        $manager->remove($note);
        $manager->flush();

        $this->addFlash("notice", "A note has been deleted.");

        return $this->redirectToRoute("notes");
    }
}
