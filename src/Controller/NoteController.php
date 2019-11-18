<?php

namespace App\Controller;

use App\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/notes", name="notes")
     */
    public function index()
    {
        return $this->render('note/index.html.twig', [
            'controller_name' => 'NoteController',
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
}
