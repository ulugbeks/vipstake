<?php

namespace App\Command\Fixtures;

use App\Entity\Frontend\OrderType;
use App\Entity\Frontend\Subject;
use App\Entity\Frontend\Writer;
use App\Entity\Frontend\WriterOrderType;
use App\Entity\Frontend\WriterSubject;
use App\Helpers\Helper;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-writers',
    description: 'Add a short description for your command',
)]
class GenerateWritersCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $authorsCount = 23;
        $faker = Factory::create('en_US');

        for ($k = 0; $k < $authorsCount; $k++) {
            $writer = new Writer();
            $writer->setThumbnail('2024/01/author-6599458423697.jpg')
                ->setName($faker->name())
                ->setEducation($faker->jobTitle());
            $reviewsCount = mt_rand(53, 2754);
            $ordersCount = intval($reviewsCount + ($reviewsCount / 100 * 20));

            /** Dynamic fields set */
            $writer->setRating(Helper::getRandomFloat(4.1, 5.0))
                ->setReviewsCount($reviewsCount)
                ->setOrdersCount($ordersCount)
                ->setSuccessRate(mt_rand(98, 100));

            $partsCount = 6;
            $subjectChunks = Helper::splitPie($ordersCount, $partsCount);
            $orderChunks = Helper::splitPie($ordersCount, $partsCount);
            $subjects = $this->em->getRepository(Subject::class)->findRandom($partsCount);
            $types = $this->em->getRepository(OrderType::class)->findRandom($partsCount);

            for ($i = 0; $i < count($subjectChunks); $i++) {
                $subject = new WriterSubject();
                $subject->setWriter($writer)
                    ->setSubject($subjects[$i])
                    ->setAmount($subjectChunks[$i]);

                $type = new WriterOrderType();
                $type->setWriter($writer)
                    ->setOrderType($types[$i])
                    ->setAmount($orderChunks[$i]);

                $this->em->persist($subject);
                $this->em->persist($type);
            }

            $this->em->persist($writer);
            $this->em->flush();
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
