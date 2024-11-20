<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $this->eventRepository->findBy([], ['startDate' => 'DESC'])
        ]);
    }

    #[Route('/event/{id}', name: 'app_event', requirements: ['id' => '\d+'])]
    public function event(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/event/add', name: 'app_event_add')]
    public function add(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', "L'évènement à bien été ajouté");

            return $this->redirectToRoute('app_events');
        }

        return $this->render('event/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/edit', name: 'app_event_edit', requirements: ['id' => '\d+'])]
    public function edit(Event $event, Request $request): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', "L'évènement à bien été modifié");
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/delete', name: 'app_event_delete', requirements: ['id' => '\d+'])]
    public function delete(Event $event): RedirectResponse
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        $this->addFlash('success', "L'évènement à bien été supprimé");

        return $this->redirectToRoute('app_events');
    }
}
