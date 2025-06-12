<?php

namespace Repository;

use Entities\Table;

class TableRepository implements TableRepositoryInterface{
    private $pdo;


    public function __construct($database)
    {
        $this->pdo = $database->getConnection();
    }

    public function create(Table $table){
        $query = "INSERT INTO devices (user_id ,device_type, manufacturer, model, serial_number, purchase_date) VALUES (? ,?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);

        $params = array(
            $_COOKIE['user_id'], $table->getDeviceType(), $table->getManufacturer(), $table->getModel(), $table->getSerialNumber(), $table->getPurchaseDate()
        );
        if ($stmt->execute($params)) {
            $response = array(
                'success' => true,
                'message' => 'Устройство успешно создано'
            );
            echo json_encode($response);
        }
    }

    public function read(Table $table)
    {
        if ($table->getSearch()) {
            $query = "SELECT * FROM devices where user_id = :user_id and ( device_type LIKE :search
                                OR ID LIKE :search
                                OR manufacturer LIKE :search
                                OR model LIKE :search
                                OR serial_number LIKE :search
                                OR purchase_date LIKE :search )";
        
            $stmt = $this->pdo->prepare($query);
            
            $searchParam = "%{$table->getSearch()}%";
            $stmt->bindParam(':search', $searchParam, \PDO::PARAM_STR);
        }
        else if($table->getId())
        {
            $query = "SELECT * FROM devices WHERE id = :id And user_id = :user_id";

            $stmt = $this->pdo->prepare($query);
            $idParam = $table->getId();
            $stmt->bindParam(':id', $idParam, \PDO::PARAM_INT);
        }
        else{
            $query = "SELECT * FROM devices Where user_id = :user_id";
            $stmt = $this->pdo->prepare($query);
        }
        $stmt->bindParam(':user_id', $_COOKIE['user_id'], \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function readLatestAddedDevices()
    {
        $query = "SELECT * FROM devices Where user_id = :user_id LIMIT 5";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $_COOKIE['user_id'], \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(Table $table)
    {
        if($table->getId()){
            $query = "DELETE FROM devices WHERE id = :id";

            $stmt = $this->pdo->prepare($query);
            $idParam = $table->getId();
            $stmt->bindParam(':id', $idParam, \PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $response = array(
                    'success' => true,
                    'message' => 'Устройство успешно удаленно'
                );
                return $response;
            }
        }
    }
    public function update(Table $table)
    {
        $query = "UPDATE devices SET device_type = ?, manufacturer = ?, model = ?, serial_number = ?, purchase_date = ? WHERE id = ? and user_id = ?";
        $stmt = $this->pdo->prepare($query);

        $params = array(
            $table->getDeviceType(), $table->getManufacturer(), $table->getModel(), $table->getSerialNumber(), $table->getPurchaseDate(), $table->getId(), $_COOKIE['user_id']
        );
        if ($stmt->execute($params)) {
            $response = array(
                'success' => true,
                'message' => 'Устройство успешно обновленно'
            );
            return $response;
        }
    }
}