<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\MessagesRequestDto;
use App\Repository\MessageRepository;
use Carbon\CarbonImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessagesController extends AbstractController
{
    #[Route('/api/messages', name: 'api_messages_list', methods: 'GET')]
    public function index(MessageRepository $repository, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $messagesRequestDto = new MessagesRequestDto(
            CarbonImmutable::parse($request->query->get('start')),
            CarbonImmutable::parse($request->query->get('end')),
            $request->get('ids')
        );

        $errors = $validator->validate($messagesRequestDto);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            throw new BadRequestHttpException($errorsString);
        }

        $data = $repository->findBetweenDates(
            $messagesRequestDto->getIds(),
            $messagesRequestDto->getStartDate(),
            $messagesRequestDto->getEndDate()
        );

        return $this->json($data);
    }
}
