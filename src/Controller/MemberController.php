<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Form\MemberRegistrationType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends AbstractController
{
    /**
     * @Route("/member", name="member")
     */
    public function index(MemberRepository $memberRepository)
    {
        $members = $memberRepository->findAll();

        return $this->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * @Route("/member/register", name="member_register")
     */
    public function register(Request $request, EntityManagerInterface $manager)
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You are already registered.');
            
            return $this->redirectToRoute('home');
        }

        $member = new Member();
        $registrationForm = $this->createForm(MemberRegistrationType::class, $member);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $manager->persist($member);
            $manager->flush();
            $this->addFlash('notice', 'A member has been added.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('member/register.html.twig', [
            'registrationForm' => $registrationForm->createView()
        ]);   
    }
}