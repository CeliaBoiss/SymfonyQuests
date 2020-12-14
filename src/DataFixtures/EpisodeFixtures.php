<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('en_US');

        for ($k = 0; $k <= 5; $k++) {
            for ($i = 1; $i <= 3; $i++) {
                for ($j = 1; $j <= 10; $j++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->realText($maxNbChars = 50));
                    $episode->setNumber($j);
                    $episode->setSynopsis($faker->realText($maxNbChars = 150));
                    $episode->setSeason($this->getReference('program_' . $k . 'season_' . $i));

                    $slug = $this->slugify->generate($episode->getTitle());
                    $episode->setSlug($slug);

                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
