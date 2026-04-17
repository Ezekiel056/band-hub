<?php

namespace App\Validator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class YoutubeUrl extends Constraint
{
    public string $message = 'Cette URL YouTube n\'est pas valide.';
}
