<?php

namespace App\Http\Controllers;

/**
  *
  * Classe principal de exibição de views
  * @package HomeController
  * @author Luiz F. Lima <lf.system@outlook.com>
  * @copyright Luiz F. Lima © 2021
  * @version 1.0
  */
class HomeController extends Controller{

  /**
   * Show the application dashboard.
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index(){
    return view('home');
  }

  /**
   * Método responsável por retornar a pagina inicial
   * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public function welcome(){
    return view('welcome');
  }
}
