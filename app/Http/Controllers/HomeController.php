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

    public function transaction()
    {
        $data = (object) [
        ];

        $pass = [
            "page" => [
                "parent_title" => $this->parent_title,
                "title" => "List Transactions",
            ],
            "data" => $data,
        ];

        return view("transaction/index", $pass);
    }
}
