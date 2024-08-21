<?php
// src/Entity/Contact.php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="L'email est obligatoire.")
     * @Assert\Email(message="Veuillez entrer un email valide.")
     */
    private $email;

    private $telephone;

    /**
     * @Assert\NotBlank(message="L'objet est obligatoire.")
     */
    private $subject;

    /**
     * @Assert\NotBlank(message="Le message est obligatoire.")
     */
    private $message;

    /**
     * @Assert\NotBlank(message="Vous devez accepter la politique de confidentialité.")
     */
    private $privacyPolicyAccepted;

    // Getters and setters...

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of telephone
     */ 
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */ 
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get the value of subject
     */ 
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */ 
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of privacyPolicyAccepted
     */ 
    public function getPrivacyPolicyAccepted()
    {
        return $this->privacyPolicyAccepted;
    }

    /**
     * Set the value of privacyPolicyAccepted
     *
     * @return  self
     */ 
    public function setPrivacyPolicyAccepted($privacyPolicyAccepted)
    {
        $this->privacyPolicyAccepted = $privacyPolicyAccepted;

        return $this;
    }
}
