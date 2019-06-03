<?php

// src/Listener/imageCacheSubsriber.php

namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Property;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }
    public function getSubscribedEvents()
    {
        #要监听的 方法  preRomove() 删除前 preUpdate() 更新前
        return [
            'preRemove',
            'preUpdate',
        ];
    }

    /**
     * 监听 更新数据库之前 
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        #返回一个实体
        $entity = $args->getEntity();
        # 如果不是property实体 则不操作
         
        if (!$entity instanceof  Property ) { 
            return;
        }
        
        # 实体中的file是 上传的file 则删除缓存缩略图 
        if ($entity->getImageFile() instanceof UploadedFile) {  
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, "imageFile")); #$this->uploaderHelper->asset($entity, "imageFile") 是老的图片地址 通过老图片 删除该图片的缩略图缓存
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Property ) {
            return;
        }

        $this->cacheManager->remove($this->uploaderHelper->asset($entity, "imageFile"));
    }
}
