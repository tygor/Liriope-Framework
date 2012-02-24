<?php
/**
 * XmlModel.class.php
 */

// $xmlUrl = "feed.xml"; // XML feed file/URL
// $xmlStr = file_get_contents($xmlUrl);
// $xmlObj = simplexml_load_string($xmlStr);
// $arrXml = objectsIntoArray($xmlObj);
// print_r($arrXml);

class XmlModel {
  var $xmlUrl;
  var $setFlag = false;
  var $xmlString;
  var $loadFlag = false;
  var $_xml; // the XML object
  
  function __construct( $file=false, $feed=false )
  {
    if( !empty( $file ))
    {
      $this->setFile( $file, $feed );
      $this->loadFile();
    }
  }

  // setFile
  // 
  // Select the file to be read and written to
  public function setFile( $file=false, $feed=false )
  {
    try {
      // was a file name given?
      if( empty( $file )) throw new Exception( __METHOD__ . ': No file was passed.' );
      // can we see that file?
      // if it's a feed, then skip the check
      if( !$feed && !is_readable( $file )) throw new Exception( __METHOD__ . ' doesn\'t think that file exists <em>'.$file.'</em>.' );
      // ok, then let's get it loaded
      $this->xmlUrl = $file;
      $this->setFlag = true;
      $this->loadFile();
    } catch( Exception $e ) {
      LiriopeException( $e );
    }
  }

  // getFile
  //
  // Returns the set file URL
  function getFile()
  {
    if( $this->setFlag ) return $this->xmlUrl;
    return false;
  }

  // loadFile
  //
  // Load the contents of the object's set XML file
  protected function loadFile()
  {
    try {
      // if a file set?
      if( !$this->setFlag )throw new Exception( __METHOD__ . ': There isn\'t a XML set to load.' );
      // then grab the contents
      $this->xmlStr = file_get_contents( $this->getFile() );
      $this->_xml = simplexml_load_string( $this->xmlStr );
      $this->loadFlag = true;
    } catch( Exception $e ) {
      die( 'Caught Exception: ' . $e->getMessage() . "\n" );
    }
  }

  // get
  //
  // Return the value for the requested row
  function get( $name=false )
  {
    // if set and loaded, then return the xml object
    if( $this->setFlag && $this->loadFlag ) return $this->_xml;
    return false;
  }
}

