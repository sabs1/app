require([
	'wikia.window',
	'wikia.document',
	'wikia.nirvana',
	'jquery'
], function (window, document, nirvana, $) {
	'use strict';

	var sniffingInterval = 1000,
		reportingInterval = 10000,
		mouseX = 0,
		mouseY = 0,
		JSON = window.JSON,
		ss = window.sessionStorage,
		lastUrl;

	$(document).ready(init);

	function init() {
		// we want to track only anons
		if (window.wgUserName) {
			return;
		}

		console.debug('RAT reporting for duty!');

		respawnRat();
		document.addEventListener('mousemove', sniffMousePosition);
		sniff();
	}

	function respawnRat() {
		ss.rat = JSON.stringify({});
	}

	function sniffMousePosition(event) {
		mouseX = event.clientX;
		mouseY = event.clientY;
	}

	function gatherReport() {
		var timestamp = Date.now(),
			rat = JSON.parse(ss.getItem('rat'));

		rat[timestamp] = {
			mx: mouseX,
			my: mouseY,
			sx: window.scrollX,
			sy: window.scrollY,
			w: window.innerWidth,
			h: window.innerHeight
		};

		if (lastUrl !== window.location.href) {
			rat[timestamp].url = window.location.href;
			lastUrl = window.location.href;
		}

		ss.setItem('rat', JSON.stringify(rat));
	}

	function sendReport() {
		// send the data object to the server and purge the local/session storage
		console.debug(JSON.parse(ss.rat));
		nirvana.getJson('RealtimeAnonTrackingController', 'saveReport', {
			report: ss.rat
		});
		respawnRat();
	}

	function sniff() {
		setInterval(gatherReport, sniffingInterval);
		setInterval(sendReport, reportingInterval);
	}
});
