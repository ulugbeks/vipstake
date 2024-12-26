<?php

namespace App\Command\App;

use App\Entity\Admin\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-users',
    description: 'Add a short description for your command',
)]
class CreateUsersCommand extends Command
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EntityManagerInterface $em,
        string $name = 'Create users command'
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $admin = new User();
        $admin->setName('Dmitriy')
            ->setEmail('dimeon94@gmail.com')
            ->setRoles(["ROLE_SUPERADMIN"])
            ->setPassword($this->hasher->hashPassword($admin, '74108520'));


        $fomin = new User();
        $fomin->setName('Olexander')
            ->setEmail('afomin.klr@gmail.com')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->hasher->hashPassword($fomin, 'oH!MY#se12o'));

        $seo = new User();
        $seo->setName('SEO')
            ->setEmail('seo@goodguys.com.ua')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->hasher->hashPassword($seo, 'iAM#sEO13maN'));

        $content = new User();
        $content->setName('CONTENT')
            ->setEmail('content@goodguys.com.ua')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->hasher->hashPassword($content, 'mAke$oME312tExt'));

        $this->em->persist($admin);
        $this->em->persist($fomin);
        $this->em->persist($seo);
        $this->em->persist($content);
        $this->em->flush();

        $io->success('Success');

        return Command::SUCCESS;
    }
}
