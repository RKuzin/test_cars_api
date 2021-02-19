<?php

namespace App\DataFixtures;

use App\Entity\VehicleBrand;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $brands;

    public function load(ObjectManager $manager)
    {
        // create 8 car brands
        $brandNames = ['Toyota', 'Mercedes-Benz', 'BMW', 'Honda', 'Volkswagen', 'Ford', 'Kia', 'Audi'];
        foreach ($brandNames as $brandName){
            $vehicleBrand = new VehicleBrand();
            $vehicleBrand->setName($brandName);
            $manager->persist($vehicleBrand);
            $this->brands[] = $vehicleBrand;
        }


        // create 20 vehicles
        if (count($this->brands) > 0) {
            for ($i = 0; $i < 20; $i++) {
                $vehicle = new Vehicle();
                $vehicle->setBrand($this->brands[rand(0, count($this->brands) - 1)]);
                $vehicle->setIsNew((bool)mt_rand(0, 1));
                $vehicle->setModelYear(mt_rand(1885, 2021));
                $vehicle->setPrice(mt_rand(100000, 6000000));
                $vehicle->setHasRainSensor((bool)mt_rand(0, 1));
                $vehicle->setMileage(mt_rand(100, 3000000));
                $manager->persist($vehicle);
            }
        }
        $manager->flush();
    }
}
