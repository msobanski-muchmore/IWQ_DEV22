<?php


use App\Helper\PeselParser;
use App\Validator\PeselValidator;
use PHPUnit\Framework\TestCase;

class PeselTest extends TestCase
{

        protected ?PeselValidator $validator = null;
        protected ?PeselParser $parser = null;

        public function setUp():void{
            $this->validator = new PeselValidator();
            $this->parser = new PeselParser();
        }

        public function tearDown():void{
            $this->validator = null;
            $this->parser = null;
        }

        public function testGoodPeselIsOk(){
            //not real pesels generated on http://generatory.it/ page
            $pesels = [
                '80010133156',
                '80010143432',
                '80010143432',
                '80010136357',
                '80010183793',
                '71081722950'
            ];

            foreach($pesels as $pesel){
                $ok = $this->validator->validate($pesel);
                $this->assertEquals(true,$ok);

            }
        }

        public function testBadPeselIsBad(){
            //fake pesels
            $pesels = [
                '80010133155',
                '80010143431',
                '80010143434',
                'dfdffdffdfd',
                '78783748743',
                '34934838383',
                '3493483748733',
                '349348374832',
            ];

            foreach($pesels as $pesel){
                $ok = $this->validator->validate($pesel);
                $this->assertEquals(false,$ok);

            }
        }

    public function testControlOk(){
        //example from https://obywatel.gov.pl/pl/dokumenty-i-dane-osobowe/czym-jest-numer-pesel
        $pesel = '02070803628';

        $ok = $this->validator->validate($pesel);
        $this->assertEquals(true,$ok);
        $this->assertEquals(8,$this->validator->getControl());

    }

    public function testDateOfBirthIsOk(){

        $pesels = [
            ['80010133156','1980-01-01'],
            ['08282803410','2008-08-28'],
            ['89923011111','1889-12-30'],

        ];
        foreach($pesels as $pesel){
            $this->parser->setPesel($pesel[0]);
            $dob = $this->parser->getDateOfBirthText();
            $this->assertEquals($pesel[1],$dob);
        }
    }

    public function testGenderIsOk(){

        $pesels = [
            ['80010133156',PeselParser::GENDER_MALE],
            ['08282803410',PeselParser::GENDER_MALE],
            ['89923011111',PeselParser::GENDER_MALE],
            ['08282841281',PeselParser::GENDER_FEMALE],
            ['08282843320',PeselParser::GENDER_FEMALE],
            ['71081739509',PeselParser::GENDER_FEMALE],

        ];
        foreach($pesels as $pesel){
            $this->parser->setPesel($pesel[0]);
            $g = $this->parser->getGenderText();
            $this->assertEquals($pesel[1],$g);
        }
    }

}
