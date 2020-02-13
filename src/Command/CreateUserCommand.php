<?php

namespace App\Command;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    private $userManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $userManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $member = new Member();
        $questionHelper = $this->getHelper('question');
        
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $name = $input->getArgument('username');

        if (!$name) {
            $question = new Question('Enter a name for the user: ', null);

            if (!$name = $questionHelper->ask($input, $output, $question)) {
                $output->writeln('You did not enter a name.');
                return 1;
            }
        }

        $question = new Question('Enter an email: ', null);

        if (!$email = $questionHelper->ask($input, $output, $question)) {
            $output->writeln('You did not enter an email.');
            return 2;
        }

        $question = new Question('Enter a password: ', null);

        if (!$password = $questionHelper->ask($input, $output, $question)) {
            $output->writeln('You did not enter a password.');
            return 3;
        }
        $question = new ConfirmationQuestion('Add the admin role to the user?', true);

        if ($questionHelper->ask($input, $output, $question)) {
            $member->addRole('ROLE_ADMIN');
        }

        $member->setPassword($this->passwordEncoder->encodePassword($member, $password));
        $member->setName($name);
        $member->setEmail($email);
        $this->userManager->persist($member);
        $this->userManager->flush();

        $output->writeln("The user {$name} has been created.");
        
        return 0;
    }
}
