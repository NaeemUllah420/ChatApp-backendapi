<?php
use Illuminate\Support\Str;

function generate_random_string()
{
    return Str::random(20);
}

function paginator($payload){
    return [
        'current_page'=>$payload['current_page'],
        'from'=>$payload['from'],
        'to'=>$payload['to'],
        'per_page'=>$payload['per_page'],
        'last_page'=>$payload['last_page'],
        'total'=>$payload['total']
    ];
}