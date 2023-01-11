<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\Contact as DataField;

class Contact extends BaseEntity
{
    public $ContactId = 0;
    public $UserInfoId = 0;
    public $Status = 0;
    public $Favorite = 0;

    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID])?$input[Datafield::USER_INFO_ID] : 0 ;
        $this->ContactId = isset($input[Datafield::CONTACT_ID])?$input[Datafield::CONTACT_ID] : 0 ;
        $this->Status = isset($input[Datafield::STATUS])?$input[Datafield::STATUS] : 0 ;
        $this->Favorite = isset($input[Datafield::FAVORITE])?$input[Datafield::FAVORITE] : 0 ;
    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::CONTACT_ID] = $this->ContactId;
        $data[Datafield::STATUS] = $this->Status;
        $data[Datafield::FAVORITE] = $this->Favorite;

        return $data;
    }
}


?>
