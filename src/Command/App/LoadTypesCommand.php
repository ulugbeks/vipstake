<?php

namespace App\Command\App;

use App\Entity\Admin\User;
use App\Entity\Frontend\OrderType;
use App\Entity\Frontend\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-types',
    description: 'Add a short description for your command',
)]
class LoadTypesCommand extends Command
{
    private array $types = [
        "Admission essay",
        "Annotated bibliography",
        "Article review",
        "Article writing",
        "Accounting assignment",
        "Book / Movie review",
        "Business plan",
        "Biology assignment",
        "Business proposal",
        "Case study",
        "Creative writing",
        "Critical thinking / Review",
        "Chemistry assignment",
        "Capstone project",
        "Coursework",
        "Discussion post",
        "Essay (any type)",
        "Engineering assignment",
        "Excel assignment",
        "Economics assignment",
        "Geography assignment",
        "Homework assignment (any type)",
        "Literature review",
        "Lab report",
        "Letter / Memos",
        "Multiple choice questions",
        "Math assignment",
        "Marketing plan",
        "Other assignment",
        "Outline",
        "Presentation or speech",
        "Physics assignment",
        "PowerPoint presentation",
        "Personal narrative",
        "Programming",
        "PowerPoint presentation with speaker notes",
        "Research paper",
        "Research proposal",
        "Reflective writing",
        "Report",
        "Reaction paper",
        "Statistics assignment",
        "Short answer questions",
        "SWOT analysis",
        "Systematic review",
        "Term paper",
        "Thesis / Dissertation",
        "Word problems",
        "Other",
    ];

    public function __construct(
        private readonly EntityManagerInterface $em,
        string $name = 'Load types'
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->types as $label) {
            $subject = new OrderType();
            $subject->setLabel($label);
            $this->em->persist($subject);
        }

        $this->em->flush();

        $io->success('Success');

        return Command::SUCCESS;
    }
}
