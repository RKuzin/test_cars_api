<?php


namespace App\Service;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class VehicleSearchService
{
    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function findByParams($params): array
    {
        $params = $this->fill($params);
        if ($params) {
            $vehicles = $this->em->getRepository(Vehicle::class)->findByParams($params);
        } else {
            $vehicles = $this->em->getRepository(Vehicle::class)->findAll();
        }
        return $vehicles;
    }

    /**
     * Fill the param array with an allowed attributes.
     *
     * @param array|null $attributes
     * @return array|null
     */
    public function fill(?array $attributes): ?array
    {
        $allowedParameters = [
            'brand',
            'isNew',
            'minModelYear',
            'maxModelYear',
            'minPrice',
            'maxPrice',
            'hasRainSensor'
        ];
        $params            = null;
        if (\is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                if (in_array($key, $allowedParameters)) {
                    $params[$key] = $value;
                }
            }
        }
        return $params;
    }
}