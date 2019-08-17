<?php


namespace MyLib\RecaptchaBundle\Constraints;

use Symfony\Component\Validator\Constraint;

# 需要RecaptchaValidator.php 来配合
class Recaptcha extends Constraint{

    public $message = 'Invalid Recaptcha' ;
 
}
