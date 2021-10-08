<?php

namespace App\Http\Controllers;

use App\Integrations\Search;
use App\Models\Hotel;
use Illuminate\Support\Facades\Request;

/**
  *
  * Classe responsável pelo pelas ações envolvendo os registros de Hotel
  * @package className
  * @author Luiz F. Lima <lf.system@outlook.com>
  * @copyright Luiz F. Lima © 2021
  * @version 1.0
  */

class HotelController extends Controller{  

  /**
   * Método repsonsável por retornar os hoteis proximos ao ponto de origem
   * @param string $originAddress
   * @param string $orderby Desired parameters “proximity” or “pricepernight”  
   * @return void
   */
  public function searchNearbyHotels(Request $request){
    // $originData = Search::getCoodinatesOrigin($originAddress, false);
    // dd($originData);
    return response()->json($request,200);
  }

  /**
   * Undocumented function
   *
   * @param string $country
   * @param string $country_acronym
   * @return array
   */
  public static function get(string $country, string $country_acronym){
    $obHotel = new Hotel();
    return $obHotel
    ->where(['country' => $country])
    ->where(['country_acronym' => $country_acronym])
    ->get()->toArray();
  }

  /**
   * Método responsável por consumir o endpoint, e salvar os registros retornado de hoteis no db
   * @return void
   */
  public static function saveHotelList(){
    $response = Search::send('buzzvel-hotels', 'get');
    if (!$response) return false;
    self::proceessHotelData($response['message']);
  }

  /**
   * Método responsável por ajustar as listagem de hoteis para salvar no banco
   * @param array $responseHotels Response do endpoint
   * @param array $listHotels Litagem do hotel
   * @return void
   */
  private static function proceessHotelData(array $responseHotels){

    foreach ($responseHotels as $hotel) {
      if (!strlen($hotel[1]) or !strlen($hotel[2])) continue;

      $data =  [
        'name'       => $hotel[0],
        'latitude'   => $hotel[1],
        'longitude'  => $hotel[2],
        'price'      => $hotel[3],
      ];

      $originHotelData = Search::getCoodinatesOrigin($hotel[1] . "," . $hotel[2]);

      if (is_array($originHotelData)) {
        $data = array_merge($data, $originHotelData);
        self::store($data);
      }
    }
  }

  /**
   * Método responsável por salvar o registro do hotel
   * @param array $data Dados do hotel
   * @return void
   */
  private static function store(array $data){
    $obHotel = new Hotel();
    $obHotel->create($data);
  }
}
