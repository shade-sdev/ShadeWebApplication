<?php

class FrameworkFunctions
{

    private $con;


    public function __construct($con)
    {

        $this->con = $con;
    }


    // Getting all rows from a table
    public function getData($tablename, $pagecount)
    {
        if ($pagecount == NULL) {
            $query = $this->con->prepare("SELECT data.id, data.firstname, data.lastname, data.age, address.name as address FROM data, address WHERE data.address = address.id LIMIT 10 OFFSET 0");
            $query->execute();
            $data = $query->fetchAll();


            $query2 = $this->con->prepare("SELECT COUNT(id) AS total FROM data");
            $query2->execute();

            $data2 = $query2->fetch(PDO::FETCH_ASSOC);
            $count = $data2['total'];

            echo json_encode(['rows' => $data, 'count' => $count, 'response' => true]);
        } else {

            if ($pagecount == 1) {
                $start = 0;
            } else 
            if ($pagecount > 1) {
                $start = ((($pagecount - 1) * 10));
            }


            $query = $this->con->prepare("SELECT data.id, data.firstname, data.lastname, data.age, address.name as address FROM data, address WHERE data.address = address.id LIMIT 10 OFFSET $start");
            $query->execute();
            $data = $query->fetchAll();

            echo json_encode(['rows' => $data, 'response' => true]);
        }
    }

    public function getDropdown($tablename)
    {
        $query = $this->con->prepare("SELECT * FROM $tablename");
        $query->execute();
        $data = $query->fetchAll();
        echo json_encode(['rows' => $data, 'response' => true]);
    }

    // Getting Specific row from a table
    public function getSingleData($tablename, $params)
    {

        $id = "";
        foreach ($params as $field => $value) {

            $id .= "`$field` = :$field";
        }
        $query = $this->con->prepare("SELECT * FROM $tablename WHERE $id");
        foreach ($params as $field => $value) {

            $query->bindValue(':' . $field, $value, PDO::PARAM_STR);
        };
        $query->execute();
        $data = $query->fetchAll();
        echo json_encode(['rows' => $data, 'response' => true]);
    }

    // Adding Data to Database
    public function addData($table, $params)
    {
        $dataFields = "";
        $data = "";

        foreach ($params as $field => $value) {

            $dataFields .= "`$field`,";
        }

        $dataFields = rtrim($dataFields, ", ");



        foreach ($params as $field => $value) {

            $data .= ":$field,";
        }

        $data = rtrim($data, ", ");

        $query = $this->con->prepare("INSERT INTO `$table` ($dataFields) VALUES ($data)");


        foreach ($params as $field => $value) {

            $query->bindValue(':' . $field, $value, PDO::PARAM_STR);
        };

        $response = $query->execute();
        echo json_encode(['response' => $response]);
    }

    // Updating a Specific row from a table
    public function updateData($table, $params, $additionalparams)
    {
        $updateSQL = "";
        $id = "";

        foreach ($params as $field => $value) {

            $updateSQL .= "`$field` = :$field,";
        }

        foreach ($additionalparams as $field => $value) {

            $id .= "`$field` = :$field";
        }

        $updateSQL = rtrim($updateSQL, ", ");

        $query = $this->con->prepare("UPDATE `$table` SET $updateSQL WHERE $id");

        foreach ($params as $field => $value) {

            $query->bindValue(':' . $field, $value, PDO::PARAM_STR);
        };

        foreach ($additionalparams as $field => $value) {

            $query->bindValue(':' . $field, $value, PDO::PARAM_INT);
        };

        $response = $query->execute();
        echo json_encode(['response' => $response]);
    }

    // Getting Specific row from a table
    public function delData($tablename, $additionalparams)
    {

        $id = "";

        foreach ($additionalparams as $field => $value) {

            $id .= "`$field` = :$field";
        }

        $query = $this->con->prepare("DELETE FROM $tablename WHERE $id");

        foreach ($additionalparams as $field => $value) {

            $query->bindValue(':' . $field, $value, PDO::PARAM_INT);
        };

        $response = $query->execute();
        echo json_encode(['response' => $response]);
    }
}
