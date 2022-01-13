<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Repository\ArchiveRepository;
use App\Repository\TypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    private TypeRepository $typeRepository;
    private ArchiveRepository $archiveRepository;

    /**
     * @param TypeRepository $typeRepository
     * @param ArchiveRepository $archiveRepository
     */
    public function __construct(TypeRepository $typeRepository, ArchiveRepository $archiveRepository)
    {

        $this->typeRepository = $typeRepository;
        $this->archiveRepository = $archiveRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $types = $this->typeRepository->findAll();
        $archives = $this->archiveRepository->findAll();

        for ($j = 1; $j <= 100; $j++) {
            $article = new Article();
            $article->setTitre($faker->sentence(5));
            $article->setUtitre($faker->sentence(1));
            $article->setAnnee($faker->randomElement($archives));
            $article->setType($faker->randomElement($types));

            $line = '';
            $sentences = $faker->sentences(10);
            foreach ($sentences as $sentence) {
                $line .= $sentence . ' ';
            }
            $article->setContenu($line);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
