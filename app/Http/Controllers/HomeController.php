<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    private $parent_title = "My Wallet X";

    public function index()
    {
        $data = (object) [
            "name" => "iqbal",
        ];

        $pass = [
            "page" => [
                "parent_title" => $this->parent_title,
                "title" => "Home",
            ],
            "data" => $data,
        ];

        return view("home/index", $pass);
    }
}
