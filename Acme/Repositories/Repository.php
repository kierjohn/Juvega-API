<?php

namespace Acme\Repositories;

use Illuminate\Database\Eloquent\Model;
use Acme\Common\Constants as Constants;
use Acme\Common\DataFields\Message as Message;
use Acme\Common\DataFields\Property as Property;
use Acme\Common\DataFields\AccountVerification as AccountVerification;
use Acme\Common\DataFields\Conversation as Conversation;
use Acme\Common\DataFields\Contact;
use CreateContactsTable;
use PHPUnit\TextUI\XmlConfiguration\Constant;

class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create($entity)
    {
        $result = $this->model->create($entity);
        return $result;
    }

    // update record in the database
    public function update($entity, $id)
    {

        $result = $this->model->where(Constants::ID, $id)->update($entity);


        return $result;
    }

    // remove record from the database
    public function delete($id)
    {
        $result = null;
        $result = $this->model->where(Constants::ID, $id)->delete();

        return $result;
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    public function getAll()
    {
        $result = $this->model->get();

        return $result;
    }

    public function getByID($id)
    {
        $result = $this->model->where(Constants::ID, $id)->first();

        return $result;
    }


    public function destroy($id)
    {
        $result = $this->model->where(Constants::ID, $id)->delete();

        return $result;
    }

    public function save($entity)
    {
        $result = null;

        if ($entity[Constants::ID] == "") {
            $result = $this->model->create($entity);
        } else {
            $this->model->where(Constants::ID, $entity[Constants::ID])->update($entity);
            $result = $entity;
        }


        return $result;
    }

    public function getVerified($entity, $UserID, $Code, $id)
    {
        $result = $this->model->where(AccountVerification::USER_ID, $UserID)->where(AccountVerification::CODE, $Code)->update(array('is_confirm' => 1));
        // $result-> save();
        return $result;
    }
}
