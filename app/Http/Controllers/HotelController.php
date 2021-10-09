<?php

namespace App\Http\Controllers;

use App\Integrations\Search;
use App\Models\Hotel;
use Illuminate\Http\Request;

/**
  *
  * Classe responsável pelo pelas ações envolvendo os registros de Hotel
  * @package HotelController
  * @author Luiz F. Lima <lf.system@outlook.com>
  * @copyright Luiz F. Lima © 2021
  * @version 1.0
  */

class HotelController extends Controller{  

  /**
   * Atributo de requisição
   * @var Request
   */
  private Request $request;

  /**
   * Método construtor
   * @param Request $request
   */
  public function __construct(Request $request){
    $this->request = $request;
  }

  /**
   * Método repsonsável por retornar os hoteis proximos ao ponto de origem
   * @param string $originAddress
   * @param string $orderby Desired parameters “proximity” or “pricepernight”  
   * @return void
   */
  public function searchNearbyHotels(){
    try {
      $originData = Search::getCoodinatesOrigin($this->request->address, false);

      if(!$originData) return response()->json(["erro"=>"endereco inexistente"],200);

      $listHotels = Search::getNearbyHotels($originData['lat'], $originData['lng'], $this->request->orderby);

      if(!count($listHotels)) return  response()->json(["erro"=>"sem hoteis"],200);
      
      return response()->json($listHotels,200);
    } catch (\Throwable $th) {
      return response()->json($th->getMessage(),500);
    }
  }

  /**
   * Método responsável por retornar a listagem de hotéis registrado no sistema, de acordo com o país e sigla
   * @param string $country Nome do país
   * @param string $country_acronym Sigla do país
   * @return array Retorna uma listagem de hotéis
   */
  public static function get(string $state, string $stateAcronym){
    $obHotel = new Hotel();
    return $obHotel
    ->where(['state' => $state])
    ->where(['state_acronym' => $stateAcronym])
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
