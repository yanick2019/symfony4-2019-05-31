<?php

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

