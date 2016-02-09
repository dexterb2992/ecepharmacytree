<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Basket;
use DB;
use Input;
use ECEPharmacyTree\Repositories\BasketRepository;
use ECEPharmacyTree\Repositories\PointsRepository;

class BasketController extends Controller
{
    function __construct(BasketRepository $basket, PointsRepository $points) {
        $this->basket = $basket;
        $this->points = $points;
    }

    function compute_basket_points(){
        return $this->points->compute_basket_points(Input::all());
    }

    function check_basket(){
        $input = Input::all();

        $response = json_decode($this->basket->check_and_adjust_basket($input['patient_id'], $input['branch_id']));
        $response->expected_points = (double) $this->points->compute_basket_points(Input::all());

        return json_encode($response);
    }

    function flush_user_basket_promos(){
        $input = Input::all();

        return json_encode($this->basket->flush_user_basket_promos($input['patient_id']));
    }
}
