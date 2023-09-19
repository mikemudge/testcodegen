<?php

use Mudge\Datastore\HandlerFactory;
use Swagger\Client\Models\Purchase;
use Swagger\Client\Models\PurchaseItem;
use Swagger\Client\Models\ValidationError;
use Swagger\Client\ObjectSerializer;

require '/app/vendor/autoload.php';

$restMethods = [
    'GET',
    'POST',
    'PUT',
    'DELETE',
];

// route can't be trusted as its a user input.
$route = htmlspecialchars($_SERVER['REQUEST_URI']);
// Set method if its a valid method.
$method = in_array($_SERVER['REQUEST_METHOD'], $restMethods) ? $_SERVER['REQUEST_METHOD'] : null;
$parts = explode("/", $route);
// $parts[0-2] should always be known because all routes begin with /api/v1/
$schema = $parts[3];

$dataHandler = HandlerFactory::chooseDatastore();
$eventHandler = HandlerFactory::chooseEventstream();
// Validate id does look like a valid id? Depends on the data handler as to what is a valid id.
$title = "";
$html = "";
if ($schema == "PurchaseItem") {
    $cls = PurchaseItem::class;
} else if ($schema == "Purchase") {
    $cls = Purchase::class;
} else {
    // 404?
    $error = new ValidationError();
    $error->setErrormsg('Unknown Schema');
    $response = ObjectSerializer::sanitizeForSerialization($error);
}
error_log(json_encode($_GET));

if (isset($cls)) {
    try {
        switch ($method) {
            case "POST":
                error_log(json_encode($_POST));
                $p = new $cls($_POST);
                if ($p->valid()) {
                    // Submit to abstract data handler which can modify it (E.g add an id).
                    $p = $dataHandler->create($schema, $p);
                    $eventHandler->create($p, $p['_id']);
                    $response = ObjectSerializer::sanitizeForSerialization($p);
                } else {
                    // form validation response.
                    $error = new ValidationError([
                        'errormsg' => 'There were some problems with your submission',
                        'error' => $p->listInvalidProperties()
                    ]);
                    $response = ObjectSerializer::sanitizeForSerialization($error);
                }
                break;
            case "PUT":
                error_log(json_encode($_POST));
                $id = $parts[4];
                $p = new $cls($_POST);
                $p = $dataHandler->update($schema, $dataHandler->validateId($id), $p);
                $eventHandler->update($p, $id);
                $response = ObjectSerializer::sanitizeForSerialization($p);
                break;
            case "DELETE":
                $id = $parts[4];
                $p = $dataHandler->delete($schema, $cls, $dataHandler->validateId($id));
                $eventHandler->delete($p, $id);
                if ($p == null) {
                    $response = [];
                } else {
                    $response = ObjectSerializer::sanitizeForSerialization($p);
                }
                break;
            case "GET":
                $id = $parts[4] ?? null;
                if ($id) {
                    $p = $dataHandler->get($schema, $cls, $dataHandler->validateId($id));
                    $response = ObjectSerializer::sanitizeForSerialization($p);
                } else {
                    // TODO is list a data handler thing or a business logic thing?
                    $ps = $dataHandler->list($schema, $cls);
                    $response = ObjectSerializer::sanitizeForSerialization($ps);
                }
                break;
            default:
                // Never expected to happen.
                throw new Exception("Bad HTTP method");
        }
    } catch (Exception $e) {
        $error = new ValidationError();
        $error->setErrormsg("$e");
        $error->setError($e->getCode());
        $response = ObjectSerializer::sanitizeForSerialization($error);
    } finally {
        $eventHandler->close();
    }
}

$flags = JSON_PRETTY_PRINT;

echo(json_encode($response, $flags));