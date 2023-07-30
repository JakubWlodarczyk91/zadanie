<?php

namespace Tests\Unit;

use Tests\TestCase;

class PhoneKeypadTest extends TestCase
{
    /**
     * Test PhoneKeypadController@mapTextToPhoneKeypad.
     */
    public function testMapTextToPhoneKeypad(): void
    {
        $sentencesAndResults = [
            ['sentence' => 'Bury Kot.', 'result' => '22 88 777 999 0 #55 666 8 *'],
            ['sentence' => 'Ala ma kota.', 'result' => '2 555 2 0 6 2 0 55 666 8 2 *'],
            ['sentence' => 'Pragmatyczny Programista!', 'result' => '7 777 2 4 6 2 8 999 222 9999 66 999 0 #7 777 666 4 777 2 6 444 7777 8 2 ***'],
            ['sentence' => 'Trans.eu Road Transport Platform', 'result' => '8 777 2 66 7777 * 33 88 0 #777 666 2 3 0 #8 777 2 66 7777 7 666 777 8 0 #7 555 2 8 333 666 777 6'],
        ];

        foreach ($sentencesAndResults as $sentencesAndResult){
            $response = $this->get('/api/zadanie/'.$sentencesAndResult['sentence']);
            $content = $response->content();
            $this->assertTrue($content == $sentencesAndResult['result']);
        }
    }
}
