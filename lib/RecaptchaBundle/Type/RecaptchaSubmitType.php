<?php

namespace MyLib\RecaptchaBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;



class RecaptchaSubmitType extends AbstractType
{

    /**
     * @var string
     */
    private $key ;

    public function __construct(string $key )
    {
        $this->key = $key ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "mapped" => false # 未对应任何数据字段
        ]);
    }
    public function buildView(FormView $view, FormInterface $form, array $option    )
    {
        $view->vars["label"] = false;  # 不显示label 
        $view->vars["key"] =  $this->key ; 
        $view->vars["button"] = $option['label'] ; 
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
