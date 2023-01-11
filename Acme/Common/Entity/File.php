<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\File as DataField;

class File extends BaseEntity
{
    public $Name = "";
    public $Url = "";
    public $Extension = "";
    public $Bucket = "";

    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->Name = isset($input[Datafield::NAME])?$input[Datafield::NAME] : "" ;
        $this->Url = isset($input[Datafield::URL])?$input[Datafield::URL] : '' ;
        $this->Extension = isset($input[Datafield::EXTENSION])?$input[Datafield::EXTENSION] : '' ;
        $this->Bucket = isset($input[Datafield::BUCKET])?$input[Datafield::BUCKET] : '' ;

    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::NAME] = $this->Name;
        $data[Datafield::URL] = $this->Url;
        $data[Datafield::EXTENSION] = $this->Extension;
        $data[Datafield::BUCKET] = $this->Bucket;

        return $data;
    }
}


?>
