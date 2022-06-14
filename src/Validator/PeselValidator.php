<?php


namespace App\Validator;


class PeselValidator extends BaseValidator implements ValidatorInterface
{


    protected ?int $maxLength = 11;
    protected ?int $minLength = 11;
    protected int $control = 0;

    /**
     * @return int
     */
    public function getControl(): int
    {
        return $this->control;
    }



    public function validate(?string $pesel) :bool {

        if(empty($pesel)){
            $this->error = "PESEL is mandatory";
            return false;
        }

        if(!$this->validateLength($pesel)){
            return false;
        }

        if(!is_numeric($pesel)){
            $this->error = "PESEL must be a number";
            return false;
        }

        $pesel10 = substr($pesel,0,-1);
        $sum = 0;
        $weights = [1,3,7,9,1,3,7,9,1,3];


        foreach(str_split($pesel10) as $index => $number){
            $p = $weights[$index] * (int)$number;

            if($p > 9){
                $p = (int) substr((string) $p,-1);
            }

            $sum+= $p;
        }

        if($sum > 9){
            $sum = (int) substr((string) $sum,-1);
        }
        $this->control = 10 - $sum;

        //10-0 case
        if($this->control > 9){
           $this->control =  (int) substr((string) $this->control,-1);
        }


        if($this->control !== (int) substr($pesel,-1) ){
            $this->error = 'PESEL number is invalid';
            return  false;
        }

        return true;
    }
}
