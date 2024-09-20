<?php

namespace Entities;

abstract class Table{
    protected $deviceType;
    protected $manufacturer; 
    protected $model;
    protected $serialNumber;
    protected $purchaseDate;
    public $type;
    public $search;
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public function getDeviceType() 
    {
        return $this->deviceType;
    }

    public function getManufacturer() 
    {
        return $this->manufacturer;    
    }

    public function getModel() 
    {
        return $this->model;
    }

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function withDeviceType($deviceType) 
    {
        $this->deviceType = $deviceType;
        return $this; 
    }    

    public function withManufacturer($manufacturer) 
    {
        $this->manufacturer = $manufacturer;
        return $this; 
    }

    public function withModel($model) 
    {
        $this->model = $model;
        return $this;
    }

    public function withSerialNumber($serialNumber) 
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function withPurchaseDate($purchaseDate) 
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    public function withSearch($search){
        $this->search = $search;
        return $this;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }


    abstract public function getType(): string;
}
