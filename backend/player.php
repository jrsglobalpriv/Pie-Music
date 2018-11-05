<?php 
$artist = $_GET['a'];
$count = $_GET['cnt'];
$skin = $_GET['skin'];

include_once('libs/classconfig.php');
include_once('libs/lib.ytartist.php');

$lbyt = new LibYT(constant("LASTFMAPI"));

$artistresul = $lbyt->searchArtistTracks($artist, $count);
$skincolr = "@skin".$skin;

if($artistresul->tracks != null){
   $tracks = $artistresul->tracks;
   $bio = $artistresul->bio;

?>
   <html>
   <head>
    
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.css" rel="stylesheet">
       <link href="css/skins/skin<?php echo $skin ?>.css" rel="stylesheet">
      <link href="http://vjs.zencdn.net/4.11/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/4.11/video.js"></script>
      <script src="js/youtube.js"></script>
   </head>
   <body>
      <div class="player">
         <div class="spinner"></div>
         <div class="time playing"><span id="current-time">00:00</span> / <span id="totaldur">00:00</span></div>

         <div class="player-yt">
            <div class="player-artist">
               <img id="artistimg" src="<?php echo $bio->cover_image; ?>" />
               <p>
                  <i class="fa fa-volume-up voltop playing"></i>
                  <span id="track-title" class="playing"></span><?php echo $bio->name; ?>
               </p>
            </div>
            <div class="player-tracking">
               <div class="buffer"> </div>
               <div class="track">
                  <span class="player-tracking-bar"></span>
               </div>
            </div>
            <div class="player-controls">
               <a href="#" id="ytlink">
                   <i class="fa fa-youtube"></i>
                   <video id="playervid" src="" class="video-js vjs-default-skin" preload="auto" width="100%" height="160px">
                 
               </a>
               <a href="#" id="btnPrev"><i class="fa fa-fast-backward"></i></a>
               <a href="#" id="ytplay">
                  <i id="btnPlay" class="fa fa-play"></i>
                  <i id="btnPause"  class="fa fa-pause"></i>
               </a>
               <a href="#" id="btnNext"><i class="fa fa-fast-forward"></i></a>
               <a href="#" id="volLink">
                  <div class="volume">
                     <div class="volume-button" title="Volume">
                        <i class="fa fa-volume-up" id="volOn"></i>
                        <i class="fa fa-volume-off" id="volOff"></i>
                     </div>
                     <div class="volume-adjust volm">
                        <div class="sound-bg volm">
                           <div class="sound-bar volm" style="height: 90%;"></div>
                        </div>
                     </div>
                  </div>
               </a>
            </div>
         </div>
         <div class="playlist-body">
            <ul class="tracks">
              <?php 
              foreach ($tracks as $track) {
                echo "<li><i id='togglePlayLi' class='fa fa-music'></i>";
                echo "<h1>$track->title</h1>";
                echo "<h2>$track->artist</h2></li>";
             }

             ?>
          </ul>
       </div>
         <!-- <div class="playlist-footer">
            <form class="search"><input class="searchTerm" id="searchArtist" placeholder="Enter your search term ..." /></form>
         </div> -->
      </div>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/0.6.0/pace.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   <script src="js/main.min.js"></script>
</body>
</html>
<?php 
}elseif(!$artistresul == 2){
  echo "No artist found with the name: <strong>" . $artist . "</strong>. Make sure it is listed on LastFM."; 
} 
?>