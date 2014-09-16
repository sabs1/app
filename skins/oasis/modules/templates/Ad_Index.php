<?php

if ($wg->EnableAdEngineExt) {
	if (isset($pageFairId) && !$wg->AnalyticsProviderPageFairAlternativeMarkup) {
		echo '<div id="' . htmlspecialchars($pageFairId) . '" class="pagefair-acceptable">';
	}

	echo F::app()->renderView('AdEngine2', 'Ad', [
		'slotName' => $slotName,
		'pageTypes' => $pageTypes,
		'pageFairId' => (isset($pageFairId) && $wg->AnalyticsProviderPageFairAlternativeMarkup) ? $pageFairId : null
	]);

	if (isset($pageFairId) && !$wg->AnalyticsProviderPageFairAlternativeMarkup) {
		echo '</div>';
	}
} else {
	echo '<!-- Ad Engine disabled -->';
}
