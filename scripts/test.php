<?php

use Swagger\Client\Models\Artist;
use Swagger\Client\Models\Purchase;
use Swagger\Client\Models\PurchaseItem;
use Swagger\Client\ObjectSerializer;

require dirname(__DIR__) . '/vendor/autoload.php';

$serializer = new ObjectSerializer();

$items = [];
$item = new PurchaseItem();
$item->setQty(1);
$item->setPrice(10.99);
$item->setTitle("My Item");
$item->setUrl("https://www.google.com");
$items[] = $item;
$purchase = new Purchase(['items' => $items]);

$isValid = $purchase->valid();
echo ($isValid ? "valid" : "invalid") . "\n";
echo("Invalid props:\n" . join("\n", $purchase->listInvalidProperties()));

$result = [];
$result['purchase'] = ObjectSerializer::sanitizeForSerialization($purchase);

echo(json_encode($result, JSON_PRETTY_PRINT));
