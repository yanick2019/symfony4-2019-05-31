<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Property;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/df" )  
     */
/* 
    // Include the paginator through dependency injection, the autowire needs to be enabled in the project
    public function index(Request $request, PaginatorInterface $paginator)
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $appointmentsRepository = $em->getRepository(Property::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
              ->getQuery();

        // Paginate the results of the query
        $appointments = $paginator->paginate(
            // Doctrine Query, not results
            $allAppointmentsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        // Render the twig view
        return $this->render('default/index.html.twig', [
            'appointments' => $appointments
         ]);
    } */
}
