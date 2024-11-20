<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $event = new Event();
            $event->setName($faker->sentence(3));
            $event->setDescription($faker->text);
            $startDate = $faker->dateTimeBetween('-1 year');
            $event->setStartDate(\DateTimeImmutable::createFromMutable($startDate));
            $event->setEndDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween($startDate, '+1 year')));

            $manager->persist($event);
        }

        $manager->flush();
    }
}
