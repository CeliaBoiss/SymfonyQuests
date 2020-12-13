<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public const ACTORS = ['Andrew Lincoln', 'Norman Reedus', 'Danai Gurira', 'Lauren Cohan', 'Chandler Riggs'];

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('en_US');

        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_0'));

            if ($actorName === 'Andrew Lincoln') {
                $actor->addProgram($this->getReference('program_5'));
            }

            $manager->persist($actor);
        }

        for ($j = 0; $j < 60; $j++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . $faker->regexify('[0-5]')));
            $actor->addProgram($this->getReference('program_' . $faker->regexify('[0-5]')));
            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
