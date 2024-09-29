<?php

namespace App\Cells;

use CodeIgniter\I18n\Time;

class GreetingCell
{
    protected function greeting(): string
    {
        $now = Time::now();
        $today = Time::today();

        if ($now->isAfter($today->setHour(4)) && $now->isBefore($today->setHour(12)))
            $greeting = lang("Header.good-morning");
        else if ($now->isAfter($today->setHour(12)) && $now->isBefore($today->setHour(18)))
            $greeting = lang("Header.good-afternoon");
        else
            $greeting = lang("Header.good-evening");

        return $greeting;
    }
    
    public function greet(array $args): string
    {
        return view("parts/greeting", [
            "greeting" => $this->greeting(),
            "username" => $args["username"] ?? ""
        ]);
    }
}
