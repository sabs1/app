<?php
/**
 * AdEngine II Hooks
 */
class AdEngine2Hooks {
	/**
	 * Handle URL parameters and set proper global variables early enough
	 *
	 * @author Sergey Naumov
	 */
	static public function onAfterInitialize($title, $article, $output, $user, WebRequest $request, $wiki) {

		// TODO: review top and bottom vars (important for adsinhead)

		global $wgAdDriverForceDirectGptAd, $wgAdDriverForceLiftiumAd, $wgAdDriverUseRemnantGpt,
			   $wgLiftiumOnLoad, $wgNoExternals, $wgAdVideoTargeting, $wgEnableKruxTargeting,
			   $wgAdEngineDisableLateQueue, $wgLoadAdsInHead, $wgLoadLateAdsAfterPageLoad,
			   $wgAnalyticsProviderPageFairAlternativeMarkup;

		$wgNoExternals = $request->getBool( 'noexternals', $wgNoExternals );
		$wgLiftiumOnLoad = $request->getBool( 'liftiumonload', (bool)$wgLiftiumOnLoad );
		$wgAdVideoTargeting = $request->getBool( 'videotargetting', (bool)$wgAdVideoTargeting );

		$wgAdDriverUseRemnantGpt = $request->getBool( 'gptremnant', $wgAdDriverUseRemnantGpt );

		$wgAdEngineDisableLateQueue = $request->getBool( 'noremnant', $wgAdEngineDisableLateQueue );

		$wgAdDriverForceDirectGptAd = $request->getBool( 'forcedirectgpt', $wgAdDriverForceDirectGptAd );
		$wgAdDriverForceLiftiumAd = $request->getBool( 'forceliftium', $wgAdDriverForceLiftiumAd );

		$wgLoadAdsInHead = $request->getBool( 'adsinhead', $wgLoadAdsInHead );
		$wgLoadLateAdsAfterPageLoad = $request->getBool( 'lateadsafterload', $wgLoadLateAdsAfterPageLoad );

		$wgEnableKruxTargeting = !$wgAdEngineDisableLateQueue && !$wgNoExternals && $wgEnableKruxTargeting;

		$wgAnalyticsProviderPageFairAlternativeMarkup = $request->getBool( 'pagefairalternative', $wgAnalyticsProviderPageFairAlternativeMarkup );

		return true;
	}

	/**
	 * Register "instant" global JS
	 *
	 * @param array $vars
	 *
	 * @return bool
	 */
	static public function onInstantGlobalsGetVariables(array &$vars)
	{
		// DR
		$vars[] = 'wgSitewideDisableLiftium';
		$vars[] = 'wgSitewideDisableSevenOneMedia';
		$vars[] = 'wgSitewideDisableRubiconRTP';

		$vars[] = 'wgHighValueCountries';
		$vars[] = 'wgAmazonDirectTargetedBuyCountries';

		return true;
	}

	/**
	 * Register ad-related vars on top
	 *
	 * @param array $vars
	 * @param array $scripts
	 *
	 * @return bool
	 */
	static public function onWikiaSkinTopScripts(&$vars, &$scripts) {
		foreach (AdEngine2Service::getTopJsVariables() as $varName => $varValue) {
			$vars[$varName] = $varValue;
		}
		return true;
	}

	/**
	 * Modify assets appended to the bottom of the page
	 *
	 * @param array $jsAssets
	 *
	 * @return bool
	 */
	static public function onOasisSkinAssetGroups(&$jsAssets) {

		global $wgAdDriverUseBottomLeaderboard, $wgAdDriverUseTopInContentBoxad;

		$coreGroupIndex = array_search(AdEngine2Service::ASSET_GROUP_CORE, $jsAssets);
		if ($coreGroupIndex === false) {
			// Do nothing. oasis_shared_core_js must be present for ads to work
			return true;
		}

		if (AdEngine2Service::areAdsInHead()) {
			if (AdEngine2Service::shouldLoadLateQueue()) {
				array_splice($jsAssets, $coreGroupIndex + 1, 0, AdEngine2Service::ASSET_GROUP_ADENGINE_LATE);
			}
			// The ASSET_GROUP_ADENGINE_LATE package was added to the blocking group
		} else {
			array_splice($jsAssets, $coreGroupIndex + 1, 0, AdEngine2Service::ASSET_GROUP_ADENGINE);
			if ($wgAdDriverUseTopInContentBoxad) {
				array_splice($jsAssets, $coreGroupIndex + 2, 0, AdEngine2Service::ASSET_GROUP_TOP_INCONTENT_JS);
			}
			if (AdEngine2Service::shouldLoadLateQueue()) {
				array_splice($jsAssets, $coreGroupIndex + 2, 0, AdEngine2Service::ASSET_GROUP_ADENGINE_LATE);
			}
		}

		if (AdEngine2Service::shouldLoadLiftium()) {
			$jsAssets[] = AdEngine2Service::ASSET_GROUP_LIFTIUM;
		}

		if ($wgAdDriverUseBottomLeaderboard === true) {
			$jsAssets[] = 'adengine2_bottom_leaderboard_js';
		}

		$jsAssets[] = 'adengine2_interactive_maps_js';

		return true;
	}

	/**
	 * Modify assets appended to the top of the page
	 *
	 * @param array $jsAssets
	 *
	 * @return bool
	 */
	static public function onOasisSkinAssetGroupsBlocking(&$jsAssets) {

		global $wgAdDriverUseTopInContentBoxad;

		if (AdEngine2Service::areAdsInHead()) {
			// Add ad asset to JavaScripts loaded on top (in <head>)
			$jsAssets[] = AdEngine2Service::ASSET_GROUP_ADENGINE;

			if ($wgAdDriverUseTopInContentBoxad === true) {
				array_unshift($jsAssets, AdEngine2Service::ASSET_GROUP_TOP_INCONTENT_JS);
			}
		}
		return true;
	}

	/**
	 * Add the resource loader modules needed for AdEngine to work.
	 *
	 * Note the dependency resolver does not work at this time, so we need to add every
	 * module needed including their dependencies.
	 *
	 * @param $scriptModules
	 * @param $skin
	 * @return bool
	 */
	static public function onWikiaSkinTopModules(&$scriptModules, $skin) {
		if (AdEngine2Service::areAdsInHead() || AnalyticsProviderAmazonDirectTargetedBuy::isEnabled()) {
			$scriptModules[] = 'wikia.instantGlobals';
			$scriptModules[] = 'wikia.cookies';
			$scriptModules[] = 'wikia.geo';
			$scriptModules[] = 'wikia.window';
		}
		if (AdEngine2Service::areAdsInHead()) {
			$scriptModules[] = 'wikia.document';
			$scriptModules[] = 'wikia.abTest';
			$scriptModules[] = 'wikia.cache';
			$scriptModules[] = 'wikia.localStorage';
			$scriptModules[] = 'wikia.location';
			$scriptModules[] = 'wikia.log';
			$scriptModules[] = 'wikia.querystring';
			$scriptModules[] = 'wikia.tracker.stub';
		}
		return true;
	}

	public static function onWikiaMobileAssetsPackages(&$jsBodyPackages, &$jsExtensionPackages, &$scssPackages) {
		global $wgAdDriverUseEbay;

		if ($wgAdDriverUseEbay) {
			$scssPackages[] = 'adengine2_ebay_scss_wikiamobile';
		}

		return true;
	}
}
