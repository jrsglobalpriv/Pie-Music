<?php
/**
 * Lib YT Artist Class
 *
 * @author Fritz Hoste <hoste.fritz@gmail.com>
 * @copyright 2014 Fritz Hoste
 */
class LibYT {

    private $APIKEY = null;
   

    const API_ENDPOINT = 'http://ws.audioscrobbler.com/2.0/?method=';

    private function curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    private function firstProperty($obj) {
        foreach ($obj as $prop) {
            return $prop;
        }
        return null;
    }


    /**
    * Constructor
    *
    * @param string $lastfmapi LastFM API KEY
    * @param string $defaultlimit Default Return Limit
    */
    public function __construct($lastfmapi){
        $this->APIKEY = $lastfmapi;
    }


    /**
    * @param string $artist artist string name
    * @param int $limit Count of the result you want to return (default 20)
    * @return array returns array of artist info based on params
    */
    public function searchArtistTracks($artist, $limit = 5) {

    	$ar = new stdClass();

		$urlbio = self::API_ENDPOINT . 'artist.getinfo&artist=' . urlencode($artist) . '&api_key=' . $this->APIKEY . '&format=json';

		$contentbio = $this->curl($urlbio);
		$contentbio = json_decode($contentbio);

		if (!$contentbio || $contentbio->error) {
			echo '<p>'.$contentbio->message."<p/>";
			return 2;            
		}

		$artistbio = $contentbio->artist;
        
       
		$b = new stdClass();
		$b->name = $artistbio->name;
		if (isset($artistbio->image) && is_array($artistbio->image)) {
			$largeCover = end($artistbio->image);
			$b->cover_image = $this->firstProperty($largeCover);
		}

		$ar->bio = $b;
        
        $tracks = array();
         
        $url = self::API_ENDPOINT . 'artist.gettoptracks&artist=' . urlencode($artist) . '&limit=' . $limit . '&api_key=' . $this->APIKEY . '&format=json';
        $content = $this->curl($url);
        
        $content = json_decode($content);
      
        if (!$content || $content->error) {
            echo '<p>'.$content->message."<p/>";
            return null;            
        }

        $tracks = $content->toptracks;

        if(isset($tracks->total)){
            return null;
        }
        
        $ret = array();
        foreach ($tracks->track as $track) {
            $o = new stdClass();
            $o->artist = $track->artist->name;
            $o->title = $track->name;

            if (isset($track->image) && is_array($track->image)) {
                $largeCover = end($track->image);
                $o->cover_image = $this->firstProperty($largeCover);
            }

            if (empty($o->cover_image)) {
                $o->cover_image = 'img/standard_cover.png';
            }

            array_push($ret, $o);
        }

        $ar->tracks = $ret;
       
        
        return ($ar);
    }

    /**
    * @param string $artist 
    * @param string $title
    * @return returns an youtube link
    */
    private function altUrl($sxml, $entry_num) {
        return (string) $sxml->entry[$entry_num]->link[0]->attributes()->href[0];
    }

    /**
    * @param string link
    * @return returns an youtube id form a given link
    */
    private function getYtidFromLink($link) {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $link, $matches);
        $id = $matches[1];
        return $id;
    }

    /**
    * @param string xml
    * @return returns alternative ids on error
    */
    private function getAlternativeIds($sxml, &$ret) {
        $ret->alt_ids = array();
        $alt1 = @$this->altUrl($sxml, 1);
        if ($alt1) {
            array_push($ret->alt_ids, $this->getYtidFromLink($alt1));
        }
        $alt2 = @$this->altUrl($sxml, 2);
        if ($alt2) {
            array_push($ret->alt_ids, $this->getYtidFromLink($alt2));
        }
    }

    /**
    * @param string artist
    * @param string title
    * @return returns 3 youtube videos from the given artist and title
    */
    public function findVideo($artist, $title) {
        $matcher = $artist . ' ' . $title;
        $matcher = urldecode($matcher);

         $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . urlencode($matcher) . '&maxResults=1&key=' . constant("GOOGLEAPI");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);
        curl_close($ch);

     
        $obj = json_decode($json);
        $id = $obj->items[0]->id->videoId;

        $ret = new stdClass();
     
        $ret->id = $id;

     

        echo json_encode($ret);
    }
    
}
?>