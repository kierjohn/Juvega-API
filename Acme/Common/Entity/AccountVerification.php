<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\AccountVerification as DataField;

class AccountVerification extends BaseEntity
{
    public $UserID = 0;
    public $Code = 0;
    public $Type = 0;
    public $IsConfirm = 0;
   
    public function Validate()
    {
       
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->UserID = isset($input[Datafield::USER_ID])?$input[Datafield::USER_ID] : 0 ;
        $this->Code = isset($input[Datafield::CODE])?$input[Datafield::CODE] : 0 ;
        $this->Type = isset($input[Datafield::TYPE])?$input[Datafield::TYPE] : 0 ;
        $this->IsConfrim = isset($input[Datafield::IS_CONFIRM])?$input[Datafield::IS_CONFIRM] : 0 ;

    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_ID] = $this->UserID;
        $data[Datafield::CODE] = $this->Code;
        $data[Datafield::TYPE] = $this->Type;
        $data[Datafield::IS_CONFIRM] = $this->IsConfrim;
   
        return $data;
    }
}


?>