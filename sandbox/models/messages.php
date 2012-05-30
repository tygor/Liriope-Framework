<?php
/**
 * @package messages
 * @subpackage plg_content_messages
 * @version 1.6 November 5, 2010
 * @author NorthRockHillChurch http://www.northrockhill.com
 * @copyright Copyright (C) 2010 North Rock Hill Church
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.plugin.plugin' );
 
/**
 * @package messages
 * @subpackage plg_content_messages
 */
class plgContentMessages extends JPlugin
{
	function plgContentMessages( &$subject )
	{
		parent::__construct( $subject );
	}

	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe;

		$shortcode = 'message';
		
		// simple performance check to determine whether bot should process further
		// if the shortcode exists in the article text, then proceed
		if ( strpos( $article->text, $shortcode ) === false ) { return true; }
		
		// Get plugin object
		$plugin =& JPluginHelper::getPlugin('content', $shortcode);
		$pluginParams = new JParameter( $plugin->params );

		// define the regular expression to look for (plugin shortcode)
		// ex: {message play="bumper"}images/messages/file.xml{/message}
		//          |            |                   |                          |
		//        open        params              contents                     end
		$pattern = '#{' . $shortcode . '(.*?)}(.*?){/' . $shortcode . '}#s';
		
		// If the plugin is not enabled, replace with "" in the article, and exit pluign code
		if ( !$pluginParams->get( 'enabled', 1 ) ) {
			$article->text = preg_replace( $pattern, '', $article->text );
			return true;
		}

		// find all instances of plugin and put in $matches
		preg_match_all( $pattern, $article->text, $matches );
		// $matches = array
		// $matches[0] = array of each instance of the plugin's full string
		// $matches[1] = array of each instance of the plugin's parameters
		// $matches[2] = array of each instance of the plugin's contents
		
		// Number of times the plugin is in the article
		$count = count( $matches[0] );

		// plugin only processes if there are any instances of the plugin in the text
		if ( $count ) {
			$this->_process( $article, $matches, $count, $pattern );
		}
	}

	function _process( &$article, &$matches, $count, $pattern )
	{
		global $mainframe;

		for ( $i=0; $i < $count; $i++ )
		{
			$params = $this->grabParams( trim( $matches[1][$i] ));
			$load = trim($matches[2][$i]);
			$message = new MessageSeries($load, $params);
			$text = $message->render();

			// now replace that instance of the shortcode with the rendered HTML
			$article->text = str_replace( $matches[0][$i], $text, $article->text );
		}
		
		// add plugin stylesheet
		$document = &JFactory::getDocument();
		$document->addStyleSheet( "/plugins/content/messages/messages.css", "text/css", "all" );
		
		// removes tags without matching module positions
		$article->text = str_replace( $pattern, '', $article->text );
	}
	
	function grabParams( $string )
	{
		// break the string on space characters
		// expect string format: 'name="value" name="value"'
		preg_match_all('#(.*?)=["\'](.*?)["\']#s', trim($string), $matches);
		$count = count($matches[0]);
		for( $i=0; $i<$count; $i++ )
		{
			$params[trim($matches[1][$i])] = trim($matches[2][$i]);
		}
		return $params;
	}
}

/**
 * Message Series Object
 */ 
// define Google Analytics code for easy use below
define(GA_PAGEVIEW_CODE, " onClick=\"javascript:pageTracker._trackPageview('/internal-links/messages/%s');\");");
define(MESSAGE_DATE_FORMAT, "Y-m-d");

class MessageSeries
{
	var $seriesTitle;
	var $seriesImage = array( "url", "label" );
	var $isCurrent = false;
	var $isComing = false;
	var $params;
	var $errors;

	public function MessageSeries( $file=NULL, $params = array() )
	{
		$xml = simplexml_load_file( $file );
		if( !$xml )
		{
			$this->errors[] = "Could not load the Message Series XML file (".$file.")";
			return false;
		}

		// get the parameters
		$this->params = $params;
		
		// now build the series from the XML file
		// test then set each item of the XML file into my object
		$this->seriesTitle = !empty($xml->seriesTitle) ? $xml->seriesTitle : false;
		$this->seriesImage = !empty($xml->seriesImage) ? $xml->seriesImage : false;
		$this->seriesBumper = !empty($xml->seriesBumper) ? $xml->seriesBumper : false;
		if( $xml->flags->current == "true" ) $this->isCurrent = true;
		elseif( $xml->flags->coming == "true" ) $this->isComing = true;
		
		// build the messages if there are any
		if( isset($xml->messages) && isset($xml->messages->message) )
		{
			foreach( $xml->messages->message as $message )
			{
				$this->messages[] = new Message( $message );
			}
		}
	}
	
	function hasErrors()
	{
		return empty( $this->errors ) ? false : true;
	}
	
	function getErrors()
	{
		if( empty( $this->errors )) return fasle;
		return implode( "<br>\n", $this->errors );
	}

	public function getSeriesTitle()
	{
		return $this->seriesTitle;
	}
	
	public function getParameter( $name = NULL )
	{
		if ( empty( $name )) return false;
		return empty( $this->params[$name] ) ? false : $this->params[$name];
	}
	
	/**
	 * getLastMessage()
	 * returns the last message object based on date
	 */
	private function getLastMessage()
	{
		if( !is_array($this->messages) ) { return false; }
		
		$latest = NULL;
		foreach( $this->messages as $key => $message )
		{
			if( is_null($latest) || $message->getDate() > $this->messages[$latest]->getDate() )
			{
				$latest = $key;
			}
		}
		return $this->messages[$latest];
	}

	/**
	 * renderTitleImage()
	 * builds the output of the Title Image section of the
	 * messages module
	 */
	private function renderTitleImage()
	{
		$image = "<img class='title-image' src='%s' alt='%s'>";
		$video = "<a href='%s' rel='rokbox' title='%s' class='title' %s>" .
		         "<span class='play'></span>%s</a>";
		
		// for sure show the image
		$html = sprintf($image, $this->seriesImage->url, $this->seriesTitle);
		
		// depending on the parameters, show either the series bumper, or a message video
		switch ( $this->getParameter('play') )
		{
			case "last":
			case "latest":
				$seriesTitle = $this->seriesTitle;
				$last = $this->getLastMessage();
				$date = date( MESSAGE_DATE_FORMAT, $last->date );
				$lastURL = $last->media['watch']->url;
				if( !empty( $last ) && !empty( $lastURL ))
				{
					$track = sprintf(GA_PAGEVIEW_CODE, "$seriesTitle/$date/watch.html");
					$html = sprintf( $video, $lastURL . "&autostart=1", "$seriesTitle :: $date", $track, $html );
				}
				break;
			default:
			case "bumper":
				if( !empty( $this->seriesBumper ) && !empty( $this->seriesBumper->url ))
				{
					$track = sprintf(GA_PAGEVIEW_CODE, $this->seriesTitle . "/bumper.html");
					$html = sprintf($video, $this->seriesBumper->url . '&autostart=1', 'Play ' . $this->seriesTitle . ' video', $track, $html);
				}
				break;
		}

		return $html;
	}
	
	private function renderFlags()
	{
		$html = "<h3>%s</h3>";
		// check for isComing or isCurrent and if true, show one of them
		if( $this->isCurrent ) return sprintf($html, "Current Series");
		if( $this->isComing )  return sprintf($html, "Coming Series");
		// on error
		return false;
	}

	private function renderMessages()
	{
		$count = count( $this->messages );

		$renderArray = array();
		if( $count ) 
		{
			foreach( $this->messages as $message )
			{
				$render[$message->getDate()] = $message->renderAsRow( $this->seriesTitle );
			}
			// order the new render array by the key (date)
			$render = array_flip($render);
			arsort($render);
			
			// explode the array into a string
			$render = implode( array_flip( $render ));
			
			// return the string
			return $render;
		}
		return false;
	}

	public function render()
	{
		// check for errors
		if( $this->hasErrors ) return $this->getErrors();
		
		$html = "<div id='message-series'>\n" .
		        "\t" . $this->renderTitleImage() . "\n" .
		        "\t<div class='title-text'>\n" .
		        "\t<h2>" . $this->getSeriesTitle() . "</h2>\n" .
		        "\t" . $this->renderFlags() . "\n" .
		        "\t</div>\n" .
		        "\t<table border='0'>\n" .
		        "\t\t<tr>\n" .
		        "\t\t\t<th>Date</th>\n" .
		        "\t\t\t<th>Title</th>\n" .
		        "\t\t\t<th>Speaker</th>\n" .
		        "\t\t\t<th>Media</th>\n" .
		        "\t\t</tr>\n" .
		        $this->renderMessages() .
				"\t\t<tr class='footer'><td></td><td></td><td></td><td></td></tr>\n" .
		        "\t</table>\n" .
		        "</div>";
		        
		return $html;
	}
}

class Message
{
	var $date;
	var $title;
	var $speaker;
	var $media = array();

	public function Message( $object = NULL )
	{
		$this->date = strtotime( $object->date );
		$this->title = $object->title;
		$this->speaker = $object->speaker;
		if( is_object( $object->media ) && count( $object->media ) )
		{
			foreach( $object->media->item as $item )
			{
				$type = strtolower($item->type);
				$this->media[$type] = new Media( $type, $item->url );
			}
		}
	}

	public function getDate()
	{
		return $this->date;
	}
	
	public function getMediaType( $search )
	{
		foreach ( $this->media as $type => $media )
			return $type == strtolower($search) ? $media : false;
	}

	public function renderAsRow( $seriesTitle )
	{
		$html = "<tr class='message-details'><td class='date'>%s</td><td class='title'>%s</td><td class='speaker'>%s</td><td class='media'>%s</td></tr>";
		return sprintf(
			$html,
			date( "Y-M-j", $this->date ),
			$this->title,
			$this->speaker,
			$this->renderMedia( $seriesTitle )
		);
	}

	public function renderMedia( $seriesTitle )
	{
		$render = "<ul class='media-tools'>\n";
		foreach( $this->media as $media )
		{
			$render .= $media->render( $seriesTitle . "/". date( "Y-m-d", $this->date ) ) . "\n";
		}
		$render .= "</ul>\n";
		return $render;
	}
}

class Media
{
	var $type;
	var $url;

	public function Media( $type = NULL, $url = NULL )
	{
		$this->type = $type;
		$this->url = $url;
	}

	public function getType()
	{
		return $this->type;
	}

	private function renderWatch( $seriesTitle )
	{
		$track = sprintf(GA_PAGEVIEW_CODE, $seriesTitle . "/watch.html");
		return "<li class='watch'><a href='%s&autostart=1' rel='rokbox' title='Watch' $track>Watch</a></li>\n";
	}

	private function renderWatchmore( $seriesTitle )
	{
		$track = sprintf(GA_PAGEVIEW_CODE, $seriesTitle . "/additional-media.html");
		return "<li class='watch-more'><a href='%s&autostart=1' rel='rokbox' title='Additional Media' $track>Media</a></li>\n";
	}

	private function renderListen( $seriesTitle )
	{
		$track = sprintf(GA_PAGEVIEW_CODE, $seriesTitle . "/listen.html");
		return "<li class='listen'><a href='%s' rel='rokbox' title='Listen' $track>Listen</a></li>\n";
	}

	private function renderDownload( $seriesTitle )
	{
		$track = sprintf(GA_PAGEVIEW_CODE, $seriesTitle . "/download.html");
		return "<li class='download'><a href='%s' alt='Download' title='Download' $track>Audio</a></li>\n";
	}

	private function renderYoutube()
	{
		return "<li class='youtube'><a href='%s' alt='You Tube' title='You Tube' target='_blank'>You Tube</a></li>\n";
	}

	public function render( $seriesTitle )
	{
		$call = 'render' . $this->type;
		$methodVariable = array( $this, $call );
		if( is_callable( $methodVariable, false, $callable_name ) )
		{
			$html = call_user_func( $methodVariable, $seriesTitle );
			return sprintf( $html, $this->url );
		}

		return false;
	}
}

?>