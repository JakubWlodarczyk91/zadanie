<?php

namespace App\Http\Controllers;

class PhoneKeypadController extends Controller
{
    /**
     * Phone keypad.
     * @var array[]
     */
    private array $phoneKeypad;

    /**
     * Representation of space char -> ' '
     * @var string
     */
    static string $space = 'SPACE';

    /**
     * Change lowercase to uppercase button.
     * @var string
     */
    static string $uppercase = 'UPPERCASE';

    public function __construct()
    {
        $this->phoneKeypad = [
            '1' => [],
            '2' => ['A', 'B', 'C'],
            '3' => ['D', 'E', 'F'],
            '4' => ['G', 'H', 'I'],
            '5' => ['J', 'K', 'L'],
            '6' => ['M', 'N', 'O'],
            '7' => ['P', 'Q', 'R', 'S'],
            '8' => ['T', 'U', 'V'],
            '9' => ['W', 'X', 'Y', 'Z'],
            '*' => ['.', ',', '!', '?'],
            '0' => ['SPACE'],
            '#' => ['UPPERCASE']
        ];
    }

    /**
     * Map text to phone keypad. For example text "Bury Kot" is map to "22 88 777 999 0 #55 666 8 *".
     * Method accept only english letters.
     * @param string $text
     * @return string
     */
    public function mapTextToPhoneKeypad(string $text): string
    {
        $resultCode = '';
        $chars = str_split($text);
        foreach ($chars as $position => $char){

            // If first char is lowercase press uppercase/lowercase button.
            if ($position == 0 && ctype_lower($char)){
                $uppercaseKey = $this->keySearch(self::$uppercase);
                $resultCode .= $this->resultCodeFromKey($uppercaseKey);
            }

            // If char is uppercase and there are no dot and space before them press uppercase/lowercase button.
            if ($position != 0 && ctype_upper($char) && !$this->detectDotAndSpace($chars, $position)){
                $uppercaseKey = $this->keySearch(self::$uppercase);
                $resultCode .= $this->resultCodeFromKey($uppercaseKey);
            }

            $key = $this->keySearch($char);
            $resultCode .= $this->resultCodeFromKey($key);

            if (next($chars) != false){
                $resultCode .= ' ';
            }
        }

        return $resultCode;
    }

    /**
     * Search char in phone keypad and return button parameters.
     * @param string $char
     * @return array|null
     */
    private function keySearch(string $char): ?array
    {
        $char = strtoupper($char);

        if ($char == ' '){
            $char = self::$space;
        }

        foreach ($this->phoneKeypad as $number => $letters) {
            foreach ($letters as $repeat => $letter){
                if ($letter == $char){
                    return ['number' => $number, 'repeat' => ++$repeat];
                }
            }
        }

        return null;
    }

    /**
     * Return code from key parameters.
     * @param array $key
     * @return string
     */
    private function resultCodeFromKey(array $key): string
    {
        $resultCode = '';
        for($repeat = 1; $repeat <= $key['repeat']; $repeat++){
            $resultCode .= $key['number'];
        }

        return $resultCode;
    }


    /**
     * Detect dot and space before char in specific position.
     * @param $chars
     * @param $position
     * @return bool
     */
    private function detectDotAndSpace($chars, $position): bool
    {
        if ($position > 2){
            $spacePosition = $position - 1;
            $dotPosition = $position - 2;
            if ($chars[$spacePosition] == ' ' && $chars[$dotPosition] == '.'){
                return true;
            }
        }

        return false;
    }
}
