<?php

namespace Controllers;

use Entities\TableDevice;
use Factory\TableFactory;
use Repository\TableRepository;

class TableController{
    private $tableRepository;
    private $tableFactory;

    public function __construct(TableRepository $tableRepository, TableFactory $tableFactory = null)
    {
        $this->tableRepository = $tableRepository;
        $this->tableFactory = $tableFactory;
    }

    public static function getActionMethodsTable()
    {
        return [
            'createDevice' => 'create',
            'deleteDevice' => 'delete',
            'updateDevice' => 'update',
        ];
    }

    public function create()
    {
        if (isset($_POST['device_type'])) {
            $dataTable = $this->tableFactory->saveTableData('tableDevice', $_POST['device_type'], $_POST['manufacturer'],$_POST['model'],$_POST['serial_number'], $_POST['purchase_date']);
            $this->tableRepository->create($dataTable);
            exit;
        }
    }

    public function read(TableDevice $table)
    {
        $devices = $this->tableRepository->read($table);
        header('Content-Type: application/json');
        echo json_encode($devices);
    }
    
    public function readLatestAddedDevices()
    {
        $devices = $this->tableRepository->readLatestAddedDevices();
        header('Content-Type: application/json');
        echo json_encode($devices);
    }

    public function delete()
    {
        if(isset($_POST['id'])){
            $tableWithId = (new TableDevice())->withId($_POST['id']);
            $response = $this->tableRepository->delete($tableWithId);
            echo json_encode($response);
            exit;
        }
    }

    public function update()
    {
        $dataTable = $this->tableFactory->saveTableData('tableDevice', $_POST['device_type'], $_POST['manufacturer'],$_POST['model'],$_POST['serial_number'], $_POST['purchase_date'])->withId($_POST['id']);
        $response = $this->tableRepository->update($dataTable);
        echo json_encode($response);
        exit;
    }
}