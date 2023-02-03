<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="user_notification_index", methods={"GET"})
     */
    public function index(NotificationRepository $notificationRepository, Request $request): Response
    {
        $offset = $request->get('start');
        return $this->json($notificationRepository->findBy([], ['createdAt' => 'asc'], null, $offset));
    }

    /**
     * @Route("/{id}/view", name="user_notification_view", methods={"POST"} )
     */
    public function view (Notification $notification, NotificationRepository $notificationRepository): Response {
        $notification->incrementViews();
        $notificationRepository->add($notification, true);

        return new Response('');
    }


}
