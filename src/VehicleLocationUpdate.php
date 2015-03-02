<?php

namespace iansltx\CapMetroClient;

use \DateTime;

class VehicleLocationUpdate
{
    protected $data;
    protected $tz;

    public function __construct($data) {
        $this->data = $data;
        $this->tz = new \DateTimeZone('America/Chicago');
    }

    public function getUpdateTime() {
        return DateTime::createFromFormat('h:i A', $this->data->updatetime, $this->tz);
    }

    public function getTimestamp() {
        return DateTime::createFromFormat('Y-m-d\TH:i:s', $this->data->iso_timestamp, $this->tz);
    }

    public function getPosition() {
        return $this->data->location;
    }

    public function getLatitude() {
        return (float) (explode(',', $this->data->location)[0]);
    }

    public function getLongitude() {
        return (float) (explode(',', $this->data->location)[1]);
    }

    public function getSpeedMPH() {
        return (float) $this->data->speed;
    }

    public function getHeading() {
        return (int) $this->data->heading;
    }

    public function getBlockId() {
        return $this->data->block;
    }

    public function getRouteDirection() {
        return $this->data->direction;
    }

    public function getRouteNumber() {
        return $this->data->route; // returns a string in case CapMetro adds alpha routes
    }

    public function getRouteId() {
        return (int) $this->data->routeid;
    }

    public function getVehicleId() {
        return (int) $this->data->vehicleid;
    }

    public function getTripId() {
        return (int) $this->data->tripid;
    }

    public function isReliable() {
        return $this->data->reliable === 'Y';
    }

    public function isInService() {
        return $this->data->inservice === 'Y';
    }

    public function isOffRoute() {
        return $this->data->offroute === 'Y';
    }

    public function isStopped() {
        return $this->data->stopped === 'Y';
    }

    public function getRawData() {
        return $this->data;
    }
}
