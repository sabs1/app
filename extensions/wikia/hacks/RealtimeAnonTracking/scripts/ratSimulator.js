require([
	'wikia.window',
	'wikia.document',
	'wikia.nirvana'
], function (window, document, nirvana) {
	'use strict';

	var listeningInterval = 5000,
		executeInterval = 250,
		JSON = window.JSON,
		ss = window.sessionStorage,
		lastUrl = window.location.href,
		pointer = document.createElement('div');

	$(document).ready(init);

	function init() {
		// we want to show the tracking only to staff
		if (window.wgUserGroups.indexOf('staff') === -1) {
			return;
		}

		console.debug('RAT simulation system loaded!');

		ss.setItem('ratQueue', JSON.stringify({}));
		pointer.className = 'rat-mouse-pointer';
		pointer.id = 'ratMousePointer';
		document.body.appendChild(pointer);

		setInterval(getReport, listeningInterval);
		setInterval(executeItemsFromQueue, executeInterval);
	}

	function getReport() {
		nirvana.getJson('RealtimeAnonTrackingController', 'index').done(
			function (data) {
				putReportIntoQueue(data);
			}
		);
	}

	function putReportIntoQueue(data) {
		var timestamp = Date.now(),
			ratQueue = JSON.parse(ss.getItem('ratQueue')),
			ratReport = data.report,
			attrname;

		if (timestamp > getLatestTimestamp(ratReport)) {
			console.debug('Putting new data into the RAT queue:');
			console.debug(ratReport);
			for (attrname in ratReport) {
				if (ratReport.hasOwnProperty(attrname)) {
					ratQueue[attrname] = ratReport[attrname];
				}
			}
		}

		ss.setItem('ratQueue', JSON.stringify(ratQueue));
	}

	function getTimestapms(report) {
		return Object.keys(report).map(
			function (key) {
				return parseInt(key);
			});
	}

	function getEarliestTimestamp(report) {
		var timestamps = getTimestapms(report);
		return Math.min.apply(null, timestamps);
	}

	function getLatestTimestamp(report) {
		var timestamps = getTimestapms(report);
		return Math.max.apply(null, timestamps);
	}

	function executeItemsFromQueue() {
		var ratQueue = JSON.parse(ss.getItem('ratQueue')),
			ratQueueSortedKeys = Object.keys(ratQueue).sort(),
			executeStartTimestamp = Date.now(),
			i = 0;

//		console.debug('Cheking the item simulation condition:', Date.now() - executeStartTimestamp < executeInterval);

		for (i; Date.now() - executeStartTimestamp < executeInterval; i++) {
			if (!ratQueue[ratQueueSortedKeys[i]]) {
				break;
			}
//			console.debug('Executing item:');
//			console.debug(ratQueue[ratQueueSortedKeys[i]]);
			simulateAction(ratQueue[ratQueueSortedKeys[i]]);
			delete ratQueue[ratQueueSortedKeys[i]];
		}

		ss.setItem('ratQueue', JSON.stringify(ratQueue));
	}

	function simulateAction(item) {
		pointer.style.left = item.mx + item.sx + 'px';
		pointer.style.top = item.my + item.sy + 'px';
		window.scrollTo(item.sx, item.sy);
//		resizeWindow(item.w, item.h);

		if (item.url && item.url !== lastUrl) {
			window.location.href = item.url;
		}
	}

	function resizeWindow(width, height) {
		document.body.style.width = width + 'px';
	}
});
