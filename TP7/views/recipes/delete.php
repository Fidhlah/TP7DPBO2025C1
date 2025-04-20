<?php
include_once '../../config/database.php';
include_once '../../models/Resep.php';

$database = new Database();
$db = $database->getConnection();

$resep = new Resep($db);

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

$resep->id = $id;

if($resep->delete()) {
    header("Location: index.php");
} else {
    echo "Unable to delete resep.";
}
?>
