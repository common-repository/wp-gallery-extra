<?php
if ( !isset( $_REQUEST['video'] ) )
	die("Your request is not valid.");

$video_url = $_REQUEST['video'];
?><!DOCTYPE html>
<html>
	<head>
		<title>WordPress Gallery Extra: Video Player</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="Demo project">
		<meta name="viewport" content="shrink-to-fit=0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="stylesheet" href="assets/css/plyr.css">
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

			/* Video Player */
			.plyr {
				height: 100vh;
			}
			.plyr__video-wrapper {
				height: 100%;
			}
			.plyr__video-wrapper {
				height: 100%;
			}
			.plyr video {
				height: 100%;
			}
		</style>
	</head>
	<body oncontextmenu="return false">
		<div id="sprite-plyr" hidden=""><!--?xml version="1.0" encoding="UTF-8"?--><svg xmlns="http://www.w3.org/2000/svg"><symbol id="plyr-captions-off" viewBox="0 0 18 18"><path fill-opacity="0.5" fill-rule="evenodd" d="M 1 1 c -0.6 0 -1 0.4 -1 1 v 11 c 0 0.6 0.4 1 1 1 h 4.6 l 2.7 2.7 c 0.2 0.2 0.4 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 l 2.7 -2.7 H 17 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 H 1 Z m 4.52 10.15 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 C 8.47 4.96 7.46 3.76 5.5 3.76 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z m 7.57 0 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 c -0.28 -1.15 -1.29 -2.35 -3.25 -2.35 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z" /></symbol><symbol id="plyr-captions-on" viewBox="0 0 18 18"><path fill-rule="evenodd" d="M 1 1 c -0.6 0 -1 0.4 -1 1 v 11 c 0 0.6 0.4 1 1 1 h 4.6 l 2.7 2.7 c 0.2 0.2 0.4 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 l 2.7 -2.7 H 17 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 H 1 Z m 4.52 10.15 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 C 8.47 4.96 7.46 3.76 5.5 3.76 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z m 7.57 0 c 1.99 0 3.01 -1.32 3.28 -2.41 l -1.29 -0.39 c -0.19 0.66 -0.78 1.45 -1.99 1.45 c -1.14 0 -2.2 -0.83 -2.2 -2.34 c 0 -1.61 1.12 -2.37 2.18 -2.37 c 1.23 0 1.78 0.75 1.95 1.43 l 1.3 -0.41 c -0.28 -1.15 -1.29 -2.35 -3.25 -2.35 c -1.9 0 -3.61 1.44 -3.61 3.7 c 0 2.26 1.65 3.69 3.63 3.69 Z" /></symbol><symbol id="plyr-enter-fullscreen" viewBox="0 0 18 18"><path d="M 10 3 h 3.6 l -4 4 L 11 8.4 l 4 -4 V 8 h 2 V 1 h -7 Z M 7 9.6 l -4 4 V 10 H 1 v 7 h 7 v -2 H 4.4 l 4 -4 Z" /></symbol><symbol id="plyr-exit-fullscreen" viewBox="0 0 18 18"><path d="M 1 12 h 3.6 l -4 4 L 2 17.4 l 4 -4 V 17 h 2 v -7 H 1 Z M 16 0.6 l -4 4 V 1 h -2 v 7 h 7 V 6 h -3.6 l 4 -4 Z" /></symbol><symbol id="plyr-fast-forward" viewBox="0 0 18 18"><path d="M 7.875 7.171 L 0 1 v 16 l 7.875 -6.171 V 17 L 18 9 L 7.875 1 Z" /></symbol><symbol id="plyr-muted" viewBox="0 0 18 18"><path d="M 12.4 12.5 l 2.1 -2.1 l 2.1 2.1 l 1.4 -1.4 L 15.9 9 L 18 6.9 l -1.4 -1.4 l -2.1 2.1 l -2.1 -2.1 L 11 6.9 L 13.1 9 L 11 11.1 Z M 3.786 6.008 H 0.714 C 0.286 6.008 0 6.31 0 6.76 v 4.512 c 0 0.452 0.286 0.752 0.714 0.752 h 3.072 l 4.071 3.858 c 0.5 0.3 1.143 0 1.143 -0.602 V 2.752 c 0 -0.601 -0.643 -0.977 -1.143 -0.601 L 3.786 6.008 Z" /></symbol><symbol id="plyr-pause" viewBox="0 0 18 18"><path d="M 6 1 H 3 c -0.6 0 -1 0.4 -1 1 v 14 c 0 0.6 0.4 1 1 1 h 3 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 Z M 12 1 c -0.6 0 -1 0.4 -1 1 v 14 c 0 0.6 0.4 1 1 1 h 3 c 0.6 0 1 -0.4 1 -1 V 2 c 0 -0.6 -0.4 -1 -1 -1 h -3 Z" /></symbol><symbol id="plyr-play" viewBox="0 0 18 18"><path d="M 15.562 8.1 L 3.87 0.225 C 3.052 -0.337 2 0.225 2 1.125 v 15.75 c 0 0.9 1.052 1.462 1.87 0.9 L 15.563 9.9 c 0.584 -0.45 0.584 -1.35 0 -1.8 Z" /></symbol><symbol id="plyr-restart" viewBox="0 0 18 18"><path d="M 9.7 1.2 l 0.7 6.4 l 2.1 -2.1 c 1.9 1.9 1.9 5.1 0 7 c -0.9 1 -2.2 1.5 -3.5 1.5 c -1.3 0 -2.6 -0.5 -3.5 -1.5 c -1.9 -1.9 -1.9 -5.1 0 -7 c 0.6 -0.6 1.4 -1.1 2.3 -1.3 l -0.6 -1.9 C 6 2.6 4.9 3.2 4 4.1 C 1.3 6.8 1.3 11.2 4 14 c 1.3 1.3 3.1 2 4.9 2 c 1.9 0 3.6 -0.7 4.9 -2 c 2.7 -2.7 2.7 -7.1 0 -9.9 L 16 1.9 l -6.3 -0.7 Z" /></symbol><symbol id="plyr-rewind" viewBox="0 0 18 18"><path d="M 10.125 1 L 0 9 l 10.125 8 v -6.171 L 18 17 V 1 l -7.875 6.171 Z" /></symbol><symbol id="plyr-volume" viewBox="0 0 18 18"><path d="M 15.6 3.3 c -0.4 -0.4 -1 -0.4 -1.4 0 c -0.4 0.4 -0.4 1 0 1.4 C 15.4 5.9 16 7.4 16 9 c 0 1.6 -0.6 3.1 -1.8 4.3 c -0.4 0.4 -0.4 1 0 1.4 c 0.2 0.2 0.5 0.3 0.7 0.3 c 0.3 0 0.5 -0.1 0.7 -0.3 C 17.1 13.2 18 11.2 18 9 s -0.9 -4.2 -2.4 -5.7 Z" /><path d="M 11.282 5.282 a 0.909 0.909 0 0 0 0 1.316 c 0.735 0.735 0.995 1.458 0.995 2.402 c 0 0.936 -0.425 1.917 -0.995 2.487 a 0.909 0.909 0 0 0 0 1.316 c 0.145 0.145 0.636 0.262 1.018 0.156 a 0.725 0.725 0 0 0 0.298 -0.156 C 13.773 11.733 14.13 10.16 14.13 9 c 0 -0.17 -0.002 -0.34 -0.011 -0.51 c -0.053 -0.992 -0.319 -2.005 -1.522 -3.208 a 0.909 0.909 0 0 0 -1.316 0 Z M 3.786 6.008 H 0.714 C 0.286 6.008 0 6.31 0 6.76 v 4.512 c 0 0.452 0.286 0.752 0.714 0.752 h 3.072 l 4.071 3.858 c 0.5 0.3 1.143 0 1.143 -0.602 V 2.752 c 0 -0.601 -0.643 -0.977 -1.143 -0.601 L 3.786 6.008 Z" /></symbol></svg></div>
		<video autobuffer controls crossorigin>
			<source src="<?php echo $video_url; ?>">
			<object data="<?php echo $video_url; ?>" width="100%" height="100%">
				<param name="src" value="<?php echo $video_url; ?>">
				<param name="autoplay" value="true">
				<param name="autoStart" value="1">
			</object>
		</video>
		<script type="text/javascript" src="assets/js/plyr.js"></script>
		<script>
			var plyrInstance = plyr.setup({
				loadSprite: false,
				autoplay: true
			});
			if( parent.jQuery.wgextraLightbox )
				plyrInstance[0].on('ended', function () {
					parent.jQuery.wgextraLightbox.next.call(parent.jQuery.wgextraLightbox.instance);
				});
		</script>
	</body>
</html>