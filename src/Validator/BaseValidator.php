<?php

declare(strict_types=1);

namespace App\Validator;


abstract class BaseValidator implements ValidatorInterface
{
    protected ?int $maxLength = 45;
    protected ?int $minLength = 3;
    protected string $error = '';

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     */
    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * @param int|null $maxLength
     */
    public function setMaxLength(?int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @return int|null
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    /**
     * @param int|null $minLength
     */
    public function setMinLength(?int $minLength): void
    {
        $this->minLength = $minLength;
    }


    protected function validateLength(string $val):bool {
        if(strlen($val) > $this->maxLength){
            $this->error = 'This field can be max '.$this->maxLength.' characters long';
            return false;
        }
        if(strlen($val) < $this->maxLength){
            $this->error = 'This field must be at last '.$this->minLength.' characters long';
            return false;
        }

        return true;
    }


}
