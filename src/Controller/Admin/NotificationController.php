<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Form\NotificationType;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="app_notification_index", methods={"GET"})
     */
    public function index(NotificationRepository $notificationRepository): Response
    {
        return $this->json($notificationRepository->findAll());
    }

    /**
     * @Route("/new", name="app_notification_new", methods={"POST"})
     */
    public function new(Request $request, NotificationRepository $notificationRepository): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->submit($request->toArray());

        if (!$form->isValid()) throw new BadRequestHttpException('Invalid form data');

        $notificationRepository->add($notification, true);
        return $this->json($notification, 200, ['Content-Type' => 'application/json;charset=UTF-8']);
    }

    /**
     * @Route("/{id}/edit", name="app_notification_edit", methods={"POST"})
     */
    public function edit(Request $request, Notification $notification, NotificationRepository $notificationRepository): Response
    {
        $form = $this->createForm(NotificationType::class, $notification);
        $form->submit($request->toArray());

        if (!$form->isValid()) throw new BadRequestHttpException('Invalid form data');

        $notificationRepository->add($notification, true);
        return $this->json($notification);
    }

    /**
     * @Route("/{id}", name="app_notification_delete", methods={"DELETE"})
     */
    public function delete(Notification $notification, NotificationRepository $notificationRepository): Response
    {
        $notificationRepository->remove($notification, true);
        return new Response(Response::HTTP_SEE_OTHER);
    }
}
