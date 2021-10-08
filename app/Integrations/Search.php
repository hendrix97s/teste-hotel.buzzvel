<?php

namespace App\Integrations;

use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Http;

/**
  *
  * Classe de busca de cordenadas e calculo de distância
  * @package Search
  * @author Luiz F. Lima <lf.system@outlook.com>
  * @copyright Luiz F. Lima © 2021
  * @version 1.0
  */

class Search{

  /**
   * Método responsável por retornar uma listagem de hoteis próximos ao local informado
   * @param string $latitude
   * @param string $longitude
   * @param string $orderby Desired parameters “proximity” or “pricepernight”  
   * @return array
   */
  public static function getNearbyHotels(string $latitude, string $longitude, string $orderby = 'proximity'){
    $coordinates = $latitude.",".$longitude;
    $nearbyHotels = self::calculateNearbyHotels($coordinates);
    return self::asortHotels($orderby,$nearbyHotels);
  }

  /**
   * Método responsável por ordenar a listagem de hotel por preço ou distancia
   * @param string $orderby “proximity” or “pricepernight” 
   * @param array $nearbyHotels Listagem bruta de hoteis
   * @return void
   */
  private static function asortHotels(string $orderby = 'proximity', array &$nearbyHotels = []){
    $orderby = ($orderby == 'proximity') ? 'distance' : 'price';

    $hotelAsortList = [];
    $aux = [];

    foreach ($nearbyHotels as $key => $value) $aux[$key] = $value[$orderby];
    asort($aux);
    foreach ($aux as $key => $value) $hotelAsortList[] = $nearbyHotels[$key];
        
    return $hotelAsortList;
  }

  /**
   * Método responsável por consultar os dados de localidade do endereço passado como parametros, 
   * ou no caso de hotel, consultar por cordenadas;
   * @param string $address Endereço ou cordenada
   * @param boolean $isHotel true para consultar dados complementares de hotel ou false para consultar dados de endereço
   * @return array
   */
  public static function getCoodinatesOrigin(string $address, bool $isHotel = true){
      
    $response = ($isHotel)?
      self::send('geocode', 'get', ['latlng' => $address]):
      self::send('geocode-address','get', ['address' => $address]);

    return self::processResponseCoodinatesOrigin($response, $isHotel);
  }

  /**
   * Método responsável por processar o response de cordenadas
   * @param array $response
   * @param bool $isHotel
   * @return array
   */
  private static function processResponseCoodinatesOrigin(array $response, bool $isHotel){

    $data = [];
    if(!self::validateResponse($response)) return false;

    // Se não for cordenadas de um hotel, então seta cordenadas
    if(!$isHotel) $data = $response['geometry']['location'];

    foreach ($response['address_components'] as $component) {
      switch (current($component['types'])) {
        case 'country':
          $data['country']         = strtolower($component['long_name']);
          $data['country_acronym'] = strtolower($component['short_name']);
          break;

        case 'administrative_area_level_1':
          $data['state']         = strtolower($component['long_name']);
          $data['state_acronym'] = strtolower($component['short_name']);
          break;
      }
    }

    return (isset($data['country']))? $data : false;
  }

  /**
   * Método responsável por vaidar o response
   * @param array $response
   * @return bool
   */
  private static function validateResponse(array &$response){
    if(!isset($response['results'][0]['address_components']) and !isset($response['results'][0]['geometry']['location'])) return false; 
    $response = $response['results'][0];
    return true;
  }

  /**
   * Método responsável por consultar a distancia entre o ponto de o horigem e o hotel
   * @param string $originCoordinates
   * @return array
   */
  private static function calculateNearbyHotels(string $originCoordinates){
    $response = self::getCoodinatesOrigin($originCoordinates, true);
    $nearbyHotels = HotelController::get($response['country'],$response['country_acronym']);

    $params = ['origins' => $originCoordinates];

    foreach ($nearbyHotels as $key => $hotel) {
      $destinyCoordinates = $hotel['latitude'].",".$hotel['longitude'];
      $params['destinations'] = $destinyCoordinates;

      $response = self::send('distancematrix','get', $params);
      $row = current($response['rows']);
      $nearbyHotels[$key] = array_merge($nearbyHotels[$key], self::formateElement($row['elements']));
    }

    return $nearbyHotels;
  }

  /**
   * Método responsavel por formatar o elemento de distancia e duração para o Hotel
   * @param array $elements
   * @return array
   */
  private static function formateElement(array $elements){
    $element  = current($elements);
    if(!isset($element['distance']['text'])) return [];
    $distance = explode(' ', $element['distance']['text']);

    $dist = current($distance);
    $dist = str_replace(".","",$dist);
    $dist = str_replace(",",".",$dist);
    $unit = end($distance);

    return [
      'distance' => $dist,
      'unit'     => $unit,
      'duration' => $element['duration']['text']
    ];
  }

/**
   * Método responsavel por processar e retornar o enpoint solicitado
   * @param string $endpoint nome do endpoint
   * @param array $params Parametros do endpoint
   * @param boolean $encode
   * @return string
   */
  private static function getEndpoint(string $endpoint, array $params = [], $encode = true){
    
    $apiKey = $_ENV['API_KEY'];
    switch ($endpoint) {
      case 'buzzvel-hotels':
        $endpoint = "https://buzzvel-interviews.s3.eu-west-1.amazonaws.com/hotels.json";
        break;

      case 'geocode':
        $endpoint = "https://maps.googleapis.com/maps/api/geocode/json?language=pt-br&latlng={latlng}&key=".$apiKey;
        break;

      case 'geocode-address':
        $endpoint = "https://maps.googleapis.com/maps/api/geocode/json?language=pt-br&address={address}&key=".$apiKey;
        break;

      case 'distancematrix':
        $endpoint = "https://maps.googleapis.com/maps/api/distancematrix/json?language=pt-br&destinations={destinations}&origins={origins}&key=".$apiKey;
        break;
    }

    // Processa endpoint
    foreach ($params as $key => $value) {
      if ($encode) $value = rawurlencode($value); // converte para padrao de url
      $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
    }

    return $endpoint;
  }

  /**
   * Método responsável por consumir os endpoints solicitados
   * @param string $endpoint Nome do endpoint
   * @param string $type Tipo de requisição
   * @param array $params Parametros que compoem o endpoint
   * @param array $data Dados de envio para tipos de requisição como delete, put ou post
   * @return array
   */
  public static function send(string $endpoint, string $type, array $params = [], array $data = []){
    $endpoint = self::getEndpoint($endpoint, $params);
    
    switch ($type) {
      case 'get':
        $obResponse = Http::get($endpoint);
        break;
      case 'post':
        $obResponse = Http::post($endpoint, $data);
        break;
      case 'put':
        $obResponse = Http::put($endpoint, $data);
        break;
      case 'delete':
        $obResponse = Http::delete($endpoint, $data);
        break;
    }

    return ($obResponse->successful()) ? $obResponse->json() : false;
  }
}
