<?php

namespace Acme\Services;

use Acme\Common\CommonFunction;
use Acme\Common\Constants as Constants;

class Services
{
    use CommonFunction;

    public function getAll()
    {
        $result = $this->repository->getAll();

        return $result;
    }

    public function getByID($id)
    {
        $result = $this->repository->getByID($id);

        return $result;
    }


    public function list($request)
    {
        $result = $this->repository->list($request);

        return $result;
    }

    public function destroy($id)
    {

        $result = $this->repository->destroy($id);

        return $result;
    }

    public function create($entity)
    {

        $result = $this->repository->create($entity);

        return $result;
    }

    public function update($entity, $id)
    {

        $result = $this->repository->update($entity, $id);

        return $result;
    }

    public function save($entity)
    {

        $result = $this->repository->save($entity);

        return $result;
    }

    public function delete($id)
    {
        $result = $this->repository->delete($id);

        return $result;
    }

    public function getVerified($entity, $UserID, $Code, $id)
    {

        $result = $this->repository->getVerified($entity, $UserID, $Code, $id);

        return $result;
    }
}
