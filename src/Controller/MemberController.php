<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberController extends AbstractController
{
    /**
     * @Route("/members", name="members")
     */
    public function index(MemberRepository $repository)
    {
        $members = $repository->findAll();

        return $this->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * @Route("/members/register", name="members_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $member = new Member();
        $registrationForm = $this->createForm(MemberType::class, $member);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $member->setPassword($passwordEncoder->encodePassword($member, $member->getPassword()));
            $manager->persist($member);
            $manager->flush();
            $this->addFlash("success", "Welcome! You have been successfully registered.");

            return $this->redirectToRoute("app_login");
        }

        return $this->render("member/registration.html.twig", [
            'registrationForm' => $registrationForm->createView()
        ]);
    }
}
