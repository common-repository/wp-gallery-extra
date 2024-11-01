<?php
ini_set( 'display_errors', 0 );
ini_set( 'display_startup_errors', 0 );
if ( !isset( $_REQUEST['audio'] ) )
	die("Your request is not valid.");

require_once 'includes/Mobile_Detect.php';

$detect = new Mobile_Detect;
$is_mobile_os = $detect->isiOS() || $detect->isAndroidOS();

$audio_url = $_REQUEST['audio'];
$cover_url = $_REQUEST['cover'];
$player_type = strtolower( $_REQUEST['player_type'] );
$is_soundcloud = preg_match( '/(?:(?:http|https):\/\/)?(?:www.|m.)?(?:soundcloud.com)\/([^\/]+)\/([^\/]+)/i', $audio_url );

if ( $player_type !== "auto" )
	$is_mobile_os = $player_type === "mobile";
?><!DOCTYPE html>
<html>
	<head>
		<title>WordPress Gallery Extra: Audio Player</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="shrink-to-fit=0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="stylesheet" href="assets/css/plyr.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono:100" rel="stylesheet">
		<style type="text/css">
			/* http://meyerweb.com/eric/tools/css/reset/ 
			   v2.0 | 20110126
			   License: none (public domain)
			*/

			html, body, div, span, applet, object, iframe,
			h1, h2, h3, h4, h5, h6, p, blockquote, pre,
			a, abbr, acronym, address, big, cite, code,
			del, dfn, em, img, ins, kbd, q, s, samp,
			small, strike, strong, sub, sup, tt, var,
			b, u, i, center,
			dl, dt, dd, ol, ul, li,
			fieldset, form, label, legend,
			table, caption, tbody, tfoot, thead, tr, th, td,
			article, aside, canvas, details, embed, 
			figure, figcaption, footer, header, hgroup, 
			menu, nav, output, ruby, section, summary,
			time, mark, audio, video {
				margin: 0;
				padding: 0;
				border: 0;
				font-size: 100%;
				font: inherit;
				vertical-align: baseline;
			}
			/* HTML5 display-role reset for older browsers */
			article, aside, details, figcaption, figure, 
			footer, header, hgroup, menu, nav, section {
				display: block;
			}
			body {
				line-height: 1;
			}
			ol, ul {
				list-style: none;
			}
			blockquote, q {
				quotes: none;
			}
			blockquote:before, blockquote:after,
			q:before, q:after {
				content: '';
				content: none;
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
			}

			/* General */
			html,
			body {
				height: 100%;
				overflow: hidden;
				background-color: #04091B;
				color: #FFF;
				touch-action: auto;
			}
			canvas {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				z-index: 11;
			}
			#cover {
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-image: url('<?php echo $cover_url; ?>');
				background-size: cover;
				background-repeat: no-repeat;
				background-position: 50%;
				-webkit-transform: scale(1.1);
				transform: scale(1.1);
				-webkit-filter: blur(10px);
				filter: blur(10px);
				z-index: 9;
			}
<?php
	if ( $is_mobile_os ) {
?>
			canvas {
				background: rgba(4, 9, 27, 0.6);
			}
<?php
	}
?>

			/* Audio Player */
			.plyr {
				position: fixed;
				width: 100%;
				left: 0;
				bottom: 0;
				z-index: 1000;
			}
			.plyr--audio .plyr__controls {
				border: 0;
				background: transparent;
				color: #FFF;
			}

			/**/
			#spinner {
				width: 100%;
				height: 100%;
				position: absolute;
				top: 0;
				bottom: 0;
				right: 0;
				left: 0;
				background: #04091B;
				z-index: 2000;
			}
			#spinner > div {
				width: 100px;
				height: 50px;
				position: absolute;
				top: 0;
				bottom: 0;
				right: 0;
				left: 0;
				margin: auto;
				text-align: center;
			}
			#spinner .ball {
				width: 20px;
				height: 20px;
				background-color: #fff;
				border-radius: 50%;
				display: inline-block;
				-webkit-animation: motion 3s cubic-bezier(0.77, 0, 0.175, 1) infinite;
					  animation: motion 3s cubic-bezier(0.77, 0, 0.175, 1) infinite;
			}
			#spinner p {
				color: #fff;
				margin-top: 5px;
				letter-spacing: 3px;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
				font-size: 16px;
				white-space: nowrap;
			}

			.play {
				position: absolute;
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
				font-size: 16px;
				z-index: 2000;
			}

			@-webkit-keyframes motion {
			  0% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			  25% {
				-webkit-transform: translateX(-50px) scale(0.3);
						transform: translateX(-50px) scale(0.3);
			  }
			  50% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			  75% {
				-webkit-transform: translateX(50px) scale(0.3);
						transform: translateX(50px) scale(0.3);
			  }
			  100% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			}

			@keyframes motion {
			  0% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			  25% {
				-webkit-transform: translateX(-50px) scale(0.3);
						transform: translateX(-50px) scale(0.3);
			  }
			  50% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			  75% {
				-webkit-transform: translateX(50px) scale(0.3);
						transform: translateX(50px) scale(0.3);
			  }
			  100% {
				-webkit-transform: translateX(0) scale(1);
						transform: translateX(0) scale(1);
			  }
			}
		</style>
	</head>
	<body oncontextmenu="return false">
		<div id="spinner"><div><div class="ball"></div><p>LOADING</p></div></div>
		<div id="cover"></div>
<?php
	if ( $is_mobile_os ) {
?>
		<canvas></canvas>
		<div class="play" style="display: none;">Tap anywhere to start.</div>
		<script type="text/javascript" src="assets/js/soundcloud-audio.js"></script>
		<script type="text/javascript" src="assets/js/web-audio-player-bundle.js"></script>
		<script type="text/javascript">
			var createApp = require('canvas-loop');
			var createAnalyser = require('web-audio-analyser');
			var createAudioContext = require('ios-safe-audio-context');
			var detectAutoplay = require('detect-audio-autoplay');
			var detectMediaSource = require('detect-media-element-source');
			var average = require('analyser-frequency-average');
			var tapEvent = require('tap-event');
			var audioPlayer = require("web-audio-player");

			// get our canvas element & 2D context
			var canvas = document.querySelector('canvas');
			var ctx = canvas.getContext('2d');

			// provide some info to the user
			var loading = document.querySelector('#spinner');
			var loadingText = document.querySelector('#spinner p');
			var clickToPlay = document.querySelector('.play');

			// full-screen and retina scaled app
			var app = createApp(canvas, {
				scale: window.devicePixelRatio
			});

			var elapsedTime = 0;

			// some devices need a "Click to Play"
			detectAutoplay(function (autoplay) {
				if (autoplay) {
					canplay();
				} else {
					clickToPlay.style.display = 'block';
					loading.style.display = 'none';

					// On iOS, it has to be a tap event and not a drag + touchend...
					var play = function (ev) {
						window.removeEventListener('touchstart', onTap);
						ev.preventDefault();
						loading.style.display = 'block';
						clickToPlay.style.display = 'none';
						canplay()
					};
					var onTap = tapEvent(play);
					if ('ontouchstart' in document.documentElement)
						window.addEventListener('touchstart', onTap);
					else
						window.addEventListener('click', play);
				}
			})

			function canplay () {
				// Create an iOS-safe AudioContext which fixes
				// potential sampleRate bugs with playback
				// (The hack needs to be called on touchend for iOS!)
				var audioContext = createAudioContext();

				// Detect whether createMediaElementSource() works
				// as expected. You can also use userAgent sniffing here.
				detectMediaSource(function (supportsMediaElement) {
					// No media element support -> we should buffer
					var shouldBuffer = !supportsMediaElement
					start(audioContext, shouldBuffer)
				}, audioContext);
			}

			var audioUtil, player, analyser;

			function start (audioContext, shouldBuffer) {
<?php
	if ( $is_soundcloud ) {
?>
				var scPlayer = new SoundCloudAudio('LOaVmgmQ1Nhoe8OtdKBPeTkwvL5jYXkG');

				scPlayer.resolve('<?php echo $audio_url; ?>', function (track) {
					// Create a looping audio player with our audio context.
					// On mobile, we use the "buffer" mode to support AudioAnalyser.
					player = audioPlayer(track.stream_url, {
						context: audioContext,
						buffer: true,
						loop: false,
						volume: 1
					});

					// Set up our AnalyserNode utility
					// Make sure to use the same AudioContext as our player!
					audioUtil = createAnalyser(player.node, player.context, {
						stereo: false
					});

					// The actual AnalyserNode
					analyser = audioUtil.analyser;

					// This is triggered on mobile, when decodeAudioData begins.
					player.once('decoding', function (amount) {
						loadingText.innerText = 'Decoding...';
					});

					// Only gets called when loop: false
					player.on('end', function () {
						elapsedTime = 0;
						if( parent.jQuery && parent.jQuery.wgextraLightbox )
							parent.jQuery.wgextraLightbox.next.call(parent.jQuery.wgextraLightbox.instance);
					});

					// If there was an error loading the audio
					player.on('error', function (err) {
						loadingText.innerText = 'Error loading audio.';
					});

					// This is called with 'canplay' on desktop, and after
					// decodeAudioData on mobile.
					player.on('load', function () {
						loading.style.display = 'none';

						// start audio node
						player.play();

						// start the render loop
						app.on('tick', render);
						app.start();
					});
				});
<?php
	} else {
?>
				// List of sources, usually good to provide
				// a back up in case MP3 isn't supported.
				var sources = '<?php echo $audio_url; ?>';

				// Create a looping audio player with our audio context.
				// On mobile, we use the "buffer" mode to support AudioAnalyser.
				var player = audioPlayer(sources, {
					context: audioContext,
					buffer: true,
					loop: false,
					volume: 1
				});

				// Set up our AnalyserNode utility
				// Make sure to use the same AudioContext as our player!
				audioUtil = createAnalyser(player.node, player.context, {
					stereo: false
				});

				// The actual AnalyserNode
				analyser = audioUtil.analyser;

				// This is triggered on mobile, when decodeAudioData begins.
				player.once('decoding', function (amount) {
					loadingText.innerText = 'Decoding...';
				});

				// Only gets called when loop: false
				player.on('end', function () {
					elapsedTime = 0;
					if( parent.jQuery && parent.jQuery.wgextraLightbox )
						parent.jQuery.wgextraLightbox.next.call(parent.jQuery.wgextraLightbox.instance);
				});

				// If there was an error loading the audio
				player.on('error', function (err) {
					loadingText.innerText = 'Error loading audio.';
				});

				// This is called with 'canplay' on desktop, and after
				// decodeAudioData on mobile.
				player.on('load', function () {
					loading.style.display = 'none';

					// start audio node
					player.play();

					// start the render loop
					app.on('tick', render);
					app.start();
				});
<?php
	}
?>

				// Play/pause on tap
				var click = function () {
					if (player.playing) {
						player.pause();
					} else {
						player.play();
					}

					if (player.playing) {
						clickToPlay.style.display = 'none';
					} else {
						clickToPlay.textContent = 'Paused';
						clickToPlay.style.display = 'block';
					}
				};
				var onTap = tapEvent(click);
				if ('ontouchstart' in document.documentElement)
					window.addEventListener('touchstart', onTap);
				else
					window.addEventListener('click', click);

				function render (currentTimeStamp) {
					var width = window.innerWidth;
					var height = window.innerHeight;

					if (player.playing)
						elapsedTime += currentTimeStamp / 1000;

					// retina scaling
					ctx.save();
					ctx.scale(app.scale, app.scale);
					ctx.clearRect(0, 0, width, height);

					// grab our byte frequency data for this frame
					var freqs = audioUtil.frequencies();

					// find an average signal between two Hz ranges
					var minHz = 40;
					var maxHz = 512;
					var avg = average(analyser, freqs, minHz, maxHz);

					// draw a circle
					ctx.beginPath();
					var radius = Math.min(width, height) / 3 * avg;
					ctx.arc(width / 2, height / 2, radius, 0, Math.PI * 2);
					ctx.fillStyle = "rgba(181, 191, 212, 0.7)";
					ctx.fill();

					ctx.fillStyle = "#ffffff";
					ctx.font = "100 45px 'Roboto Mono'";
					ctx.textAlign = "center";
					ctx.textBaseline = "middle";
					ctx.fillText(generateTime(player.duration - elapsedTime), width / 2, height - 50);

					ctx.restore();
				}
			}

			function generateTime(seconds) {
				var minutes = Math.floor(seconds / 60);
				var seconds = Math.floor(seconds % 60);
				
				return ((minutes < 10)?("0" + minutes):(minutes)) + ":" + ((seconds < 10)?("0" + seconds):(seconds));
			}
		</script>
<?php
	} else {
?>
		<div id="sprite-plyr" hidden=""><!--?xml version="1.0" encoding="UTF-8"?--><svg xmlns="http://www.w3.org/2000/svg"><symbol id="plyr-captions-off" viewBox="0 0 18 18"><path fill-opacity="0.5" fill-rule="evenodd" d="M 1 1 c -0.6 0 -1 0.4 -1 1 v 11 c 0 0.6 0.4 1 1 1 h 4.6 l 2.7 2.7 c 0.2 0.2 0.4 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 l 2.7 -2.7 H 17 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 H 1 Z m 4.52 10.15 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 C 8.47 4.96 7.46 3.76 5.5 3.76 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z m 7.57 0 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 c -0.28 -1.15 -1.29 -2.35 -3.25 -2.35 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z" /></symbol><symbol id="plyr-captions-on" viewBox="0 0 18 18"><path fill-rule="evenodd" d="M 1 1 c -0.6 0 -1 0.4 -1 1 v 11 c 0 0.6 0.4 1 1 1 h 4.6 l 2.7 2.7 c 0.2 0.2 0.4 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 l 2.7 -2.7 H 17 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 H 1 Z m 4.52 10.15 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 C 8.47 4.96 7.46 3.76 5.5 3.76 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z m 7.57 0 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 c -0.28 -1.15 -1.29 -2.35 -3.25 -2.35 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z" /></symbol><symbol id="plyr-enter-fullscreen" viewBox="0 0 18 18"><path d="M 10 3 h 3.6 l -4 4 L 11 8.4 l 4 -4 V 8 h 2 V 1 h -7 Z M 7 9.6 l -4 4 V 10 H 1 v 7 h 7 v -2 H 4.4 l 4 -4 Z" /></symbol><symbol id="plyr-exit-fullscreen" viewBox="0 0 18 18"><path d="M 1 12 h 3.6 l -4 4 L 2 17.4 l 4 -4 V 17 h 2 v -7 H 1 Z M 16 0.6 l -4 4 V 1 h -2 v 7 h 7 V 6 h -3.6 l 4 -4 Z" /></symbol><symbol id="plyr-fast-forward" viewBox="0 0 18 18"><path d="M 7.875 7.171 L 0 1 v 16 l 7.875 -6.171 V 17 L 18 9 L 7.875 1 Z" /></symbol><symbol id="plyr-muted" viewBox="0 0 18 18"><path d="M 12.4 12.5 l 2.1 -2.1 l 2.1 2.1 l 1.4 -1.4 L 15.9 9 L 18 6.9 l -1.4 -1.4 l -2.1 2.1 l -2.1 -2.1 L 11 6.9 L 13.1 9 L 11 11.1 Z M 3.786 6.008 H 0.714 C 0.286 6.008 0 6.31 0 6.76 v 4.512 c 0 0.452 0.286 0.752 0.714 0.752 h 3.072 l 4.071 3.858 c 0.5 0.3 1.143 0 1.143 -0.602 V 2.752 c 0 -0.601 -0.643 -0.977 -1.143 -0.601 L 3.786 6.008 Z" /></symbol><symbol id="plyr-pause" viewBox="0 0 18 18"><path d="M 6 1 H 3 c -0.6 0 -1 0.4 -1 1 v 14 c 0 0.6 0.4 1 1 1 h 3 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 Z M 12 1 c -0.6 0 -1 0.4 -1 1 v 14 c 0 0.6 0.4 1 1 1 h 3 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 h -3 Z" /></symbol><symbol id="plyr-play" viewBox="0 0 18 18"><path d="M 15.562 8.1 L 3.87 0.225 C 3.052 -0.337 2 0.225 2 1.125 v 15.75 c 0 0.9 1.052 1.462 1.87 0.9 L 15.563 9.9 c 0.584 -0.45 0.584 -1.35 0 -1.8 Z" /></symbol><symbol id="plyr-restart" viewBox="0 0 18 18"><path d="M 9.7 1.2 l 0.7 6.4 l 2.1 -2.1 c 1.9 1.9 1.9 5.1 0 7 c -0.9 1 -2.2 1.5 -3.5 1.5 c -1.3 0 -2.6 -0.5 -3.5 -1.5 c -1.9 -1.9 -1.9 -5.1 0 -7 c 0.6 -0.6 1.4 -1.1 2.3 -1.3 l -0.6 -1.9 C 6 2.6 4.9 3.2 4 4.1 C 1.3 6.8 1.3 11.2 4 14 c 1.3 1.3 3.1 2 4.9 2 c 1.9 0 3.6 -0.7 4.9 -2 c 2.7 -2.7 2.7 -7.1 0 -9.9 L 16 1.9 l -6.3 -0.7 Z" /></symbol><symbol id="plyr-rewind" viewBox="0 0 18 18"><path d="M 10.125 1 L 0 9 l 10.125 8 v -6.171 L 18 17 V 1 l -7.875 6.171 Z" /></symbol><symbol id="plyr-volume" viewBox="0 0 18 18"><path d="M 15.6 3.3 c -0.4 -0.4 -1 -0.4 -1.4 0 c -0.4 0.4 -0.4 1 0 1.4 C 15.4 5.9 16 7.4 16 9 c 0 1.6 -0.6 3.1 -1.8 4.3 c -0.4 0.4 -0.4 1 0 1.4 c 0.2 0.2 0.5 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 C 17.1 13.2 18 11.2 18 9 s -0.9 -4.2 -2.4 -5.7 Z" /><path d="M 11.282 5.282 a 0.909 0.909 0 0 0 0 1.316 c 0.735 0.735 0.995 1.458 0.995 2.402 c 0 0.936 -0.425 1.917 -0.995 2.487 a 0.909 0.909 0 0 0 0 1.316 c 0.145 0.145 0.636 0.262 1.018 0.156 a 0.725 0.725 0 0 0 0.298 -0.156 C 13.773 11.733 14.13 10.16 14.13 9 c 0 -0.17 -0.002 -0.34 -0.011 -0.51 c -0.053 -0.992 -0.319 -2.005 -1.522 -3.208 a 0.909 0.909 0 0 0 -1.316 0 Z M 3.786 6.008 H 0.714 C 0.286 6.008 0 6.31 0 6.76 v 4.512 c 0 0.452 0.286 0.752 0.714 0.752 h 3.072 l 4.071 3.858 c 0.5 0.3 1.143 0 1.143 -0.602 V 2.752 c 0 -0.601 -0.643 -0.977 -1.143 -0.601 L 3.786 6.008 Z" /></symbol></svg></div>
		<div id="cover"></div>
		<canvas id="canvas"></canvas>
<?php
	if ( $is_soundcloud ) {
?>
		<audio id="player" autobuffer controls crossorigin></audio>
<?
	} else {
?>
		<audio id="player" autobuffer controls crossorigin>
			<source src="<?php echo $audio_url; ?>">
		</audio>
<?
	}
?>
		<script type="text/javascript" src="assets/js/visualizer.js"></script>
		<script type="text/javascript" src="assets/js/plyr.js"></script>
<?php
	if ( $is_soundcloud ) {
?>
		<script type="text/javascript" src="assets/js/soundcloud-audio.js"></script>
		<script type="text/javascript">
			var paused = true;
			var scPlayer = new SoundCloudAudio('LOaVmgmQ1Nhoe8OtdKBPeTkwvL5jYXkG');

			var plyrInstance = plyr.setup({
				loadSprite: false
			});

			plyrInstance[0].on('ready', function(){
				initializeVisualizer();
				playVisualizer();
				pauseVisualizer();
			});
			plyrInstance[0].on('playing', function () {
				if(paused)
					playVisualizer();

				paused = false;
			});
			plyrInstance[0].on('pause waiting', function () {
				pauseVisualizer();
				paused = true;
			});

			scPlayer.resolve('<?php echo $audio_url; ?>', function (track) {
				spinner.style.display = 'none';

				plyrInstance[0].source({
					type:       'audio',
					title:      'Example title',
					sources: [{
						src:      track.stream_url,
						type:     'audio/mp3'
					}]
				});
				plyrInstance[0].play();
			});
		</script>
<?
	} else {
?>
		<script type="text/javascript">
			var paused = true;
			var plyrInstance = plyr.setup({
				loadSprite: false
			});

			plyrInstance[0].on('ready', function(){
				initializeVisualizer();
				playVisualizer();
				pauseVisualizer();
				spinner.style.display = 'none';
				plyrInstance[0].play();
			});
			plyrInstance[0].on('playing', function () {
				if(paused)
					playVisualizer();

				paused = false;
			});
			plyrInstance[0].on('pause waiting', function () {
				pauseVisualizer();
				paused = true;
			});
		</script>
<?
	}
?>
		<script type="text/javascript">
			if( parent.jQuery && parent.jQuery.wgextraLightbox )
				plyrInstance[0].on('ended', function () {
					parent.jQuery.wgextraLightbox.next.call(parent.jQuery.wgextraLightbox.instance);
				});
			canvas.addEventListener('click', function () {
				plyrInstance[0].togglePlay();
			});
		</script>

<?php
	}
?>
	</body>
</html>