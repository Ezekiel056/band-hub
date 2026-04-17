<?php

namespace App\Validator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use App\Service\YoutubeLinksResolver;

class YoutubeUrlValidator extends ConstraintValidator
{
    public function __construct(
        private YoutubeLinksResolver $resolver,
    ) {}
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$this->resolver->resolve($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
