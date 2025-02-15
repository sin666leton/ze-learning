<?php
namespace App\Services;

class FlashMessageService
{
    public function error(string $message)
    {
        session()->flash('error', $message);
    }
}