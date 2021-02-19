<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Vehicle;
use App\Service\DropMileageService;
use App\Service\VehicleSearchService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    /**
     * @Route(
     *     "/api/v1/vehicles",
     *     methods={"GET"},
     *     name="app_api_vehicle_list"
     * )
     * @param Request $request
     * @param VehicleSearchService $searchService
     * @return JsonResponse
     */
    public function getVehicleList(Request $request, VehicleSearchService $searchService): JsonResponse
    {
        $params = $request->get('filter');
        $vehicles = $searchService->findByParams($params);

        /** @var Vehicle $vehicle */
        if (count($vehicles) > 0) {
            $response['status'] = 'success';
            foreach ($vehicles as $vehicle) {
                $item['id']             = $vehicle->getId();
                $item['brand']          = $vehicle->getBrand()->getName();
                $item['isNew']          = $vehicle->getIsNew();
                $item['year']           = $vehicle->getModelYear();
                $item['price']          = $vehicle->getPrice();
                $item['hasRainSensor']  = $vehicle->getHasRainSensor();
                $response['vehicles'][] = $item;
            }
        } else {
            $response['status']  = 'success';
            $response['message'] = 'No vehicles found';
        }
        return new JsonResponse($response);
    }

    /**
     * @Route(
     *     "/api/v1/vehicles/{id}/drop-mileage",
     *     methods={"GET"},
     *     name="app_api_vehicle_drop_mileage"
     * )
     * @param $id
     * @param Request $request
     * @param DropMileageService $dropMileageService
     * @return Response|null
     */
    public function dropMileage($id, Request $request, DropMileageService $dropMileageService): ?Response
    {
        $type  = $request->request->get('type');
        $value = (int)$request->request->get('value');
        /** @var EntityManager $em */
        $em = $this->get('doctrine');
        /** @var Vehicle $vehicle */
        $vehicle = $em->getRepository(Vehicle::class)->find($id);
        if ($vehicle) {
            $res = $dropMileageService->drop($vehicle, $type, $value);
            if (array_key_exists('status', $res) && array_key_exists('message', $res)) {
                if ('success' == $res['status']) {
                    return new Response ('OK', Response::HTTP_OK);
                }
                if ('error' == $res['status']) {
                    throw new BadRequestHttpException($res['message']);
                }
            }
        }
        throw new NotFoundHttpException('Vehicle with id ' . $id . ' not found!');
    }
}
