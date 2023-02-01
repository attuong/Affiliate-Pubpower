<?php

/**
 * Colorful Dumper (part of Lotos Framework)
 *
 * Copyright (c) 2005-2010 Artur Graniszewski (aargoth@boo.pl) 
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 * - Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 * - Neither the name of the Lotos Framework nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Lotos
 * @subpackage Dumper
 * @copyright  Copyright (c) 2005-2010 Artur Graniszewski (aargoth@boo.pl)
 * @license    New BSD License
 * @version    $Id$
 */
class Dumper {

    /**
     * Background CSS color, for example: "#000000" or "black"
     * 
     * @var string
     */
    public static $backgroundColor = 'white';

    /**
     * Braces CSS color, for example: "#000000" or "black"
     * 
     * @var string
     */
//	public static $bracesColor = 'red';
    public static $bracesColor = '#777777';

    /**
     * Data type CSS color, for example: "#000000" or "black"
     * 
     * @var string
     */
    public static $typeColor = 'black';

    /**
     * Numeric value CSS color, for example: "#000000" or "black"
     * 
     * @var string
     */
    public static $numericValueColor = 'green';

    /**
     *
     * @var type 
     */
    public static $floatValueColor = '#FC770A';

    /**
     * put your comment there...
     * 
     * @var string
     */
//	public static $stringsValueColor = 'blue';
    public static $stringsValueColor = '#cc0000';

    /**
     * String value CSS color, for example: "#000000" or "black"
     * 
     * @var string
     */
//	public static $indexColor = '#FF8000';
    public static $indexColor = '#666666';

    /**
     *
     * @var type 
     */
    public static $boolenColor = "blue";

    /**
     *
     * @var type 
     */
    public static $numberValueArray = "#888888";

    /**
     *
     * @var type 
     */
    public static $nullValue = "blue";

    /**
     * Sets custom color theme in one static method call.
     * 
     * @param string $backgroundColor Background CSS color, for example: "#000000" or "black"
     * @param string $indexColor Index CSS color, for example: "#000000" or "black"
     * @param string $bracesColor Braces CSS color, for example: "#000000" or "black"
     * @param string $typeColor Data type CSS color, for example: "#000000" or "black"
     * @param string $numericValueColor Numeric value CSS color, for example: "#000000" or "black"
     * @param string $stringsValueColor String value CSS color, for example: "#000000" or "black"
     * @return void
     */
    public static function setColors($backgroundColor, $indexColor, $bracesColor, $typeColor, $numericValueColor, $stringsValueColor, $boolenColor, $floatValueColor, $numberValueArray, $nullValue) {
        self::$indexColor = $indexColor;
        self::$backgroundColor = $backgroundColor;
        self::$bracesColor = $bracesColor;
        self::$typeColor = $typeColor;
        self::$numericValueColor = $numericValueColor;
        self::$stringsValueColor = $stringsValueColor;
        self::$boolenColor = $boolenColor;
        self::$floatValueColor = $floatValueColor;
        self::$numberValueArray = $numberValueArray;
        self::$nullValue = $nullValue;
    }

    /**
     * Displays structured information about one or more expressions that includes its type and value. 
     * 
     * @param mixed $str Structure to display.
     * @return void
     */
    public static function dump($str) {
        echo '<pre style="color: ' . self::$bracesColor . '; background-color: ' . self::$backgroundColor . ';" >';
        $header = "\"<span style=\\\"color: " . self::$indexColor . "\\\">\$matches[1]</span><span style=\\\"color: " . self::$bracesColor . "\\\">\$matches[2]</span><span style=\\\"color: " . self::$typeColor . "\\\">\$matches[3]</span>\"";

        $function = create_function('$matches', '
            $count = count($matches);
			//var_dump($count);
			//var_dump($matches[4]);
			//var_dump($matches[5]);
			//var_dump($matches[6]);
            if($count == 7 && $matches[4] === $matches[5] && $matches[6] && ($matches[6] == "(true)" || $matches[6] == "(false)")) {
                $ret = ' . $header . '."<span style=\"color: ' . self::$boolenColor . '\">$matches[5]</span>";
            } else if($count == 7 && $matches[4] === $matches[5] && $matches[6]) {
				$ret = ' . $header . '."<span style=\"color: ' . self::$bracesColor . '\">$matches[5]</span>";
				$pos_int = strpos($matches[3], "int");
				$pos_float = strpos($matches[3], "float");
				$pos_array = strpos($matches[3], "array");
                $numberic = str_replace(array("(",")"),array("",""),$matches[6]);
				if($pos_int !== false || $pos_float !== false){
					if((int) $numberic == $numberic){
						$ret = ' . $header . '."<span style=\"color: ' . self::$numericValueColor . '\">$matches[5]</span>";
					}else{
						$ret = ' . $header . '."<span style=\"color: ' . self::$floatValueColor . '\">$matches[5]</span>";
					}
				}
				if($pos_array !== false){
					$ret = ' . $header . '."<span style=\"color: ' . self::$numberValueArray . '\">$matches[5]</span>";
				}
            } else if($count == 7) {
                $ret = ' . $header . '."<span style=\"color: ' . self::$bracesColor . '\">$matches[5]</span><span style=\"color: ' . self::$stringsValueColor . '\">$matches[6]</span>";
            } else if($count == 4){
				$pos_null = strpos($matches[3], "NULL");
				$pos_commentid = strpos($matches[1], "commentid");
				$number_arr = ($pos_commentid !== false) ? "" : $matches[1];
				if($pos_null !== false){
					if($number_arr){
						$ret = "<span style=\"color: ' . self::$indexColor . '\">" . $number_arr. "</span>=>\n <span style=\"padding-left:10px;color: ' . self::$nullValue . '\">$matches[3]</span>";
					}else{
						$ret = "<span style=\"color: ' . self::$nullValue . '\">$matches[3]</span>";
					}
				}else{
					$ret = ' . $header . ';
				}
            } else if($count == 10) {
                $ret = ' . $header . '."<span style=\"color: ' . self::$bracesColor . '\">$matches[6]</span><span style=\"color: ' . self::$typeColor . '\">$matches[8]</span><span style=\"color: ' . self::$numericValueColor . '\">$matches[9]</span>";
            } else if($count == 11) {
                // strings
                $ret = ' . $header . '."<span style=\"color: ' . self::$bracesColor . '\">$matches[5]</span><span style=\"color: ' . self::$stringsValueColor . '\">$matches[10]</span>";
            } else {
                $ret = $matches[0];
            }
            return $ret;
            
        ');
        ob_start();
        @var_dump($str);
        $str = '["commentid"]=>' . "\n " . ob_get_clean();
        $str1 = preg_replace_callback('~(\[[^\]]+\]|[\d]+)(=>\n\s+)([a-zA-Z_\d]+)(((\([^)]+\))((#[\d]+)(\s\([\d]+\)))?)(\s".*")?)?~', $function, $str);

        $str_last = ltrim(substr($str1, strpos($str1, "\n ")));
        echo $str_last;
        echo "</pre>";
    }

}

/**
 * Displays structured information about one or more expressions that includes its type and value. 
 * 
 * @param mixed $str Structure to display.
 * @return void
 */