<?php

// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Property;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use App\Notification\ContactNotification;

class HomeController extends AbstractController
{
     /**
      * @var Environment # 说明是环境变量
      */
     private $twig; # config/service.yaml line 23

     /**
      * @var PropertyRepository
      */
     private $repository;

     public function __construct(Environment $twig, PropertyRepository $repository)
     {
          $this->twig = $twig;
          $this->repository = $repository;
     }

     public function home3()
     {
          return new Response($this->twig->render('pages/home3.html.twig')); # 调用  templates/pages/home.html.twig 这个视图
     }

     /**
      * @param PropertyRepository $repository
      * @return Response
      */
     public function home(PropertyRepository $repository): Response
     {

          $properties = $repository->findLatest();



          return $this->render(
               'pages/home.html.twig',
               [
                    'properties' => $properties,
               ]
          );
     }
     public function home2()
     {
          return new Response('salut les gens home2 ');
     }


     /**
      * @Route("/biens/{slug}-{id}" , name="property.show" , requirements = {"slug":"[a-z0-9\-]*" , "id":"\d+"}  )
      * @return Response
      */

     public function show(Property $property,    string $slug, int $id, Request  $request, ContactNotification $contactNotification): Response
     {



          // $property = $this->repository->find($id);
          # $property 是通过传入的{id} 从数据库获得的数据 

          /*  
          $property->getId()  #数据库中的数据
          $property->getSlug()#数据库中的数据
          $slug # $_GET 进来的数据
          
          */

          # 如果 传入的slug和数据库的slug不同,则重新定向 
          if ($property &&   $property->getSlug() !== $slug) {
               return $this->redirectToRoute(
                    'property.show', #  route name = property.show 
                    [
                         'id' => $property->getId(),      # 令id= 数据库的id
                         'slug' => $property->getSlug(),  # slug= 数据库的slug
                         'options' => $property->getOptions(),

                    ],
                    301
               );
          }

          $contact = new Contact;
          $contact->setProperty($property);
          $form = $this->createForm(ContactType::class, $contact);
          $form->handleRequest($request);


          if ($form->isSubmitted() && $form->isValid()) {

               $contactNotification->notify($contact);

               $this->addFlash('success', "Merci de votre contacte");
               /* return $this->redirectToRoute(
                    'property.show', #  route name = property.show 
                    [
                         'id' => $property->getId(),      # 令id= 数据库的id
                         'slug' => $property->getSlug(),  # slug= 数据库的slug
 
                    ],
                    
               ); */
          }


          /*
          # 把 Property $property 放入参数里public function show(Property $property ) , 会自动调用 $this->repository->find($id);   查找数据获得property

            public function show(  string $slug , int $id ): Response
           $property = $this->repository->find($id);
           改成
           public function show(Property $property , string $slug ): Response
          
           省略代码
          */
          return $this->render(
               'pages/show.html.twig',
               [
                    'property' => $property,
                    'current_menu' => 'properties',
                    'form' => $form->createView(),
               ]
          );
     }

     /**
      * @Route("/hello")
      */
     public function hello()
     {
          return new Response('hello ');
     }
}
