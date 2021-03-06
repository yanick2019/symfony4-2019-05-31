<?php

// src/Controller/PropertyController.php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\propertySearch;
use App\Form\PropertySearchType;
use App\Repository\PictureRepository ;

 


class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager # 需要 use Doctrine\Common\Persistence\ObjectManager ;  
     */
    private $em;

    private $picRep ;



    public function __construct(PropertyRepository $repository, ObjectManager $em , PictureRepository $picRep  )
    {
        $this->repository =  $repository; # 引用src/Repository/PropertyRepository
        $this->em = $em;
        $this->picRep = $picRep ;
    }

    /**
     * @Route("/biens",name="property.index") # 对应 path('property.index')或config/routes.yaml 中的 property.index:
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        #用use Knp\Component\Pager\PaginatorInterface; 分页

 



        #建立表
        $search = new propertySearch();

        $form = $this->createForm(PropertySearchType::class, $search); #载入src/form/PropertySearchType.php的数据结构

        $form->handleRequest($request); # 获取$_GET的数据并把这些数据赋值给 $search ;


        //   $resultCount = $this->repository->findAllVisibleQuery($search)->getResult();
        //    $pageCount = count($resultCount);


      //  $pageCount =  $this->repository->findCount($search);

        #$this->findData() ;
        $limit = 36 ;
        $query = $this->repository->findAllVisibleQuery($search);

        

        $properties = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $limit  /*limit per page*/
        );
        
        if( count(  $properties->getItems() ) > 0 )
        {
            $pictures =  $this->picRep->findPicForProperty( $properties->getItems() );  

            foreach( $properties as  $property )
            {
                if( $pictures->containsKey( $property->getId()))
                {
                    $property->setPicture($pictures->get( $property->getId() ));
                }
             }  
        }
        
 
        # 这两句要放在$properties = $paginator->paginate( .... 的下面 否则  property/index.html.twig 里 knp_pagination_render(properties)  会失效

  
        # 需要引用 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 并继承 extends AbstractController
        return $this->render(
            "property/index.html.twig",
            [
                 
                'form' => $form->createView(),
                'properties' => $properties,
                'current_menu' => 'properties', # 给视图文件传值 令 变量 current_menu = properties
                 
            ]
        );
    }
	/**
	* @Route("tmt")
    */
    public function ctat()
    {
        return $this->json([
            ['name'=>'cats'],
            ['name'=>'cats'],
        ]);
    }



    /**
     * @Route("/mybiens",name="property.myindex") # 对应 path('property.index')或config/routes.yaml 中的 property.index:
     * @return Response
     */
    public function myindex(Request $request): Response
    {


        #用 use Doctrine\ORM\Tools\Pagination\Paginator ;分页
        $limit = 12;
        $page =  $request->query->getInt('page', 1);
        $offset = $limit * ($page - 1);

        $paginator = new Paginator($this->repository->findVisibleQuery()->getQuery());

        $paginator->getQuery()
            ->setFirstResult($offset) // Offset
            ->setMaxResults($limit); // Limit




        $properties =   $paginator;


        return $this->render(
            "property/index.html.twig",
            [
                'properties' => $properties,
                'current_menu' => 'properties',
            ]
        );

        /*
        https://github.com/KnpLabs/KnpPaginatorBundle/issues/392
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM AppBundle:Product p WHERE p.enabled = :enabled AND p.private = :private AND p.approved = :approved AND p.thumb >= :thumb GROUP BY p.name ORDER BY p.thumb DESC, p.created ASC";
        $query = $em->createQuery($dql);
        $query->setParameters( array(
        'enabled' => 1,
        'private' => 0,
        'approved' => 1,
        'thumb' => 0
        )); */
    }


    #获取数据
    public function getData()
    {

        $repository = $this->getDoctrine()->getRepository(Property::class); #会调用  App\Entity 下 Property类 
        dump($repository); # 在页面最下面瞄准镜里查看


    }
    #获取数据2
    public function getData2(PropertyRepository $repository)
    {


        dump($repository);
    }


    #获取数据 个别数据
    public function findData()
    {

        #单个 
        # $property =  $this->repository->find(1); # property表 id为1 的数据 find(id)

        #all数据 
        #$property =  $this->repository->findAll(); 

        # 单个条件数据 
        #$arrFind = ['floor' => 4 ] ;
        # $property =  $this->repository->findOneBy($arrFind); 

        # 所有条件数据 
        #$arrFind = ['floor' => 4 ] ;
        #$property =  $this->repository->findBy($arrFind); 

        #自定义语句查找
        # 函数 findAllVisible 是自己在// src/Repository/PropertyRepository里定义的

        $property =  $this->repository->findAllVisible();

        #把0号数据中的sold设为 true ;
        $property[0]->setSold(true); # 准备语句 ; 
        $this->em->flush(); #操作数据库

        dump($property);
    }



    public function insertData()
    {
        ## 插入数据库 

        # 调用 //src/entity/property.php  需要先调用 use App\Entity\Property ; # 命名空间 App\Entity\ 类Property
        $property = new Property();

        # 设置数据
        $property
            ->setTitle("Mon premier bien")
            ->setPrice(2000000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription("Une petit description l'ete l'opera ")
            ->setSurface(66)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity('Montpellier')
            ->setAddress("15 boulevard Gambetta")
            ->setPostalCode('34000');
        # 准备函数然后 插入数据库
        $em =  $this->getDoctrine()->getManager();
        $em->persist($property);
        $em->flush();
    }

    // Include the paginator through dependency injection, the autowire needs to be enabled in the project
    public function default(Request $request, PaginatorInterface $paginator)
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $appointmentsRepository = $em->getRepository(Appointments::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
            ->where('p.status != :status')
            ->setParameter('status', 'canceled')
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
    }
}
