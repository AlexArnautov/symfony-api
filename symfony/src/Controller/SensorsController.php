<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SensorRepository;
use App\Service\SensorApiConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SensorsController extends AbstractController
{
    #[Route('/api/sensors', name: 'api_sensors_list', methods: 'GET')]
    public function index(SensorRepository $sensorRepository, SensorApiConverter $converter): JsonResponse
    {
        $features = [];
        $data = $sensorRepository->findAll();
        foreach ($data as $sensor) {
            $features[] = $converter->convert($sensor);
        }

        return $this->json(
            ['type' => 'FeatureCollection', 'features' => $features],
            headers: ['Access-Control-Allow-Origin' => '*']
        );
    }

    #[Route('/api/sensors/{id}', name: 'api_sensor_show', methods: 'GET')]
    public function show(SensorRepository $repository, int $id): JsonResponse
    {
        $sensor = $repository->findOrFail($id);

        return $this->json($sensor);
    }

    #[Route('/api/sensors/{id}', name: 'api_sensor_edit', methods: 'PATCH')]
    public function edit(
        SensorRepository $repository,
        Request $request,
        ValidatorInterface $validator,
        int $id
    ): JsonResponse {
        $sensor = $repository->findOrFail($id);
        $sensor->setLatitude($request->get('latitude'));
        $sensor->setLongitude($request->get('longitude'));

        $errors = $validator->validate($sensor);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            throw new BadRequestHttpException($errorsString);
        }

        $repository->save($sensor);

        return $this->json($sensor);
    }
}
