<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class FlightController extends Controller {

    // class properties
    private $flights = array();
    private $flightsGrouping = array();
    private $flightsGrouped = array();
    private $flightsResponse = NULL;
    private $message = array();

    // Constructor class
    public function __construct() {
        $this->flightsResponse =  Http::get(env('API_URL'));

        if($this->flightsResponse->successful())
        {
            $this->flights = $this->flightsResponse->json();
        }
        else
        {
            $this->message['error'] = 'Nao foi possivel se conectar ao provedor de Voos';
            json_encode($this->message);
        }
    }

    // Return all Flights available
    public function getAllFlights() {

        if(sizeof($this->flights) > 0)
        {
            return response($this->flights, 200)
                   ->header('Content-Type', ' application/json; charset=utf-8');
        }
        else
        {
            return response($this->message, 500)
                   ->header('Content-Type', ' application/json; charset=utf-8');
        }

    } // end getAllFlights

    // Return all Flights in groupings
    public function groupFlights () {

        $flightOutBound = array();
        $flightInBound  = array();
        $totalGroups = 0;

        foreach ($this->flights as $key => $flight)
        {
            $this->flightsGrouping[$this->flights[$key]['outbound'] ? 'outbound' : 'inbound']
                                  [$this->flights[$key]['fare']]
                                  [$this->flights[$key]['price']]
                                  [] = $flight;
        }


        foreach ($this->flightsGrouping['outbound'] as $fare => $prices)
        {
            foreach ($prices as $price => $flightData)
            {
                // Process Flights outbound for the same amount
                foreach ($flightData as $indice => $value)
                {
                    $flightOutBound = array();
                    $flightOutBound = $flightData ;
                }

                $flightsInBound = $this->flightsGrouping['inbound'][$fare];
                $flightInBound = array();

                // Process Flights inbound for the same fare
                foreach ($flightsInBound as $priceInBound => $flightDataInBound)
                {
                    foreach ($flightDataInBound as $key => $value)
                    {
                        $flightInBound[] = $value;
                    }
                }

                $this->flightsGrouped['groups'][] = array('uniqueID'    => Str::uuid(),
                                                          'totalPrice'  => $price + $priceInBound,
                                                          'outbound'    => $flightOutBound,
                                                          'inbound'     => $flightInBound);

                $totalGroups ++;
            }
        }

        $this->flightsGrouped['flights'] = $this->getAllFlights();
        $this->flightsGrouped['totalGroups']  = $totalGroups;
        $this->flightsGrouped['totalFlights'] = sizeof($this->flights);

        return response($this->flightsGrouped, 200)
               ->header('Content-Type', ' application/json; charset=utf-8');

    } // end groupFlights

    // Returns all flight groups ordered
    function sortGroupFlights() {
        return response($this->groupFlights(), 200)
               ->header('Content-Type', ' application/json; charset=utf-8');

    } // end sortGroupFlights

} // End Class FlightController.php
