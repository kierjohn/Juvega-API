<?php

namespace Acme\Services;

use Acme\Repositories\Files as Repository;

use Acme\Common\DataFields\File as DataField;
use Acme\Common\Entity\File as Entity;
use Acme\Common\Constants as Constants;
use Acme\Common\CommonFunction;

class Files extends Services
{

    use CommonFunction;


    protected $repository;
    public $bucketName;

    public function __construct()
    {
        $this->bucketName = "attachments";
        $this->repository = new Repository;
    }

    public function SaveFileContent($raw_file)
    {
        $extension = $raw_file->getClientOriginalExtension();
        $original_file_name = $raw_file->getClientOriginalName();
        $file_path = $raw_file->getPathName();

        $input["extension"] = $extension;
        $input["url"] = url('/');
        $input["bucket"] = $this->bucketName;
        $input["name"] = pathinfo($original_file_name, PATHINFO_FILENAME);

        $entity  = new Entity;
        $entity->SetData($input);
        $data = $entity->Serialize();

        $result = $this->create($data);

        $content = $this->UploadFile(
            $this->bucketName,
            $raw_file,
            $result->id . "." . $extension
        );

        return $result;
    }

    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->repository->getByUserInfoId($UserInfoId);

        return $result;
    }
}
