<?php

namespace Factory;

use Entities\TableDevice;

class TableFactory implements TableFactoryInterface{
    public function saveTableData(string $type, $deviceType, $manufacturer, $model, $serialNumber, $purchaseDate) {
        switch ($type) {
            case 'tableDevice':
                return (new TableDevice)
                    ->withDeviceType($deviceType)
                    ->withManufacturer($manufacturer)
                    ->withModel($model)
                    ->withSerialNumber($serialNumber)
                    ->withPurchaseDate($purchaseDate);
            default:
                throw("Неизвестный тип таблицы: {$type}");
        }
    }
}