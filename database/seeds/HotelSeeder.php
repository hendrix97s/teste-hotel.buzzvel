<?php

use App\Http\Controllers\HotelController;
use App\Integrations\Search;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
    HotelController::saveHotelList();
  }
}
