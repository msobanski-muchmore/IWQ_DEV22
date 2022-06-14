<?php


namespace App\Helper;


class PeselParser
{

    const GENDER_FEMALE = 'female';
    const GENDER_MALE = 'male';

    protected string $pesel;

    /**
     * @return string
     */
    public function getPesel(): string
    {
        return $this->pesel;
    }

    /**
     * @param string $pesel
     */
    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }


    public function getDateOfBirthText(): string {

        if(empty($this->pesel)){
            return '';
        }

        $years_dec = (int)substr($this->pesel,0,2);
        $day = substr($this->pesel,4,2);
        $semi_month = (int)substr($this->pesel,2,2);

        $difs = [
            1900 => 0,
            1800 => 80,
            2000 => 20,
            2100 => 40,
            2200 => 60,
        ];

        $dateOfBirthText = '';

        foreach($difs as $century => $add){
           $month = $semi_month - $add;
           if($month < 13 && $month > 0){
               $dateOfBirthArr = [];
               $dateOfBirthArr[] = (string) ($century+$years_dec);
               $dateOfBirthArr[] = str_pad((string) $month,2,'0',STR_PAD_LEFT);
               $dateOfBirthArr[] = $day;
               $dateOfBirthText = implode("-",$dateOfBirthArr);
               break;
           }
        }

        if(empty(date($dateOfBirthText))){
            return  'Parsing Date Error';
        }

      return $dateOfBirthText;
    }

    public function getGenderText():string {
        $gender_number = (int) substr($this->pesel,6,4);

        if($gender_number % 2 == 0){
            return self::GENDER_FEMALE;
        }
        return  self::GENDER_MALE;
    }
}
