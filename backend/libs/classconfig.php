<?php 
/**
 * Config YT Artist Player
 *
 * @author Fritz Hoste <hoste.fritz@gmail.com>
 * @copyright 2015 Fritz Hoste
 */

// Change here the LAST FM API KEY
define("LASTFMAPI", '');

// Change here the GOOGLE KEY
define("GOOGLEAPI", '');

// Change here de folder the script is put into.
define('ROOTMAP', '');

// DO NOT EDIT BELOW THIS LINE
class YT_Artist_Player {

    private $ARTIST;
    private $NUMBER_TRACKS;
    private $SKIN_COLOR;
    private $WIDTH;
    private $HEIGHT;

    /**
    * Constructor
    *
    * @param string $artist correct artist name
    * @param string $number_tracks returns the count of tracks that were specified
    * @param string $defaultlimit Default Return Limit
    * @return return of an iframe based on the params
    */
    public function show($artist = null, $number_tracks = 5, $skin = 0, $width = 300, $height = 550){
        if(is_null($artist)){
            echo "Need an artist!";
            return false;
        }

        $this->ARTIST = $artist;
        $this->NUMBER_TRACKS =  $number_tracks;
        $this->SKIN_COLOR = $skin;
        $this->WIDTH = $width;
        $this->HEIGHT = $height;

        echo '<iframe id="lstfmytplayer" scrolling="no" src="'.constant("ROOTMAP").'player.php?a='.$this->ARTIST.'&cnt='.$this->NUMBER_TRACKS.'&skin='.$this->SKIN_COLOR.'" width="'.$this->WIDTH.'" height="'.$this->HEIGHT.'" frameBorder="0" >Browser not compatible.</iframe>';
    }
    

}

?>