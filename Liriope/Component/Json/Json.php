<?php

namespace Liriope\Component\Json;

/**
 * The JSON class gives the PHP json functions a brain.
 */

class Json {
    
    // @var string The path to the JSON file
    private $file;
    // @var string The JSON string read from $file
    private $json_string;
    // @var stdClass The JSON object decoded
    private $json_object;
    // @var int The error code associated with the last json_ function
    private $error;

    /**
     * CONSTRUCTOR
     *
     * @param string $file The path to the JSON file
     *
     * @return void
     */
    public function __construct($file=NULL) {
        if($file===NULL) return FALSE;
        $this->file = $file;

        if(!is_file($file)) throw new \Exception('The JSON file given is not a file at all.');
        $this->json_string = file_get_contents($file);

        $this->json_object = json_decode($this->json_string);
        $this->hasErrors();
    }

    public function get() {
        if(empty($this->json_object)) throw new \Exception('No JSON has been loaded yet... sorry.');
        return $this->json_object;
    }

    /**
     * Checks the last call to json_encode or json_decode for errors and throws the appropriate \Exception
     */
    private function hasErrors() {
        $this->error = json_last_error();
        switch($this->error) {
            case JSON_ERROR_NONE:
                return TRUE;
            case JSON_ERROR_DEPTH:
                throw new \Exception(__CLASS__ . ": The maximum stack depth has been exceeded.");
            case JSON_ERROR_STATE_MISMATCH:
                throw new \Exception(__CLASS__ . ": Invalid or malformed JSON.");
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception(__CLASS__ . ": Control character error, possibly incorrectly encoded.");
            case JSON_ERROR_SYNTAX:
                throw new \Exception(__CLASS__ . ": Syntax error.");
            case JSON_ERROR_UTF8:
                throw new \Exception(__CLASS__ . ": Malformed UTF-8 characters, possibly incorrectly encoded.");
            default:
                throw new \Exception(__CLASS__ . ": Unknown error.");
        }
    }
}
