<?php



namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
  /**
   * @var string|null
   * @Assert\NotBlank()
   * @Assert\Length(min=2,max=100 , )
   */
  private $firstname;

  /**
   * @var string|null
   * @Assert\NotBlank()
   * @Assert\Length(min=2,max=100)
   */
  private $lastname;


  /**
   * @var string|null
   * @Assert\NotBlank(  )
   * @Assert\Regex( 
   * pattern="/\d{10}/"
   * )
   *   pattern 正则表达式
   * 也可以在config/validator/validation.yaml 里设置 两个地方只能设置一个 否则会弹出两个错误信息
   * 
   */
  private $phone ;


  /**
   * @var string|null
   * @Assert\NotBlank()
   * @Assert\Email()
   */
  private $email;

  /**
   * @var string|null
   * @Assert\NotBlank()
   * @Assert\Length(min=10)
   */
  private $message;

  /**
   * @var Property|null
   */
  private $property ;


  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): Contact
  {
    $this->firstname = $firstname;
    return $this;
  }
  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  public function setLastname(string $lastname): Contact
  {
    $this->lastname = $lastname;
    return $this;
  }
  public function getPhone(): ?string
  {
    return $this->phone;
  }

  public function setPhone(string $phone): Contact
  {
    $this->phone = $phone;
    return $this;
  }
  public function getEmail(): ?string
  {
    return $this->email ;
  }

  public function setEmail(string $email): Contact
  {
    $this->email = $email;
    return $this;
  }

  public function getmessage(): ?string
  {
    return $this->message;
  }

  public function setmessage(string $message): Contact
  {
    $this->message = $message;
    return $this;
  }

  public function getProperty(): ?Property
  {
    return $this->property;
  }

  public function setProperty( Property $property): Contact
  {
    $this->property = $property;
    return $this;
  }
}
