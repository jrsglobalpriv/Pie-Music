window.ytArtist = window.ytArtist || {};
var playerTotalTime = null;
var myTrackBar = null;
var ytIds = {};
$('#playervid').hide();

var player = videojs('playervid', { "techOrder": ["youtube"]}).ready(function() {
	this.on('play', function() {
		playerTotalTime = player.duration();
		$('#totaldur').text(ytArtist.setSeconds2Time(playerTotalTime));
		myTrackBar = setInterval(function() {
 			var playerCurrentTime = player.currentTime();
 			var playerTimeDifference = (playerCurrentTime / playerTotalTime) * 100;
 			var fractionBuffer = player.bufferedPercent() *  100;
 			$('.track').width(playerTimeDifference + '%');
 			$('.player-tracking-bar').html(ytArtist.setSeconds2Time(playerCurrentTime));
 			$('.buffer').width(fractionBuffer + '%');
 			$('#current-time').text(ytArtist.setSeconds2Time(playerCurrentTime));
 		}, 1000);
 		$('#btnPause').show();
 		$('#btnPlay').hide();

	});
	this.on('ready', function(){
		$('.sound-bar').height(this.volume() * 100 + '%');
	});
	this.on('pause', function(){
		$('#btnPlay').show();
 		$('#btnPause').hide();
	});
	this.on('ended', function() {
		clearTimeout(myTrackBar);
	});
	this.on('error', function(e){
		next();
	});

});



ytArtist.n = function(n){
	return n > 9 ? "" + n: "0" + n;
}

ytArtist.setSeconds2Time = function(t) {
	if(typeof t !== 'number') t = 0;
	return ytArtist.n(parseInt(t / 60)) + ':' + ytArtist.n(parseInt(t % 60));
}


$(document).ready(function() {
	var img = $("#artistimg")
	var imgUrl = img.attr("src");
	img.hide();
	$('.player-artist').css("background", "url("+imgUrl+") no-repeat center top scroll");
	$('.playing').hide();
	$('.spinner').hide();

	var playerHeight = $('.player-yt').height();
	var footerHeight = 0;
	//footerHeight = $('.playlist-footer').height();

	var docHeight = window.parent.$("iframe").height();

	$('.playlist-body').css('margin-top', playerHeight);

	$('.playlist-body').css('height', (docHeight - playerHeight - footerHeight));

	$('.player-artist p').css('bottom','0');


	ytArtist.playAnimation = function(){

		$('.player-artist p').css('bottom','-55px');
		$('.time').css('top','-23px');
		setTimeout(function(){
			$('.playing').show();
			$('.time').css('top','0px');
			$('.player-artist p').css('bottom','0px');
		}, 500);


	}

	$('.sound-bg').click(function($e) {
		var posY = $(this).offset().top;
		var barHeight = Math.floor(100 - ((($e.pageY - posY) / $(this).height()) * 100));
		$('.sound-bar').height(barHeight + '%');
		player.volume(barHeight /100);
	});


	$('.player-tracking').click(function($e) {
		var posX = $(this).offset().left;

		var barHeight = Math.floor(((($e.pageX - posX) / $(this).width()) * 100));
		$('.track').width(barHeight + '%');

		var sec = (barHeight / 100)*playerTotalTime;
		player.currentTime(sec);

	});

	$('#ytplay').click(function(e){
		e.preventDefault();
		if($('#btnPlay').is(":visible")){
			player.play();

		}else{
			player.pause();
		}

	});
	function next(){
		prevItem = $('ul.tracks li.active').next();
		
		if(prevItem.length > 0){
			var artist = prevItem.find('h2').text();
			var title = prevItem.find('h1').text();
			$('ul.tracks li.active').removeClass('active');
			$('.spinner').show();
			$.ajax({
				type: "GET",
				url: "libs/ajax.functions.php",
				data: "ytTitle=" + title + "&ytArtist=" + artist,
				success: function(result){
					ytIds = JSON.parse(result);
					prevItem.addClass('active');
					$('.spinner').hide();
					player.src('http://www.youtube.com/watch?v='+ytIds.id);
					$("#ytlink").attr("href", 'http://www.youtube.com/watch?v='+ytIds.id)
					$('#ytlink').attr("target", '_blank');
				$('#playervid').show();
				player.play();
					ytArtist.playAnimation();
				$('#track-title').html(title + '<br/>');
			}
		});

		}
	}
	$('#btnNext').click(function(e){	
		next();
	});

	$('#btnPrev').click(function(e){
		nextItem = $('ul.tracks li.active').prev();

		if(nextItem.length > 0){
			var artist = nextItem.find('h2').text();
			var title = nextItem.find('h1').text();
			$('ul.tracks li.active').removeClass('active');
			$('.spinner').show();
			$.ajax({
				type: "GET",
				url: "libs/ajax.functions.php",
				data: "ytTitle=" + title + "&ytArtist=" + artist,
				success: function(result){
					ytIds = JSON.parse(result);
					nextItem.addClass('active');
					$('.spinner').hide();
					player.src('http://www.youtube.com/watch?v='+ytIds.id);
					$("#ytlink").attr("href", 'http://www.youtube.com/watch?v='+ytIds.id)
					$('#ytlink').attr("target", '_blank');
				$('#playervid').show();
				player.play();
					ytArtist.playAnimation();
				$('#track-title').html(title + '<br/>');
			}
		});

		}


	});


	$('ul.tracks li').click(function(e){
		var self = $(this);
		$('.time').hide();
		var lis = $('ul.tracks li')
		lis.removeClass('active');
		var artist = self.find('h2').text();
		var title = self.find('h1').text();
		

		$('.spinner').show();
		$.ajax({
			type: "GET",
			url: "libs/ajax.functions.php",
			data: "ytTitle=" + title + "&ytArtist=" + artist,
			success: function(result){
				ytIds = JSON.parse(result);
				self.addClass('active');
				$('.spinner').hide();
				player.src('http://www.youtube.com/watch?v='+ytIds.id);
				$("#ytlink").attr("href", 'http://www.youtube.com/watch?v='+ytIds.id)
				$('#ytlink').attr("target", '_blank');
				$('#playervid').show();
				player.play();
				ytArtist.playAnimation();
				$('#track-title').html(title + '<br/>');
			}
		});
		

	});

	$("#volLink").click(function(evt) {
		evt.preventDefault();
		if(!$(event.target).hasClass('volm')){
			if($('#volOn').is(":visible")){
				$('#volOff').show();
				$('#volOn').hide();
				player.volume(0);
			}else{
				$('#volOn').show();
				$('#volOff').hide();
				player.volume(1);
			}
		}
		
	});

});