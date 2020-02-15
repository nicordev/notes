<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberEditionType;
use App\Helper\RoleHelper;
use App\Form\MemberRegistrationType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberController extends AbstractController
{
    /**
     * @Route("/members", name="member_index")
     */
    public function index(MemberRepository $memberRepository)
    {
        $members = $memberRepository->findAll();

        return $this->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * @Route("/members/register", name="member_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You are already registered.');
            
            return $this->redirectToRoute('home');
        }

        $member = new Member();
        $registrationForm = $this->createForm(MemberRegistrationType::class, $member);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $member->setPassword($encoder->encodePassword($member, $member->getPassword()));
            $manager->persist($member);
            $manager->flush();
            $this->addFlash('notice', 'A member has been added.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('member/register.html.twig', [
            'registrationForm' => $registrationForm->createView()
        ]);
    }

    /**
     * @Route("/members/edit/{id}", name="member_edit", requirements={"id": "\d+"})
     */
    public function edit(Member $member, Request $request, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $memberForm = $this->createForm(MemberEditionType::class, $member);
        $memberForm->handleRequest($request);

        if ($memberForm->isSubmitted() && $memberForm->isValid()) {
            $manager->flush();
            $this->addFlash('notice', 'A member has been modified.');

            return $this->redirectToRoute('admin_index', ['panel' => 'member']);
        }

        return $this->render('member/edit.html.twig', [
            'memberForm' => $memberForm->createView()
        ]);
    }

    /**
     * @Route("/members/delete/{id}", name="member_delete", requirements={"id": "\d+"})
     */
    public function delete(Member $member, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $manager->remove($member);
        $manager->flush();
        $this->addFlash('notice', 'A member has been deleted.');

        return $this->redirectToRoute('admin_index', ['panel' => 'member']);
    }
}
