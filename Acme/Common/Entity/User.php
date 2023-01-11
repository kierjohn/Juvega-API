<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\User as DataField;

class User extends BaseEntity
{
    public $Name = "";
    public $Email = "";
    public $EmailVerifiedAt = "";
    public $Password = "";
    public $RememberToken = "";
   
    public function Validate()
    {
       
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->Name = isset($input[Datafield::NAME])?$input[Datafield::NAME] : "" ;
        $this->Email = isset($input[Datafield::EMAIL])?$input[Datafield::EMAIL] : '' ;
        $this->EmailVerifiedAt = isset($input[Datafield::EMAIL_VERIFIED_AT])?$input[Datafield::EMAIL_VERIFIED_AT] : '' ;
        $this->Password = isset($input[Datafield::PASSWORD])?$input[Datafield::PASSWORD] : '' ;
        $this->RememberToken = isset($input[Datafield::REMEMBER_TOKEN])?$input[Datafield::REMEMBER_TOKEN] : "" ;

    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::NAME] = $this->Name;
        $data[Datafield::EMAIL] = $this->Email;
        $data[Datafield::EMAIL_VERIFIED_AT] = $this->EmailVerifiedAt;
        $data[Datafield::PASSWORD] = hash('sha512', $this->Password);
        $data[Datafield::REMEMBER_TOKEN] = $this->RememberToken;
   
        return $data;
    }
}


?>