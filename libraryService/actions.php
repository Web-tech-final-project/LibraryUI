<?php
require 'libraries.php';

$lib = new libraries();
$lib->setId(isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : null);
$lib->setLat(isset($_REQUEST['lat']) ? (float) $_REQUEST['lat'] : null);
$lib->setLng(isset($_REQUEST['lng']) ? (float) $_REQUEST['lng'] : null);

try {
    $status = $lib->updateLibrariesWithLatLng();
    echo $status ? "Updated..." : "Failed...";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
