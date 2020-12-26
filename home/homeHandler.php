<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/classes/FrameworkFunctions.php");

// If ajax is posting data getData call function getData to return data from database to till the table
if (isset($_POST['getData'])) {
    $FF = new FrameworkFunctions($con);
    $pagecount = $_POST['getData'];
    $result = $FF->getData('data', $pagecount); // data --> target tablename
    echo $result;
}

if (isset($_GET['getAddresses'])) {
    $FF = new FrameworkFunctions($con);
    $result = $FF->getDropdown('address'); // data --> target tablename
    echo $result;
}

// If ajax is getting data getSingleData do the following
if (isset($_GET['getSingleData'])) {
    $FF = new FrameworkFunctions($con);
    $dataid = $_GET['getSingleData'];

    $params = array(
        'id' => $dataid,

    );
    $result = $FF->getSingleData('data', $params); // data --> target tablename, $dataid --> id
    echo $result;
}

// If ajax is getting data updateData 
if (isset($_GET['updateData'])) {
    $FF = new FrameworkFunctions($con);
    $dataid =  $_GET['dataid'];
    $firstname =  $_GET['firstname'];
    $lastname =  $_GET['lastname'];
    $age =  $_GET['age'];

    $params = array(
        'firstname' => $_GET['firstname'],
        'lastname' => $_GET['lastname'],
        'age' => $_GET['age'],
        'address' => $_GET['address']
    );

    $additionalparams = array(
        'id' => $_GET['dataid']
    );

    $result = $FF->updateData('data', $params, $additionalparams);
    echo $result;
}

// If ajax is getting data addData do the following
if (isset($_GET['addData'])) {
    $FF = new FrameworkFunctions($con);
    $firstname =  $_GET['firstname'];
    $lastname =  $_GET['lastname'];
    $age =  $_GET['age'];

    $params = array(
        'firstname' => $_GET['firstname'],
        'lastname' => $_GET['lastname'],
        'age' => $_GET['age'],
        'address' => $_GET['address']
    );

    $result = $FF->addData('data', $params);
    echo $result;
}

// If ajax is getting data delData do the following
if (isset($_GET['delData'])) {
    $FF = new FrameworkFunctions($con);
    $additionalparams = array(
        'id' => $_GET['delData']
    );
    $result = $FF->delData('data', $additionalparams);
    echo $result;
}
