<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/index/{panel}", name="admin_index")
     */
    public function index(
        NoteRepository $noteRepository,
        MemberRepository $memberRepository,
        string $panel
    ) {
        $notes = null;
        $members = null;

        if ($panel === "note") {
            $notes = $noteRepository->findAll();
        } elseif ($panel === "member") {
            $members = $memberRepository->findAll();
        }

        return $this->render("admin/index.html.twig", [
            "notes" => $notes,
            "members" => $members
        ]);
    }
}
