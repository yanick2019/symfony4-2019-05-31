<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Property;

/**
 * @Route("/picture")
 */
class PictureController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }



    /**
     * @Route("/", name="picture_index", methods={"GET"}  )
     */
    public function index(PictureRepository $pictureRepository): Response
    {
        return $this->render('picture/index.html.twig', [
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/", name="picture_new", methods={"GET","POST"}  )
     */
    public function new(Request $request, PictureRepository $pictureRepository)
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);
        $id = (int)$request->get('property_id');



        # 准备函数然后 插入数据库





        //    print_r( $picture ) ; exit;
        // $data = $request->request->get('request'); 

        if ($id) {
            $property = $this->repository->find($id);

            if (null === $property) {
                return new Response('no data  ');
            }
            /* $pp = new Property() ;
            $pp->setId($id); */
            $picture->setProperty($property);
        }

        if ($form->isSubmitted()) {

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($picture);
            $this->em->persist($picture);
            $this->em->flush();
            //return new Response(json_encode('ffffff'));
            //  return $this->redirectToRoute('picture_index');
              $last_id  = $picture->getId();  //  last id 

            if ($last_id ) $pictures = $pictureRepository->findPropertyId($id);

            foreach ($pictures as $picture) {
                $files[] = "/images/properties/" . $picture->getPath();

            }
        }

        /*   return $this->render('picture/new.html.twig', [
            'picture' => $picture,
            'form' => $form->createView(),
        ]); */

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('delete'.$last_id )->getValue();

        return new Response(json_encode([
            'files' => $files,
            'id' => $last_id , 
            'csrf_token' =>$token ,



        ]));
    }

    /**
     * @Route("/{id}", name="picture_show", methods={"GET"})
     */
    public function show(Picture $picture): Response
    {
        return $this->render('picture/show.html.twig', [
            'picture' => $picture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="picture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Picture $picture): Response
    {
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('picture_index', [
                'id' => $picture->getId(),
            ]);
        }

        return $this->render('picture/edit.html.twig', [
            'picture' => $picture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="picture_delete", methods={"POST"}   )
     * 
     * 
     * 
     */
    // 默认 @Route("/delete", name="picture_delete", methods={"DELETE"}   )
    public function delete(Request $request , PictureRepository $repository ): Response
    {
        $error = 0 ;
        $id = (int)$request->get('id');
        

        $picture = $repository->find($id);
        $msgs['error'] = '';
        if ($id &&  $picture) {
            if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($picture);
                $entityManager->flush();
                  
            } else {
                $error = 'error';
            }
        }
        else
        {
            $error = 'no id ';
        }

        if (  0 !=  $error) $msgs['error'] = $error;
        //  return $this->redirectToRoute('picture_index'); 

        return new Response(json_encode([
            "msgs" => $msgs,

        ]));
    }
}
