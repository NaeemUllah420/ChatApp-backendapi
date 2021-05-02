<?php
use Illuminate\Support\Str;

function generate_random_string()
{
    return Str::random(20);
}
