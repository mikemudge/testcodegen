<?php

namespace Mudge\Datastore;

use Error;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use MongoId;
use Swagger\Client\Models\ModelInterface;
use Swagger\Client\Models\PurchaseItem;
use MongoDB\Client;
use Swagger\Client\ObjectSerializer;

class BadDataStore {

    private $database;

    public function __construct() {
        $mc = new Client("mongodb://mongo:27017");
        $this->database = $mc->selectDatabase("app");
    }

    public function create(string $schema, ModelInterface $p): ModelInterface {
        $data = ObjectSerializer::sanitizeForSerialization($p);
        $result = $this->database->selectCollection($schema)->insertOne($data);
        $p['id'] = (string)$result->getInsertedId();
        return $p;
    }

    public function update(string $schema, string $id, ModelInterface $p): ModelInterface {
        $data = ObjectSerializer::sanitizeForSerialization($p);
        $this->database->selectCollection($schema)->updateOne(['_id' => new ObjectId($id)], [
            '$set' => $data
        ]);
        return $p;
    }

    public function get($schema, $cls, string $id): array {
        $result = $this->database->selectCollection($schema)->findOne(['_id' => new ObjectId($id)]);
        if (is_null($result)) {
            // TODO not found case?
            throw new \Exception("$schema not found");
        }
        return $this->toArray($result);
    }

    public function delete($schema, $cls, string $id): ?ModelInterface {
        $result = $this->database->selectCollection($schema)->findOneAndDelete(['_id' => new ObjectId($id)]);
        if (is_null($result)) {
            // TODO not found case for delete?
            return null;
        }
        return new $cls($this->toArray($result));
    }

    public function list($schema, $cls): array {
        $result = $this->database->selectCollection($schema)->find([]);
        return $this->toArray($result);
    }

    public function validateId(string $id): string {
        if (empty($id)) {
            throw new Error('id is required');
        }
        $id = new ObjectId($id);
        return $id;
    }

    /** A recursive version of iterator_to_array */
    private function toArray(\Traversable $iterator) {
        $array = [];
        foreach ($iterator as $key => $value) {
            if ($value instanceof \Traversable) {
                $value = $this->toArray($value);
            } else if ($value instanceof ObjectId) {
                $value = (string) $value;
            } else if ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            }
            $array[$key] = $value;
        }
        return $array;
    }
}