<?php


namespace App\Service;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class DropMileageService
{
    /** @var EntityManager $em */
    private $em;

    /** @var ValidatorInterface $validator */
    private ValidatorInterface $validator;

    /**
     * Maximum percent of mileage reduce
     * @var integer
     */
    const MAX_REDUCE_PERCENT = 95;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em        = $em;
        $this->validator = $validator;
    }

    public function drop(Vehicle $vehicle, $type, $value): array
    {
        $check = $this->validate($vehicle, $type, $value);
        if ('success' == $check['status']) {
            $currentMileage = $vehicle->getMileage();
            if ('percent' == $type) {
                $value = $currentMileage * ($value / 100);
            }
            $vehicle->setMileage($currentMileage - $value);
            if (($currentMileage - $value) < 5000) {
                $vehicle->setIsNew(true);
            }
            try {
                $this->em->persist($vehicle);
                $this->em->flush();
                $check['message'] = 'Vehicle data updated';
            } catch (ORMException $e) {
                $check['status']  = 'error';
                $check['message'] = 'Error while updating Vehicle data';
            }
        }
        return $check;
    }

    private function validate(Vehicle $vehicle, $type, $value): array
    {
        $result['status']  = 'error';
        $result['message'] = 'Validation error';
        if (in_array($type, ['miles', 'percent']) && $value) {
            $maxValue = $vehicle->getMileage() * (self::MAX_REDUCE_PERCENT / 100);
            if ('percent' == $type) {
                $maxValue = self::MAX_REDUCE_PERCENT;
            }
            $positiveNumberConstraint = new Assert\Positive();
            $maxValueConstraint       = new Assert\LessThan($maxValue);
            $errors                   = $this->validator->validate(
                $value,
                [$positiveNumberConstraint, $maxValueConstraint]
            );
            if (count($errors) == 0) {
                $result['status']  = 'success';
                $result['message'] = 'Validation is completed';
            }
        }
        return $result;
    }
}