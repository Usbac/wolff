<?php
/**
 * Created by IntelliJ IDEA.
 * User: franklinmoreno
 * Date: 03/03/19
 * Time: 01:12 PM
 */

namespace core;


class Controller
{

    public function view($view)
    {
        $filePath = "Resources/Views/$view.php";

        if(file_exists($filePath)){
            return file_get_contents($filePath);
        }

        return "View not found.";
    }

    public function notFount()
    {
       return $this->view("Errors/404");
    }

}