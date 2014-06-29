<?php

namespace Liriope\Component\Test;

function doCURL($url) {
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    curl_exec($ch);

    // get the response headers
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // close cURL resource
    curl_close($ch);

    return $httpcode;
}

class Colors {
    private $foreground_colors = array();
    private $background_colors = array();

    public function __construct() {
        // Set up shell colors
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
    }

    public function text($string, $foreground_color=NULL, $background_color=NULL) {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return $colored_string; 
    }
}

class Expect {
    public function __construct() {
        $this->color = new Colors();
        $this->count = 0;
        $this->passed = 0;
        $this->failed = 0;
    }

    public function getResults() {
        echo "\n" . $this->color->text($this->getCount() . " tests run", 'white') . " (" .
             $this->color->text($this->passed . " PASSED", 'green');
        if($this->failed > 0) {
        echo " / " .
             $this->color->text($this->failed . " FAILED", 'red');
        }
        echo ")" . "\n";
    }

    public function getCount() {
        return $this->count();
    }

    private function count($plus=NULL) {
        if( $plus === NULL ) return $this->count;
        $this->count++;
    }

    private function reportGrade($failed) {
        if( $failed ) { $this->failed++; }
        else { $this->passed++; }
        $this->count(1);
    }

    private function echoPass() { echo $this->color->text('PASS', 'green') . "\n"; }
    private function echoFail() { echo $this->color->text('FAIL', 'red') . "\n"; }

    public function wantBool($subject, $value) {
        $failed = 0;
        echo "  - is an boolean: ";
        if(is_bool($subject)) { $this->echoPass(); }
        else { $this->echoFail(); $failed = 1; }
        if(isset($value)) {
            echo "  - is " . $this->color->text(($value ? "TRUE" : "FALSE"), 'yellow') . ": ";
            if( $subject === $value) { $this->echoPass(); }
            else { $this->echoFail(); $failed = 1; }
        }
        $this->reportGrade($failed);
    }

    public function wantString($subject, $value=NULL, $equal=1) {
        $failed = 0;
        echo "  - is a string: ";
        if(is_string($subject)) {
            $this->echoPass();
        } else {
            $this->echoFail();
            $failed = 1;
        }
        if(isset($value)) {
            echo "  - is " . ($equal ? '' : 'NOT ') . $this->color->text("'" . $value . "'", 'yellow') . ": ";
            if( !$equal || $subject === $value) {
                $this->echoPass();
            } else {
                $this->echoFail();
                echo '    - instead it was : ' . $this->color->text($subject, 'red') . "\n";
                $failed = 1;
            }
        }
        $this->reportGrade($failed);
    }

    public function wantInteger($subject, $value=NULL) {
        $failed = 0;
        echo "  - is an integer: ";
        if(is_int($subject)) {
            $this->echoPass();
        } else {
            $this->echoFail();
            $failed = 1;
        }
        if(isset($value)) {
            echo "  - is $value: ";
            if($subject == $value) { $this->echoPass(); }
            else { $this->echoFail(); $failed = 1; }
        }
        $this->reportGrade($failed);
    }

    public function wantObject($subject, $type=NULL) {
        $failed = 0;
        echo "  - is an object: ";
        if( is_object($subject)) {
            $this->echoPass();
        } else {
            $this->echoFail();
            $failed = 1;
        }
        if( isset($type)) {
            echo "  - is an instance of ". $this->color->text($type, 'yellow') . ": ";
            if( is_a($subject, $type)) { $this->echoPass(); }
            else { $this->echoFail(); $failed = 1; }
        }
        $this->reportGrade($failed);
    }
}

?>
