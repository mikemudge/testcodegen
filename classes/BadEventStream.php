<?php

namespace Mudge\Datastore;

use RdKafka\ProducerTopic;
use Swagger\Client\Models\ModelInterface;
use RdKafka\Conf;
use RdKafka\Producer;

class BadEventStream {

    private string $application;
    private Producer $rk;
    private ProducerTopic $topic;
    private $stdout;

    public function __construct() {
        $this->application = "Test";
        $conf = new Conf();
//        $conf->set('log_level', (string) LOG_DEBUG);
//        $conf->set('debug', 'all');
        $conf->set('bootstrap.servers', "kafka:9092");
        $conf->setErrorCb(function ($data) {
            echo("error cb");
            error_log($data);
        });
        $this->rk = new Producer($conf);
        $this->rk->addBrokers("kafka");

        $this->topic = $this->rk->newTopic($this->application . ".event");
        $this->stdout = fopen('php://stdout', 'w');
    }

    public function create(ModelInterface $p, $id) {
        $event = [
            'type' => str_replace( "\\", ".", get_class($p)),
            'application' => $this->application,
            'id' => $id,
            'action' => 'create'
        ];
        // Include the created model if requested?
        $data = json_encode($event, JSON_PRETTY_PRINT);
        // Log to std out as well.
        fwrite($this->stdout, $data . "\n");
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $data);
    }

    public function update(ModelInterface $p, $id) {
        $event = [
            'type' => str_replace( "\\", ".", get_class($p)),
            'application' => $this->application,
            'id' => $id,
            'action' => 'update'
        ];
        // Include the created model if requested?
        $data = json_encode($event, JSON_PRETTY_PRINT);
        // Log to std out as well.
        fwrite($this->stdout, $data . "\n");
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $data);
    }

    public function delete(ModelInterface $p, $id) {
        $event = [
            'type' => str_replace( "\\", ".", get_class($p)),
            'application' => $this->application,
            'id' => $id,
            'action' => 'delete'
        ];
        // Include the created model if requested?
        $data = json_encode($event, JSON_PRETTY_PRINT);
        // Log to std out as well.
        fwrite($this->stdout, $data . "\n");
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $data);
    }

    public function close() {
        $this->rk->flush(2000);
    }
}