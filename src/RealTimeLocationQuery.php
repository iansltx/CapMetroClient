<?php

namespace iansltx\CapMetroClient;

/**
 * Class RealTimeLocationQuery
 *
 * Queries the CapMetro Socrata API for real-time bus information. See http://dev.socrata.com/docs/queries.html for
 * more details on the query language.
 *
 * @package iansltx\CapMetroClient
 */
class RealTimeLocationQuery
{
    protected $http;
    protected $filters = [];
    protected static $queryFilters = ['reliable', 'offroute', 'stopped', 'inservice'];
    protected static $whereFilters = ['route', 'routeid', 'tripid'];

    const URL = 'https://data.texas.gov/resource/9e7h-gz56.json';

    /**
     * Creates a new query for real-time bus/train position information
     *
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http = null) {
        $this->http = $http ?: new HttpClient;
    }

    public function filterByTripId($trip_id) {
        $this->filters['tripid'] = $trip_id;
        return $this;
    }

    public function filterByRouteId($route_id) {
        $this->filters['routeid'] = $route_id;
        return $this;
    }

    /**
     * Filters a query by route number (e.g. 1, 19, 550); existing routes are
     * replaced. Multiple routes are supported.
     *
     * @param int|int[] $route_number
     * @return $this
     */
    public function filterByRouteNumber($route_number) {
        $this->filters['route'] = $route_number;
        return $this;
    }

    public function showOnlyReliable() {
        $this->filters['reliable'] = 'Y';
        return $this;
    }

    public function showOnlyUnreliable() {
        $this->filters['reliable'] = 'N';
        return $this;
    }

    public function showOnlyOffRoute() {
        $this->filters['offroute'] = 'Y';
        return $this;
    }

    public function showOnlyOnRoute() {
        $this->filters['offroute'] = 'N';
        return $this;
    }

    public function showOnlyStopped() {
        $this->filters['stopped'] = 'Y';
        return $this;
    }

    public function showOnlyNotStopped() {
        $this->filters['stopped'] = 'N';
        return $this;
    }

    public function showOnlyInService() {
        $this->filters['inservice'] = 'Y';
        return $this;
    }

    public function showOnlyOutOfService() {
        $this->filters['inservice'] = 'N';
        return $this;
    }

    public function fetchAll() {
        $filters = array_intersect_key($this->filters, array_flip(static::$queryFilters));
        $filters['$where'] = $this->buildWHere(array_intersect_key($this->filters, array_flip(static::$whereFilters)));

        return array_map(function($result) {
            return new VehicleLocationUpdate($result);
        }, $this->http->getJSONAsObject(static::URL . '?' . http_build_query($filters)));
    }

    protected function buildWhere(array $params) {
        return implode(' AND ', array_map(function($column_name, $column_values) {
            return '(' . implode(' OR ', array_map(function($value) use ($column_name) {
                return $column_name . ' = ' . $value;
            }, is_array($column_values) ? $column_values : [$column_values])) . ')';
        }, array_keys($params), $params));
    }
}
