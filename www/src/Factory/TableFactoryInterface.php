<?php

namespace Factory;

interface TableFactoryInterface{
    public function saveTableData(string $type, $deviceType, $manufacturer, $model, $serialNumber, $purchaseDate);
}