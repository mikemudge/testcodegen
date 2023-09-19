<?php

namespace Mudge\Datastore;

class HandlerFactory {

    public static function chooseDatastore() {
        return new BadDataStore();
    }

    public static function chooseEventstream() {
        return new BadEventStream();
    }
}