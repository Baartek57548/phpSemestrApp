<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-user', description: 'Tworzy użytkownika z wybraną rolą.')]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email użytkownika')
            ->addArgument('password', InputArgument::REQUIRED, 'Hasło użytkownika')
            ->addArgument('role', InputArgument::OPTIONAL, 'Rola użytkownika', 'ROLE_USER');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = (string) $input->getArgument('email');
        $password = (string) $input->getArgument('password');
        $role = strtoupper((string) $input->getArgument('role'));

        if (!in_array($role, ['ROLE_USER', 'ROLE_ADMIN'], true)) {
            $io->error('Dozwolone role: ROLE_USER albo ROLE_ADMIN.');

            return Command::INVALID;
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($existingUser instanceof User) {
            $io->error('Użytkownik o tym emailu już istnieje.');

            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Utworzono użytkownika %s z rolą %s.', $email, $role));

        return Command::SUCCESS;
    }
}
