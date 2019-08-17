 

  
git remote add origin https://github.com/wangphp2010/symfony4-tuto.git  
git push -u origin master  

 


连接 github  
	git remote add origin https://github.com/wangphp2010/symfony4-tuto.git     
	git push -u origin master 推master分支到github  
	git push -u origin dev 推dev分支到github  
查看连接  
	git remote -v show  
断开链接  
	git remote rm origin  





	
	


先安装composer 确定环境变量PATH 里有 C:/composer
在git 的 git CMD 输入
	composer create-project symfony/website-skeleton MaSuperAgence
	下载symfony4 所需
	

What's next?


  * Run your application:
    1. Go to the project directory
    2. Create your code repository with the git init command
    3. Download the Symfony CLI at https://symfony.com/download to install a dev
elopment web server,
       or run composer require server --dev for a minimalist one

  * Read the documentation at https://symfony.com/doc


 Database Configuration


  * Modify your DATABASE_URL config in .env

  * Configure the driver (mysql) and
    server_version (5.7) in config/packages/doctrine.yaml


 How to test?


  * Write test cases in the tests/ folder
  * Run php bin/phpunit
  

  
  一般操作的文件夹是 
  config  操作routes.yaml
  src  操作php代码
  templates 操作前端

  

### 配置mysql所需用户名和密码
	根目录 .env文件
		DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name 改为
		DATABASE_URL=mysql://root:wxz@127.0.0.1:3306/masuperagence
	修改配置文件  config/packages/doctrine.yaml  
	
### 启动symfony服务器 
	先cd 到项目所在文件夹
```bash

	$ php -S 127.0.0.1:8000 -t public

	$ php bin/console server:run 192.168.0.1:8080
 	$ php bin/console server:run *:8080
	$ php bin/console server:run 0.0.0.0:8000

	$ php bin/console 可获取各种指令语法
	$ php bin/console server:run	获取指令后 应该这样输入
	#Press Ctrl-C to quit. #按ctrl-c 关闭symfony服务器

```
 	
	
	
### 找出route bug	 debug
	php bin/console debug:router 查看所有路由
	
	php bin/console debug:container 查找要使用某容器需要引入的命名空间 比如要使用twig 要应用Twig\Environment
	php bin/console debug:autowiring 查看那些服务会自动加载的 不需要在services.yaml定义的
	
	
	
### 改变处理配置route
		config/routes.yaml
			方法
				home:	#名称
				path:/  #路径
				controller: App\Controller\HomeController::index # App\Controller\  命名空间(namespace)  HomeController类 index函数
				
				
### 改变处理配置Controller
		src/Controller\
			新建文件 HomeController.php
					代码
					namespace App\Controller;
					
					use Symfony\Component\HttpFoundation\Response
					
					class HomeController{ 
					#类名要与文件名相同


						public function index():Response # 要使用 Response 要先引入use Symfony\Component\HttpFoundation\Response
						{

						}
					}
					
					
直接在controller里写routes 不必去config/routes.yaml定义
有时候需要 先运行 这个语句才可以用 composer require annotations 
 	
	use Symfony\Component\Routing\Annotation\Route; #直接用@Route 需要应用这个

	class LuckyController
	{
	    /**
		 * @Route("/biens",name="property.index") # name="property.index" # route 后面一定要用双引号 对应 html.twig格式文件中的链接: {{ path('property.index') }}或config/routes.yaml 中的 property.index:
		 * @return Response
		 */
		public function number()
		{
			// this looks exactly the same
		}
	}
	



### 使用twig时			
	public function __construct($twig)
    {
         $this->twig = $twig ; 
    }
    
    public function home3()
    {
         return new Response( $this->twig->render('pages/home.html.twig'));
    }
	再修改services.yaml 
	services.yaml #定义环境参数	
	
	# 这三句可以不用写 因为symfony4中 twig会自动加载
	App\Controller\HomeController: #最前面一定要空一个tab
		tags: ['controller.service_arguments']
        arguments:
            $twig: "@twig" # @twig 载入twig服务
			
			
			

### 建立mysql数据库 步骤 
	1. php bin/console doctrine:database:create 会根据 根目录 .env文件 建立  DATABASE_URL=mysql://root:wxz@127.0.0.1:3306/masuperagence 定义的数据库名:masuperagence
	如果出现 PDOConnection.php   could not find driver 需要在php.ini 加载extension=php_pdo_mysql.dll
	
 	2. php bin/console make:entity #Creates or updates a Doctrine entity class, and optionally an API Platform resource
		会要求输入 class name of the entity 
		自己取名 如: Property
		symfony 会 创建文件	
					created: src/Entity/Property.php
					created: src/Repository/PropertyRepository.php
		接下来 会要求输入表的字段 例如 (自己取名就行) 名称    		类型(输入? 会提示各种类型)     长度   是否空
											  title    		string  					   255     not
											  description    text   				       255     yes
											  
		最后问是否要增加新的例 , 如果不增加新例,按回车结束
		刚刚这些值 会写入Property.php 然后会把这些值写入数据库中的表:Property
		
		接下来输入 php bin/console make:migration 如果提示utf8mb4错误 修改文件  config--packages--doctrine.yaml 
			--建立准备文件 src\Migrations\Version20190528215650.php #里面有在数据库建立表:Property的语法
			--写入数据库 建立 表 migration_versions
		
		检查src\Migrations\Version20190528215650.php 如果建立表:Property的语法 没有问题 
		把表结构Property写入数据库 php bin/console doctrine:migrations:migrate 同时 会把表版本号Version20190528215650 写入表 migration_versions
		
### 修改数据库表		
	增加结构 表Property
		php bin/console make:entity Property
	增加完后 php bin/console make:migration	
	也可以直接修改Property.php 
		改完后 删除最后一个src\Migrations\Version20190528215650.php
		再一次 php bin/console make:migration
	最后 将结构更新写入数据库 	php bin/console doctrine:migrations:migrate
				
				
				
				
				
				
 ## 插入数据库 

        # 需要先调用 //src/entity/property.php  方法use App\Entity\Property ; 
        $property = new Property() ;

        # 设置数据
        $property
            ->setTitle("Mon premier bien")
            ->setPrice(2000000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription('Une petit description')
            ->setSurface(66)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity('Montpellier')
            ->setAddress("15 boulevard Gambetta")
            ->setPostalCode('34000') ;
			
        # 准备函数然后 插入数据库
        $em =  $this->getDoctrine()->getManager();
        $em->persist($property) ;
        $em->flush() ; 
				

## 建立form 
	php bin/console make:form 
	接下来会要求填入
		1 类型 如 PropertyType 最好与表名相同 ,
		2 entity的名字 ru Property 与表名相同
	会在src/form下创建文件PropertyType.php 里面会根据src/entiry/property.php 里面的值建立
	

form 视图文件C:\htdocs\symfony\MaSuperAgence\vendor\symfony\twig-bridge\Resources\views\Form
修改文件 : config/packages/twig.yaml
		增加form_themes: 这里一定要空格['bootstrap_4_layout.html.twig']
		
#安全策略 管理安全的yaml 登录等等 
	config/packages/security.yaml
	--> 
		修改
		为
					
		providers:
			in_memory: 
				memory: 
					users:
						demo:
							password: demo
							roles: ROLE_ADMIN
		
		
		main:
            http_basic: true  
		access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }	增加这行: route:amdin , 只允许 ROLE_ADMIN 
		
		encoders:
			Symfony\Component\Security\Core\User\User: plaintext # 加密方式 plaintext: 不加密密码 ps:使用空格进行缩进

	#退出刚才的登录
		http://log:out@localhost:8000/admin
		
	#管理登录页面
		创建(数据库表User)entity User
			php bin/console make:entity User #(如果要增加表的字段 在输入一次php bin/console make:entity User 具体方法查看 "修改表 199行" )
			
			#会建立文件
				src/Entiry/User.php
				src/Repository/UserRepository.php
			接下来输入表需要的字段 如user username ps time email 等	
			
			php bin/console make:migration # 准备载入到数据库中会建立版本文件src/migrations/version数字.php
			php bin/console doctrine:migrations:migrate # 在数据库中建立表user
		
		修改安全策略以便其可以通过数据库管理
			//config/packages/security.yaml	
			
			providers:
				from_database:
					entity:
						class: App\Entiry\User
						property: username #通过username管理
			
			firewalls:
			 	main:
					# 自定义禁止访问页面
					access_denied_handler: App\Security\AccessDeniedHandler 
					# 建立文件 src\App\Security\AccessDeniedHandler.php
			
			#加密方式
			encoders:
				App\Entity\User:
					algorithm: bcrypt # algorithm:算法 即加密方法
					cost: 12 #加密强度 12为最强		
		
		修改src/Entiry/User.php
			
			增加use Symfony\Component\Security\Core\User\UserInterface;
			修改class User 为 class User implements UserInterface,\Serializable
				
			增加
				/**
				 *  Returns the roles granted to the user.
				 *  
				 *         public function getRoles()
				 *          {
				 *              return array("ROLE_USER");
				 *          }
				 *  Alternatively, the roles might be stored on a ``roles`` property,
				 *  and populated in any number of different ways when the user object
				 *  is created
				 * 
				 *  @return (Role|string)[] the user roles
				 */
				public function getRoles()
				{
					// TODO: Implement getRoles() method.
					return ['ROLE_ADMIN'];
				}

				/**
				 * Return the salt that was originally used to encode the password
				 * this can return null if the password was not encoded using a salt.
				 * 
				 * @return string:null The salt
				 */
				public function getSalt()
				{
					// TODO: Implement getSalt() method
					return null ; 
				}
				/**
				 * Removes senstive data from the user.
				 * 
				 * This is important if , at any given point , sensitive information like
				 * the plain-text password is stored on this object.
				 */
				public function eraseCredentials()
				{
					//TODO: Implement eraseCredentials() method 
					 
				}
				/**
				 * String representation of object
				 * @return string the string representation of the object or null
				 * 
				 */

				public function serialize()
				{
					return serialize([
						$this->id , 
						$this->username,
						$this->password,
						
					]);
					
				}
				/**
				 * Constructs the object
				 * @link https://php.net/manual/en/serializable.unserrialize.php
				 * @param string @serrialized <p>
				 * The string representation of the object
				 * <p>
				 * @return void
				  */
				public function unserialize($serialized)
				{
					list(
						$this->id , 
						$this->username,
						$this->password
					) = userialize($serialized,['allowed_class' => false ]);
				}
				
				
				
				
# 查看安全策略参数
	php bin/console config:dump-reference security
	
	如果出现 La requête d'authentification n'a pas pu être executée à cause d'un problème système 错误 在security.yaml找找看
	
# 下载 安装	fixtures (用来加载测试用的数据) 因此只在开发程序时使用 所以加 --dev
	composer require  orm-fixtures --dev
	
	建立 Userfixtures 
		php bin/console make:fixtures
		接下来输入 Userfixtures 可以自己取名 会创建文件src/DataFixtures/UserFixtures.php
		修改src/DataFixtures/UserFixtures.php
		
		运行 php bin/console doctrine:fixtures:load 载入设置好的fixtures
			会提问是否清空数据库 当然N
		或者运行 php bin/console doctrine:fixtures:load --append 只在数据库增加数据
	
	
	建立 Propertyfixtures
		php bin/console make:fixtures
		输入Propertyfixtures
		修改src/DataFixtures/Propertyfixtures.php
			安装 faker : composer require fzaninotto/faker(用来产生各做虚拟数据 这样就不需要自己一个个写了)
		修改好src/DataFixtures/Propertyfixtures.php后	
		运行 php bin/console doctrine:fixtures:load #会执行src/datafixtures/propertyfixtures.php 里面的load方法  会清空数据库表Property并插入新的数据


# 建立分页
	安装https://github.com/KnpLabs/KnpPaginatorBundle
	指令 composer require knplabs/knp-paginator-bundle
	创建文件 config/knp_paginator.yaml


		
	
				
在src/entity 创建 propertySearch.php
	修改propertySearch.php
	php bin/console make:form 
	输入 PropertySearchType
	输入 \App\Entity\PropertySearch
	修改src/from/PropertySearchType.php
	
	修改 src/Controller/PropertyController.php
	修改 templates\property/index.html.twig
	修改 src/Repository/PropertyRepository 
	
	修改src/entiry/propertySearch.php 
		增加use Symfony\Component\Validator\Constraints as Assert ; # 检验输入的数值是否有效
		@Assert\Range(min=10,max=400) # 这个范围是10-400 否则无效
	修改 templates\property/index.html.twig
		修改	<div class="form-row "  >
			--> <div class="form-row align-items-end "  >
		把<button class="btn btn-primary">Rechercher</button> 
		放到这里 
			像这样<div class="col">
                    <div class="form-group">
                        <button class="btn btn-primary">Rechercher</button>
                    </div>

                </div>
		这样,就算弹出错误信息,表的input还是会保持在同一行
		

# 建立数据库及相信文件指令
 	php bin/console make:entity
		> Option # option表
		> name( string  255 no null )  #name 字段
		> properties (relation Property ManyToMany yes options ) # 会建立表option_property 及外键option_id连接 表option id 外键property_id连接表Property id 
	php bin/console make:migration 	
	php bin/console doctrine:migrations:migrate

# 为option建立crud 这个指令会创建一系列相关文件 以免去了手动创建
	
	php bin/console make:crud Option 
		
		# created: src/Controller/OptionController.php
		# created: src/Form/OptionType.php
		# created: templates/option/_delete_form.html.twig
		# created: templates/option/_form.html.twig
		# created: templates/option/edit.html.twig
		# created: templates/option/index.html.twig
		# created: templates/option/new.html.twig
		# created: templates/option/show.html.twig
		 修改 src/entity/option.php
			加 @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
   				@Orm\Table(name="`Option`") # 要加这句否则 mysql会有字符冲突Option
		 
		
		修改文件//src/form/optiontype.php
			注释掉 ->add('properties') 不然会报错
	把文件//src/controller/OptionController.php 放到 //src/controller/Admin/ 并改名为 AminOptionController.php 
		修改类名与文件名相同 里面的html.twig文件 都写 admin/
	在templete/admin下建立option文件夹并复制 templete/admin/property下的.twig文件到templete/admin/option 然后修改里面的文字
	
	
	修改 src/Form/PropertyType.php 
		增加  ->add('options',EntityType::class,[
               'class'=>Option::class,
               'choice_label'=>'name',
               'multiple'=> true ,#use App\Entity\Option;

           ]) 
 
		
## 很重要	
	# /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="properties") #所有者用inversedBy 非所有者用mappedBy 结果就是查数据时会 找表 所有者_非所有者 如 table property_option 
     */
    private $options;

	  /**
		 * @ORM\ManyToMany(targetEntity="App\Entity\Property", mappedBy="options") #所有者用inversedBy 非所有者用mappedBy 结果就是查数据时会 找表 所有者_非所有者 如 table property_option
		 */
		private $properties;	
		
##  Image à la une 加入图片 图片处理

		安装
			upload bundle 
			网址 https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/installation.md
		
		composer require vich/uploader-bundle
			选择y 表示信任这个包, 安装继续直至完成
		
		修改 config/pakage/vich_uploader.yaml
			  选择 db_driver: orm 
			  
		这个bundle使用
			修改 config/packages/vich_uploader.yaml
					
				vich_uploader:
					db_driver: orm

					mappings:
						property_image:
							uri_prefix: /images/properties # 图片的前缀
							upload_destination: '%kernel.project_dir%/public/images/properties' # 图片上传地址
			修改 // src/entity/property.php
				增加
					use Symfony\Component\HttpFoundation\File\File;
					use Vich\UploaderBundle\Mapping\Annotation as Vich;
					* @Vich\Uploadable */
					
					********
			改好 src/entity/property.php 后 需要更新 表property数据库结构了
				准备 php bin/console make:migration 检查 src/migrations/version 文件是否正确
				再	 php bin/console dcctrine:migrations:migrate
				
				
# 安装图片缩略图包 这个缩略图在缓存里 但是 删除数据库等 操作都不会对 缩略图操作 所以要增加监听事件来处理
	composer require liip/imagine-bundle
		修改 config/packages/liip_imagine.yaml 
		
				liip_imagine:
						filter_sets:
							thumb:
								quality: 75
								filters:
									thumbnail:
										size: [360,230]
										mode: outbound
	
	
		在要所需缩略图的后面加 | imagine_filter('thumb')
			例如 <img alt="" class="card-img-top" src="{{ vich_uploader_asset(property,'imageFile') | imagine_filter('thumb') }}" style="width:100%;height:auto;"> 
			
	修改删除图片的同时操作缩略图
		方法一
			src/controller/AdminPropertyController.php
			
				在edit和delete方法里修改
					增加
						参数
							CacheManager $cacheManager #use Liip\ImagineBundle\Imagine\Cache\CacheManager;
							UploaderHelper $helper #use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
						语句
							  if ($property->getImageFile() instanceof UploadedFile) 
							  {
									$cacheManager->remove($helper->asset($property, 'imageFile')); # $helper->asset($property, 'imageFile') 和 $property->getfilename() 都是老图片的地址 所以就是通过老图片 删除该图片的缩略图缓存
								} 
					
		方法二
			创建监听文件 src/Listener/imageCacheSubsriber.php
			修改该文件
			因为不是在注册过的文件夹(Listener)所以 不会自动加载
			所以要修改 config/services.yaml
				增加
					    App\Listener\ImageCacheSubscriber:
						tags: 
						- { name: doctrine.event_subscriber }
						

# Formulaire de contact		
	创建entity  //src/entity/Contact.php  #这个不需要插入数据库的
		type     src/form/ContactType.php
				
		


	下载测试邮件服务器
		https://github.com/rnwood/smtp4dev/releases/tag/3.1.0-ci0552
		解压缩  运行Rnwood.Smtp4dev.exe(就在第一级文件) 
		打开http://localhost:5000/ 测试收件箱
		设置.env 
			MAILER_URL=smtp://localhost:25


#Encore Installation
	要先按node.js 和yarn 
	
		composer require encore 
		yarn 
	开启服务器 
		npm run dev-server 
	
	# 如果在 *.html.twig 里加  <link href="{{ asset('build/app.css')}}" rel="stylesheet">代表 assets/css/app.css 所以可以把所有css或js文件放到assets里
	# 
	# 同理  <script src="{{ asset('build/app.js')}}"></script>代表 assets/js/app.js 




#安装缓存cache 这样就不用每次搜索数据库了

	https://github.com/twigphp/twig-cache-extension 

	$ composer require twig/cache-extension 
	
	使用缓存:
	在config/services.yaml 写入代码
		 #载入缓存所需
			Twig\CacheExtension\CacheProviderInterface:
				class: Twig\CacheExtension\CacheProvider\PsrCacheAdapter    
			Twig\CacheExtension\CacheStrategyInterface:
				class: Twig\CacheExtension\CacheStrategy\LifetimeCacheStrategy

			Twig\CacheExtension\Extension:
				tags:
				- { name: twig.extension }

	或者 : 
	use Twig\CacheExtension\CacheProvider\PsrCacheAdapter;
	use Twig\CacheExtension\CacheStrategy\LifetimeCacheStrategy;
	use Twig\CacheExtension\Extension as CacheExtension;
	use Cache\Adapter\Apcu\ApcuCachePool;

	$cacheProvider  = new PsrCacheAdapter(new ApcuCachePool());
	$cacheStrategy  = new LifetimeCacheStrategy($cacheProvider);
	$cacheExtension = new CacheExtension($cacheStrategy);

	$twig->addExtension($cacheExtension);


	修改 templates\property\_property.html.twig		
		
		{% cache 'property' ~ property.id    900    %} 

			.....
		{% endcache %}
		
				
		 




# 问题 issues

## vscode emmet支持twig

文件---首选项---设置---扩展---emmet
```{
   
  "emmet.includeLanguages": {
    "html.twig": "html",
    "twig": "html"
  },
    "emmet.showSuggestionsAsSnippets": true,
   
    "emmet.optimizeStylesheetParsing": false,
    "emmet.syntaxProfiles": {
      "html.twig": "html",
      "twig": "html"
    },
    "emmet.triggerExpansionOnTab": true
  
}
```


## 指令 教程
	
### 让程序载入自己写的bundel
+ 创建 lib/MyBundle/MyBundle.php
```php
			namespace MyBundle;
			use Symfony\Component\HttpKernel\Bundle\Bundle;
			class MyBundle extends Bundle {....}
```

+ 修改 composer.json >> 
```			
"autoload": {
			"psr-4": {
				"App\\": "src/" ,
				"MyBundle\\":"lib/MyBundle/", # 在根目录lib/MyApp/
			}
		},
```
+ 指令 composer dump-autoload  # 更新一下刚刚的上面的修改 使上面的修改起作用 

+ 修改 config/bundle.php
	+ 增加 MyBundle\MyBundle::class =>['all'=> true ], ## MyBundle\MyBundle::class   载入"MyBundle\\":"lib/MyBundle/"  下的MyBundle.php中的MyBundle class



	
	
	
### 自己写type 方法 
+ 创建文件 lib/MyBundle/MyType.php
``` 
	namespace MyBundle\Type; # "MyBundle\\":"lib/MyBundle/", # lib/MyBundle/ 文件夹 下type文件夹
	
	use Symfony\Component\Form\AbstractType ; #一定要这个 
	class MyType extends AbstractType{....#按照其他type文件写法}
```
	 			

```
  public function index( ):Response
    {
       
        #$this->findData() ;

        return $this->render(
            "property/biens.html.twig",
            [
                'current_menu'=>'properties', # 给视图文件传值 令 变量 current_menu = properties
            ]
        ); # 需要引用 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 并继承 extends AbstractController

    }
```	

### 直接route
```
	# 需要引用 use Symfony\Component\Routing\Annotation\Route ;

	 /**
     * @Route("/login" , name="login")
     */
```	
### 加密密码 
```
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use App\Entity\User;



	// ...
	private $encoder ;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder ;

    public function __construct( UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder ;
    }
	 $user = new User();
	 $this->encoder->encodePassword(  $user , 'demo'); #加密
```

	 
###返回老的数据库结构
	php bin/console doctrine:migrations:status #获得migrations 状态
	找previous Version: 2019-05-31 02:43:08 (20190531024308) 后面括号的版本号
	输入 php bin/console doctrine:migrations:migrate 20190531024308 会让数据库返回这个状态 并在src/migrations 文件加下 删除对应的版本文件
	然后再一次
		准备 php bin/console make:migration
		查看新生成src/migrations 版本文件是否正确
		在 加载到数据库 php bin/console doctrine:migrations:migrate
		

###文件作用
	src/
		controller # 控制器
		form	   # 表单<form>控制器
		entity     # 数据库表结构控制器
		repository # 操作数据库

### 自定义page access denied 访问需要管理员权限的页面时

```
	#自定义禁止访问页面
	access_denied_handler: App\Security\AccessDeniedHandler 
	# 建立文件 src\Security\AccessDeniedHandler.php
```	 
### 重写错误页面 自定义page 404 ...
测试地址 http://localhost:8000/index.php/_error/403/404/500

创建文件

```
templates/
└─ bundles/
   └─ TwigBundle/
      └─ Exception/
         ├─ error404.html.twig
         ├─ error403.html.twig
         ├─ error.html.twig      # All other HTML errors (including 500)
         ├─ error404.json.twig
         ├─ error403.json.twig
         └─ error.json.twig      # All other JSON errors (including 500)
```	

各种错误样式

```twig
{# templates/bundles/TwigBundle/Exception/error404.html.twig #}
{% extends 'base.html.twig' %}

{% block body %}
    <h1>Page not found</h1>

    <p>
        The requested page couldn't be located. Checkout for any URL
        misspelling or <a href="{{ path('homepage') }}">return to the homepage</a>.
    </p>
{% endblock %}		 
```

### 查看安全策略参数 php bin/console config:dump-reference security
https://symfony.com/doc/current/reference/configuration/security.html
```yaml
form_login:
	target_path_parameter: go_to
	failure_path_parameter: back_to
```

go_to , back_to 表示 登录页提交过来的post参数或是get参数 ?go_to=/dashboard&back_to=/forgot-password

```html
	<input type="hidden" name="go_to" value="{{ path('dashboard') }}" />
    <input type="hidden" name="back_to" value="{{ path('forgot_password') }}" />
```
### 登录成功跳转页面

默认是登录也的上一个地址
+ 可以在登录表单里增加
 <input type="hidden" name="_target_path" value="{{ path('account') }}" /> 这个比下面的优先
 	因为 target_path_parameter的默认值是_target_path 
+ 或者 # config/packages/security.yaml
	firewalls:
        main:
            form_login:
                # ...
                default_target_path:  / #登录成功跳转路由  登录页面没有前一页时 登录成功跳转的路由 否则跳转到登录页的前一页    

### 提供默认参数

为 src/Updates/SiteUpdateManager.php 提供默认参数 $adminEmail: 'manager@example.com'


```yaml
# config/services.yaml
services:
    # explicitly configure the service
    App\Updates\SiteUpdateManager:
        arguments:
            $adminEmail: 'manager@example.com'
``` 

```php
# src/Updates/SiteUpdateManager.php 
public function __construct($adminEmail)
    {
       $this->adminEmail = $adminEmail;
    }
```

```yaml
# config/services.yaml
services:
    # ...

    # this is the service's id 自定义服务id 
    site_update_manager.superadmin:
		#调用的class名 src/Updates/SiteUpdateManager.php 
        class: App\Updates\SiteUpdateManager
        # you CAN still use autowiring: we just want to show what it looks like without
        autowire: false
        # manually wire all arguments
		# 提供三个默认删除
        arguments:
            - '@App\Service\MessageGenerator'
            - '@mailer'
            - 'superadmin@example.com'

    site_update_manager.normal_users:
        class: App\Updates\SiteUpdateManager
        autowire: false
        arguments:
            - '@App\Service\MessageGenerator'
            - '@mailer'
            - 'contact@example.com'

	# 调用自定义服务id
    # Create an alias, so that - by default - if you type-hint SiteUpdateManager,
    # the site_update_manager.superadmin will be used
    App\Updates\SiteUpdateManager: '@site_update_manager.superadmin' 
  
```

### service tag 

https://symfony.com/doc/current/reference/dic_tags.html数据

https://symfony.com/doc/current/service_container/tags.html

方法一
```php
# config/services.yaml
services:
	
	# 需要自动加载的tag 放在_instanceof: 下面
    # this config only applies to the services created by this file
    _instanceof:
        # services whose classes are instances of CustomInterface will be tagged automatically
        App\Security\CustomInterface:
            tags: ['app.custom_tag']
    # ...
``` 	

方法二
```php
// src/Kernel.php
class Kernel extends BaseKernel
{
    // ...

    protected function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(CustomInterface::class)
            ->addTag('app.custom_tag')
        ;
    }
}
```



## reCAPTCHA 

https://www.google.com/recaptcha/admin/site/347304153

修改 composer.json
```json
 "autoload": {
        "psr-4": {
            "App\\": "src/",
        	+ "MyLib\\RecaptchaBundle\\": "lib/RecaptchaBundle"
        }
    },
```
$ composer dump-autoload



src\Entity\Contact.php
```php
#增加
private $recaptcha ;

  public function getRecaptcha(): ?string
  {
    return $this->recaptcha;
  }
  public function setRecaptcha(string $recaptcha): Contact
  {
    $this->recaptcha = $recaptcha;
    return $this;
  }
```



src\Form\ContactType.php
```php
#增加
->add('recaptcha', RecaptchaSubmitType::class,[
                'label'=>"Envoyer" # 不写则显示Recaptcha
            ])
```

config/bundles.php
```php
	+ MyLib\RecaptchaBundle\RecaptchaBundle::class => ['all' => true],
```

lib/RecaptchaBundle/Type/RecaptchaSubmitType.php
```php
<?php

namespace MyLib\RecaptchaBundle\Type ;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView ;



class RecaptchaSubmitType extends AbstractType{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "mapped"=> false # 未对应任何数据字段
        ]);

    }
    public function buildView(FormView $view , FormInterface $form , array $option )
    {
       // $view->vars["label"] = false ; 
    }
    public function getBlockPrefix()
    {
        return "recaptcha_submit"; # 定义前缀 lib/RecaptachBundle/Resources/views/fields.html.twig
    }
    public function getParent()
    {
        return TextType::class;
    }

}
```


lib/RecaptachBundle/Resources/views/fields.html.twig
```twig
# 空着
```


用 $ php bin/console debug:twig 查看 发现多了

	@Recaptcha       lib\RecaptchaBundle/Resources/views\
	@!Recaptcha      lib\RecaptchaBundle/Resources/views\

lib/RecaptachBundle/Resources/views/fields.html.twig
```twig
{% block recaptcha_submit_widget %}
    {% block submit_widget %}{% endblock %} {# 调用默认的submit按钮样式 #}
{% endblock %}
```


### 把刚刚写的fields.html.twig 加入到 twig.form.resources 这样就可以调用它 

$ php bin/console debug:container --parameters  查看定义的全局变量 

$ php bin/console debug:container --parameter=twig.form.resources 查看某个全局变量  twig.form.resources



lib/RecaptchaBundle/RecaptchaBundle.php
```php
 #增加 
 public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RecaptchaCompilerPass());
    }
```

lib\RecaptchaBundle\RecaptchaCompilerPass.php
```php
namespace MyLib\RecaptchaBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder ;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface ;

class RecaptchaCompilerPass implements CompilerPassInterface{

    public function process(ContainerBuilder $container){

        if($container->hasParameter("twig.form.resources"))
        {
            $resources = $container->getParameter("twig.form.resources")?: [] ;

            array_unshift($resources , '@Recaptcha/fields.html.twig' ) ;
            $container->setParameter( "twig.form.resources", $resources ) ;
        }
            
        
    }
}

```

刷新页面  http://localhost:8000/biens/tiel-112 发现定义按钮已经出现在里面了 

#### 重写刚刚定义按钮文字 

方法1

	lib\RecaptchaBundle\Type\RecaptchaSubmitType.php :
		增加 $view->vars["button"] = $option['label'] ;

	lib\RecaptchaBundle\Resources\views\fields.html.twig : 
		增加 {% set label = button %}  
    	{% block submit_widget %}{% endblock %} 

方法2

	或者 只要修改 lib\RecaptchaBundle\Resources\views\fields.html.twig : 增加{% set label = "Envoyer" %} , 即可 


#### 为按钮设置 class

lib\RecaptchaBundle\Resources\views\fields.html.twig : 

```tiwg
# 继承原来的 并增加class:'g-recaptcha'
{% set attr = attr | merge({class:'g-recaptcha' }) %}
```

#### 为样式文件传入参数

##### 方法一

lib\RecaptchaBundle\Resources\views\fields.html.twig

```yaml
#修改 
{% set attr = attr | merge({class:'g-recaptcha' , 'data-sitekey':key , 'data-callback':'' }) %} 
```

config\packages\recaptcha.yaml

```yaml
services:
  recaptcha.type : #定义一个名为 recaptcha.type 的服务
    class: MyLib\RecaptchaBundle\Type\RecaptchaSubmitType # 定义要载入的class
    tags: ['form.type']
    arguments:
      $key: '6LeIohoUAAAAAOtXJI_WbAwVoPiALQcg3q2JKiKX'
 ```


lib\RecaptchaBundle\Type\RecaptchaSubmitType.php

```php

增加 
/**
* @var string
*/
private $key ;

public function __construct(string $key )
{
$this->key = $key ;
}



	
public function buildView(FormView $view, FormInterface $form, array $option    )
{
    $view->vars["key"] =  $this->key ;   增加这一行	 
}	

```

##### 方法二  载入自定义 yaml 

创建 lib\RecaptchaBundle\DependencyInjection\RecaptchaExtension.php
```php
namespace MyLib\RecaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

# use MyLib\RecaptchaBundle\DependencyInjection\Configuration;

class RecaptchaExtension extends Extension  {

    /**
     * Loads a specific configuration 
     * 
     * @throws \InvalidArgumentException When proviced tag is not defined in this extension
     */
    public function load(array $configs , ContainerBuilder $container ){

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config' )
         ) ;

        $loader->load('services.yaml') ; 

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration,$configs);
        $container->setParameter('recaptcha.key' , $config['key']);
        $container->setParameter('recaptcha.secret' , $config['secret']);
        # $ php bin/console debug:container --parameter=recaptcha.key
        
    }
}
```

创建lib\RecaptchaBundle\Resources\config\services.yaml
```yaml
services:
  recaptcha.type : #定义一个名为 recaptcha.type 的服务
    class: MyLib\RecaptchaBundle\Type\RecaptchaSubmitType # 定义要载入的class
    tags: ['form.type']
    arguments:
      $key: '%recaptcha.key%'      
      #$secret: '%recaptcha.secret%'
```

创建lib\RecaptchaBundle\DependencyInjection\Configuration.php
```php

namespace MyLib\RecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface{
 
    /**
     * Generates the configuration tree builder
     * 
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder the tree builder
     */
    public function getConfigTreeBuilder(){

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('recaptcha') ; #小写bundle名
        $rootNode
            ->children()
            ->scalarNode('key')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('secret')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
        ->end() ;
        return $treeBuilder ;

    }

}
```

config\packages\recaptcha.yaml
```yaml
recaptcha : # bundle 名
  key: '6LeIohoUAAAAAOtXJI_WbAwVoPiALQcg3q2JKiKX'
  secret: '6LeIohoUAAAAANrafw8NeM5y44AzzvmoLXLq09jk'
```

#### 总结 

	1. config\packages\recaptcha.yaml 定义 值 
	2. lib\RecaptchaBundle\Resources\config\services.yaml 获取上面定义的值 给某个类的构造函数 传入这个 参数 
	3. lib\RecaptchaBundle\DependencyInjection\RecaptchaExtension.php 使用上面两个个yaml文件,ps这个文件会自动调用  
	4. 使用方法 , 在某个类的构造函数传入这个参数 如 
```php
	public function __construct(string $key )
	{
		$this->key = $key ;
	}
```	




###  自定义表单验证

当修改了translations下面的yaml翻译文件 需要运行指令清理缓存修改的内容呈现
清理缓存

```bash
$ php bin/console cache:clear
```


lib\RecaptchaBundle\Constraints\Recaptcha.php
```php
namespace MyLib\RecaptchaBundle\Constraints;

use Symfony\Component\Validator\Constraint;

class Recaptcha extends Constraint{

    public $message = 'Invalid Recaptcha' ; # 错误信息
 
}
```


lib\RecaptchaBundle\Type\RecaptchaSubmitType.php
```php
$resolver->setDefaults([
            "mapped" => false ,# 未对应任何数据字段
           + "constraints" => new Recaptcha() 
        ]);
```


lib\RecaptchaBundle\Constraints\RecaptchaValidator.php
```php
namespace MyLib\RecaptchaBundle\Constraints;

use Symfony\Component\Validator\ConstraintValidator ;
use Symfony\Component\Validator\Constraint ; 
use Symfony\Component\HttpFoundation\RequestStack ;

#需要services.yaml配合
class RecaptchaValidator extends ConstraintValidator{

    /**
     * @var RequestStack
     */
    private $requestStack ;

    public function __construct(RequestStack $requestStack )
    {
        $this->requestStack = $requestStack ;
    }

    /**
     * Check if the passed value is valid
     * 
     * @param mixed $value The value that should be validated
     * @param Contraint $constraint The contraint for the validation
     */
    public function validate($value , Constraint $constraint )
    {
        $recaptchaResponse = $this->requestStack->getCurrentRequest()->request->get('g-recaptcha-response')  ; 
        if( empty($recaptchaResponse))
        {
            $this->context->buildViolation($constraint->message)->addViolation();
            return ; 
        }
    }

}


```



lib\RecaptchaBundle\Resources\config\services.yaml
```yaml
#
+ 
  recaptcha.validator:
    class: MyLib\RecaptchaBundle\Constraints\RecaptchaValidator
    tags: ['validator.constraint_validator']
    autowire: true 
```

