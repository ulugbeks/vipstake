<?php

namespace App\Command\App;

use App\Entity\Admin\User;
use App\Entity\Frontend\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-subjects',
    description: 'Add a short description for your command',
)]
class LoadSubjectsCommand extends Command
{
    private array $subjects = [
        "Art",
        "Architecture",
        "Advertising",
        "Accounting",
        "Application writing",
        "American history",
        "American literature",
        "Ancient literature",
        "Agriculture",
        "Anthropology",
        "Astronomy",
        "Aviation",
        "Algebra",
        "Anatomy",
        "Analytics",
        "Animal science",
        "Applications and Forms",
        "Atmospheric sciences",
        "Biology",
        "Business and Management",
        "Behavioral science and Human development",
        "Chemistry",
        "Communications and Media",
        "Creative writing",
        "Canadian studies",
        "Criminology",
        "Computer science",
        "Calculus",
        "Code",
        "Cryptography",
        "Cultural studies",
        "C++",
        "C#",
        "Career and Professional development",
        "Community and Society",
        "Cryptocurrency",
        "Cyber security",
        "Dance",
        "Drama and Theatre",
        "Dentistry",
        "Data science",
        "Design and Modeling",
        "Digital sciences",
        "Economics",
        "Education",
        "Engineering",
        "English",
        "Ethics",
        "Environmental science",
        "Entrepreneurship",
        "Excel",
        "Emergency management",
        "Employee welfare",
        "Finance",
        "Fashion",
        "Feminism",
        "Forensic science",
        "Family and Child studies",
        "Food and Culinary studies",
        "Geography",
        "Geology",
        "Gender studies",
        "Geometry",
        "Global issues & Disaster and Crisis management",
        "Global studies",
        "History",
        "Healthcare",
        "Human relations",
        "Hospitality management",
        "International affairs / relations",
        "Investing and Financial markets",
        "Internet technology (IT)",
        "Immigration and Citizenship",
        "Information ethics",
        "Innovation and Technology",
        "Journalism",
        "Java",
        "Javascript",
        "Logistics",
        "Law",
        "Linguistics",
        "Literature",
        "Language studies",
        "Music",
        "Marketing",
        "Mathematics",
        "Medicine and Health",
        "Military science",
        "Mythology",
        "Nursing",
        "Nutrition",
        "Natural science",
        "Occupational safety and Health administration",
        "Painting",
        "Public relations",
        "Pharmacology",
        "Philosophy",
        "Physics",
        "Political science",
        "Psychology",
        "Photography",
        "PHP",
        "Physiology",
        "Psychiatry",
        "Public administration",
        "Programming",
        "Python",
        "Physical education",
        "Religion and Theology",
        "Research methods",
        "Shakespeare literature",
        "Sports and Athletics",
        "Sociology",
        "Social work",
        "Scholarship writing",
        "Sex education",
        "Special education",
        "SQL",
        "Statistics",
        "Social science",
        "Software and Applications",
        "Student activities",
        "Study design",
        "Technology",
        "Tourism",
        "Telecommunications",
        "Trigonometry",
        "Urban and Environmental planning",
        "Visual arts",
        "Veterinary science",
        "Vehicles and Mechanisms",
        "Web design",
        "Writing",
        "Other",
    ];

    public function __construct(
        private readonly EntityManagerInterface $em,
        string $name = 'Load subjects'
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->subjects as $label){
            $subject = new Subject();
            $subject->setLabel($label);
            $this->em->persist($subject);
        }

        $this->em->flush();

        $io->success('Success');

        return Command::SUCCESS;
    }
}
