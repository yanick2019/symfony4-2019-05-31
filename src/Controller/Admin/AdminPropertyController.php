<?php
// src/controller/AdminPropertyController.php



namespace App\Controller\Admin;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Property;
use App\Form\PropertyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
  

class AdminPropertyController extends AbstractController
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
     * @Route("/admin" , name = "admin.property.index")
     * @return Response
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render("admin/property/index.html.twig", compact('properties'));
    }

    /**
     * @Route("/admin/property/create" , name ="admin.property.new" )
     */
    public function new(Request $request)
    {
        $property = new Property(); #这里的$property没读取数据库,是空的
        $form =  $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # 提交到数据库 需要用到 use Doctrine\Common\Persistence\ObjectManager; public function __construct(PropertyRepository $repository, ObjectManager $em ){$this->em = $em;}
            $this->em->persist($property); # 比下面的函数edit() 多了这一句 因为$property 是手动加的 
            $this->em->flush();
            $this->addFlash('success', 'Bien creer avec succes');

            return $this->redirectToRoute("admin.property.index");
        }
        return $this->render(
            "admin/property/new.html.twig",
            [
                'property' => $property,
                'form' => $form->createView()
            ]
        );
    }

    /**
     *  @Route("/admin/property/{id}" , name = "admin.property.edit" , methods="GET|POST"  ) 
     */
    public function edit(Property $property,  Request $request ) # 如果参数有 $property 程序会根据传来的{id} 查找表property id ={id} 的数据
    {
        /*  $option = new Option();
        $property->addOption($option); */



        $form =  $this->createForm(PropertyType::class, $property); # load $property  that  id  = { id } 

        # 接受 request 来的数据 包括get post 要先载入use Symfony\Component\HttpFoundation\Request; 
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            /* 
             if ($property->getImageFile() instanceof UploadedFile) {
                $cacheManager->remove($helper->asset($property, 'imageFile')); # $helper->asset($property, 'imageFile') 和 $property->getfilename() 都是老图片的地址 所以就是通过老图片 删除该图片的缩略图缓存
            } 
            */


            # 提交到数据库 需要用到 use Doctrine\Common\Persistence\ObjectManager; public function __construct(PropertyRepository $repository, ObjectManager $em ){$this->em = $em;}
            # 如果$property 不是当前方法的参数则需要加 $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien modifie avec succes');
            /*
            
            return $this->redirectToRoute("admin.property.index");
            */
        }


        return $this->render(
            "admin/property/edit.html.twig",
            [
                'property' => $property,
                'form' => $form->createView()
            ]
        );
    }

    /**
     *  @Route("/admin/property/{id}" , name = "admin.property.delete" , methods = "DELETE"  ) 
     */
    # 删除数据
    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid("delete" . $property->getId(), $request->get('_token'))) {
            // dump("suppression");
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimer avec succes');

            // return new Response("suppression");

        }
        return $this->redirectToRoute("admin.property.index");
    }
}
