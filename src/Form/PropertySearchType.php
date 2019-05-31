<?php

// src/form/PropertySearchType.php
namespace App\Form;

use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            # 给form 加input 
             ->add('maxPrice',IntegerType::class ,[
                'required' => false,
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Budget max'
                ]
            ])
            ->add('minSurface',IntegerType::class ,[
                'required' => false,
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Surface minimale'
                ]
            ])
           /*  #给form 加 sumbit按钮
            ->add('submit',SubmitType::class,[
                'label'=>'Rechercher'
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false , # 不加token
        ]);
    }


    /**
     * 阻止前缀
     * 不写这个方法时提交: ?property_search%5BminSurface%5D=&property_search%5BmaxPrice%5D=&property_search%5B_token%5D=E_5ubOAV2EABtkDCHXdwtLOiFNfkCMPQd80XxDTkdZI
     * 用了这个方法后 提交: ?minSurface=&maxPrice=&_token=0BGWjCPdrMZmYA-3P7-iLK_K3INBejeOnKrW32xwQEc
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
