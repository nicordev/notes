<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\Note;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');

        $this->makeMembers();
        $this->makeNotes(10);

        $manager->flush();
    }

    public function makeMembers()
    {
        for ($i = 0; $i < 5; $i++) {
            $member = new Member();
            $member->setPassword($this->passwordEncoder->encodePassword($member, "mdp"))
                ->setUsername($this->faker->name);
            $this->makeNotes(5, $member);
            $this->manager->persist($member);
        }
    }

    public function makeNotes(int $count, ?Member $author = null)
    {
        $genericTitle = $author ? "Note" : "Anonymous note";

        for ($i = 0; $i < $count; $i++) {
            $note = new Note();
            $note->setTitle("$genericTitle n°$i")
                ->setContent(implode(" ", $this->faker->sentences))
                ->setAuthor($author);
            $this->manager->persist($note);
        }
    }
}
