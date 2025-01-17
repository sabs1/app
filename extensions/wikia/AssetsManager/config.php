<?php

$config = array();

/******** Shared libraries and assets *******/

$config['oasis_shared_core_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_wikia_js',
		'//resources/wikia/libraries/sloth/sloth.js',
		'//resources/wikia/libraries/mustache/mustache.js',
		'//resources/wikia/modules/browserDetect.js',
		'//resources/mediawiki/mediawiki.Uri.js',
		'#group_banner_notifications_js',
		'#group_ui_repo_api_js',
	),
);

$config['oasis_extensions_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_noads_extensions_js',
		'#group_ui_repo_api_js',
	)
);

$config['tracker_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/wikia/modules/tracker.stub.js',
		'//resources/wikia/modules/tracker.js',
	)
);

$config['liftium_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'wikiamobile'],
	'assets' => array(
		'//extensions/wikia/AdEngine/liftium/Liftium.js',
	)
);

$config['liftium_ads_extra_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'wikiamobile'],
	'assets' => array(
		// TODO: get rid of those:
		'//extensions/wikia/AdEngine/liftium/Wikia.Athena.js',
		'//extensions/wikia/AdEngine/liftium/Wikia.AQ.js',
		'//extensions/wikia/AdEngine/liftium/Wikia.meerkat.js',
		'//extensions/wikia/AdEngine/liftium/Wikia.ve_alternate.js',
		'//extensions/wikia/AdEngine/liftium/AdsInContent.js'
	)
);

$config['adengine2_desktop_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'wikiamobile'],
	'assets' => array(
		// was: early queue
		'//resources/wikia/modules/iframeWriter.js',
		'//resources/wikia/modules/scriptwriter.js',
		'//extensions/wikia/AdEngine/js/AdContext.js',
		'//extensions/wikia/AdEngine/js/AdDecoratorLegacyParamFormat.js',
		'//extensions/wikia/AdEngine/js/AdDecoratorPageDimensions.js',
		'//extensions/wikia/AdEngine/js/AdEngine2.js',
		'//extensions/wikia/AdEngine/js/AdLogicDartSubdomain.js',
		'//extensions/wikia/AdEngine/js/AdLogicHighValueCountry.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageDimensions.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageParams.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageViewCounter.js',
		'//extensions/wikia/AdEngine/js/CustomAdsLoader.js',
		'//extensions/wikia/AdEngine/js/DartUrl.js',
		'//extensions/wikia/AdEngine/js/EventDispatcher.js',
		'//extensions/wikia/AdEngine/js/EvolveSlotConfig.js',
		'//extensions/wikia/AdEngine/js/lookup/services.js',
		'//extensions/wikia/AdEngine/js/MessageListener.js',
		'//extensions/wikia/AdEngine/js/SlotTracker.js',
		'//extensions/wikia/AdEngine/js/SlotTweaker.js',
		'//extensions/wikia/AdEngine/js/WikiaAdHelper.js',
		'//extensions/wikia/AdEngine/js/WikiaDartHelper.js',
		'//extensions/wikia/AdEngine/js/config/desktop.js',
		'//extensions/wikia/AdEngine/js/provider/directGpt.js',
		'//extensions/wikia/AdEngine/js/provider/factoryWikiaGpt.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adDetect.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adElement.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeFilter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeConverter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/googleTag.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/helper.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/sraHelper.js',
		'//extensions/wikia/AdEngine/js/provider/openX.js',
		'//extensions/wikia/AdEngine/js/provider/openX.targeting.js',
		'//extensions/wikia/AdEngine/js/provider/remnantGpt.js',
		'//extensions/wikia/AdEngine/js/provider/turtle.js',
		'//extensions/wikia/AdEngine/js/slot/inContentPlayer.js',
		'//extensions/wikia/AdEngine/js/slot/skyScraper3.js',
		'//extensions/wikia/AdEngine/js/template/floor.js',
		'//extensions/wikia/AdEngine/js/template/modal.js',
		'//extensions/wikia/AdEngine/js/template/skin.js',
		'//resources/wikia/modules/krux.js',

		// was: late queue
		'//extensions/wikia/AdEngine/js/EvolveHelper.js',
		'//extensions/wikia/AdEngine/js/OoyalaTracking.js',
		'//extensions/wikia/AdEngine/js/SevenOneMediaHelper.js',
		'//extensions/wikia/AdEngine/js/WikiaDartVideoHelper.js',
		'//extensions/wikia/AdEngine/js/provider/evolve.js',
		'//extensions/wikia/AdEngine/js/provider/liftium.js',
		'//extensions/wikia/AdEngine/js/provider/monetizationService.js',
		'//extensions/wikia/AdEngine/js/provider/sevenOneMedia.js',

		'//extensions/wikia/AdEngine/js/run/desktop.run.js',
	),
);

$config['spotlights_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// ads
		'//extensions/wikia/Spotlights/js/AdProviderOpenX.js',
		'//extensions/wikia/Spotlights/js/LazyLoadAds.js',
	),
);

$config['adengine2_taboola_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis', 'wikiamobile' ],
	'assets' => array(
		'//extensions/wikia/AdEngine/js/provider/taboola.js',
	),
);

$config['adengine2_interactive_maps_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/AdEngine/js/AdSlotInteractiveMaps.js',
	),
);

$config['adengine2_oasis_in_content_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'oasis',
	'assets' => array(
		'//extensions/wikia/AdEngine/js/slot/inContent.js',
		'//extensions/wikia/AdEngine/js/slot/inContentDesktop.js',
	),
);

$config['adengine2_oasis_exitstitial_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'oasis',
	'assets' => array(
		'//extensions/wikia/AdEngine/js/slot/exitstitial.js',
	),
);

$config['adengine2_tracking_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus', 'wikiamobile'],
	'assets' => array(
		'//extensions/wikia/AdEngine/js/AdTracker.js',
	),
);

$config['adengine2_rubicon_rtp_js'] = array(
	'skin' => ['oasis', 'venus'],
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/AdEngine/js/lookup/rubiconRtp.js',
	),
);

$config['adengine2_amazon_match_js'] = array(
	'skin' => ['oasis', 'venus'],
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/AdEngine/js/lookup/amazonMatch.js',
		'//extensions/wikia/AdEngine/js/lookup/amazonMatchOld.js',
	),
);

$config['oasis_noads_extensions_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_bucky_js',
		'//resources/wikia/libraries/jquery/ellipses.js',
		'//extensions/wikia/CreatePage/js/CreatePage.js',
		'//extensions/wikia/VideoHandlers/js/VideoBootstrap.js',
		'//extensions/wikia/Lightbox/scripts/LightboxLoader.js',
		'#group_imglzy_js',
		'//extensions/wikia/MiniEditor/js/MiniEditor.js',
		// needs to load after MiniEditor
		'#group_articlecomments_js',

		// This needs to load last after all common extensions, please keep this last.
		'//skins/oasis/js/GlobalModal.js',
		'//extensions/wikia/UserLogin/js/UserLogin.js',
		// WikiaBar is enabled sitewide
		'//extensions/wikia/WikiaBar/js/WikiaBar.js',
		// Chat is enabled sitewide
		'//extensions/wikia/Chat2/js/ChatEntryPoint.js',
		'//extensions/wikia/VideoEmbedTool/js/VET_Loader.js',
		// Survey for first time editors
		'//extensions/wikia/EditorSurvey/js/EditorSurvey.js',
		// Image and video thumbnail mustache templates
		'//extensions/wikia/Thumbnails/scripts/templates.mustache.js',
		// handlebars - uncomment this when introducing first client-side rendered handlebars template
		// '//resources/wikia/libraries/handlebars/handlebars.js',
		'//extensions/wikia/JSSnippets/js/JSSnippets.js'
	)
);

/**
 * Scripts that are needed very early in execution and thus are worth blocking for.
 *
 * Keep this group small!
 **/

$config['oasis_blocking'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/wikia/modules/lazyqueue.js',
	)
);

$config['abtesting'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis', 'wikiamobile', 'venus' ],
	'assets' => array(
		'//extensions/wikia/AbTesting/js/AbTest.js',
	)
);

/** jQuery **/
$config['jquery'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#function_AssetsConfig::getJQueryUrl'
	)
);

$config['oasis_jquery'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// polyfills
		'//resources/wikia/polyfills/jquery.wikia.placeholder.js',
		'//resources/wikia/polyfills/array.js',

		// 3rd party plugins
		'//resources/wikia/libraries/jquery/getcss/jquery.getcss.js',
		'//resources/wikia/libraries/jquery/cookies/jquery.cookies.2.1.0.js',
		'//resources/wikia/libraries/jquery/timeago/jquery.timeago.js',
		'//resources/wikia/libraries/jquery/store/jquery.store.js',
		'//resources/wikia/libraries/jquery/throttle-debounce/jquery.throttle-debounce.js',
		'//resources/wikia/libraries/jquery/floating-scrollbar/jquery.floating-scrollbar.js',

		// Wikia plugins
		'//resources/wikia/jquery.wikia.js',
		'//resources/wikia/libraries/jquery/carousel/jquery.wikia.carousel.js',
		'//resources/wikia/libraries/jquery/modal/jquery.wikia.modal.js',
		'//resources/wikia/libraries/jquery/expirystorage/jquery.expirystorage.js',
		'//resources/wikia/libraries/jquery/focusNoScroll/jquery.focusNoScroll.js',

		// libraries loaders
		'//resources/wikia/libraries/jquery/getResources/jquery.wikia.getResources.js',
		'//resources/wikia/libraries/jquery/loadLibrary/jquery.wikia.loadLibrary.js',
		'//resources/wikia/libraries/jquery/loadLibrary/jquery.wikia.loadExternalLibrary.js',
		'//skins/oasis/js/isTouchScreen.js',

		// jQuery/Oasis specific code
		'//skins/oasis/js/tables.js',

		// BackgroundChanger
		'//skins/oasis/js/BackgroundChanger.js',

		// Search A/B testing
		'//extensions/wikia/Search/js/SearchAbTest.DomUpdater.js',
		'//extensions/wikia/Search/js/SearchAbTest.Context.js',
		'//extensions/wikia/Search/js/SearchAbTest.js',

		// Article length & screen width tracking
		'//skins/oasis/js/ArticleLengthAbTesting.js',

		// rail
		'#group_rail_js',
	)
);

/** Wikia **/
$config['oasis_wikia_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// classes
		'//resources/wikia/libraries/my.class/my.class.js'
	)
);

/******** Skins *******/

/** Oasis **/

// core shared JS - used as part of oasis_shared_js_anon or oasis_shared_js_user.
// See BugzId 38541 for details on why it's better to have these 2 different packages!
// (short version: less HTTP requests is more important than optimizing page-weight of the single page after you log in/out)
$config['oasis_shared_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// shared libraries
		'#group_oasis_jquery',
		'#group_oasis_nojquery_shared_js',
		'#group_oasis_extensions_js',
	)
);

$config['oasis_nojquery_shared_js_user'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_nojquery_shared_js',
		// the only time this group is used currently is inside of this _nojquery_ package, for Ad-Loading experiment
		'#group_oasis_noads_extensions_js',
		'#group_oasis_user_js',
	)
);

$config['oasis_nojquery_shared_js_anon'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_nojquery_shared_js',
		// the only time this group is used currently is inside of this _nojquery_ package, for Ad-Loading experiment
		'#group_oasis_noads_extensions_js',
		'#group_oasis_anon_js',
	)
);

$config['oasis_nojquery_shared_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(

		// shared libraries
		'//extensions/wikia/AssetsManager/js/AssetsManager.js',
		'//extensions/wikia/JSMessages/js/JSMessages.js',
		'//extensions/wikia/CategorySelect/js/CategorySelect.view.js',
		'//extensions/wikia/WikiaStyleGuide/js/Form.js',
		'//resources/wikia/modules/csspropshelper.js',
		'//resources/wikia/modules/fluidlayout.js',
		'//resources/wikia/modules/breakpointsLayout.js',

		// oasis specific files
		'//resources/wikia/libraries/bootstrap/tooltip.js',
		'//resources/wikia/libraries/bootstrap/popover.js',
		'//skins/oasis/js/PageHeader.js',
		'//skins/oasis/js/Search.js',
		'//skins/oasis/js/WikiaFooter.js',
		'//skins/oasis/js/buttons.js',
		'//skins/oasis/js/WikiHeader.js',
		'//skins/oasis/js/WikiaNotifications.js',
		'//skins/oasis/js/FirefoxFindFix.js',
		'//skins/oasis/js/tabs.js',
		'//skins/oasis/js/Tracking.js',

		'//skins/shared/scripts/onScroll.js',

		'//extensions/wikia/UserTools/scripts/UserTools.js',
	)
);

//anon JS
// Note: Owen moved getSiteJS call from both anon_js and user_js to OasisController::loadJS
// so that common.js is loaded last so it has less chance of breaking other things
$config['oasis_anon_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'#group_user_login_js_anon',
		'//skins/oasis/js/LatestActivity.js',
	]
];

$config['user_login_js_anon'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//extensions/wikia/UserLogin/js/FacebookLogin.js',
		'//extensions/wikia/UserLogin/js/MarketingOptIn.js',
		'//extensions/wikia/UserLogin/js/UserBaseAjaxForm.js',
		'//extensions/wikia/UserLogin/js/mixins/UserSignup.mixin.js',
		'//extensions/wikia/UserLogin/js/UserSignupAjaxValidation.js',
		'//extensions/wikia/UserLogin/js/FacebookFormCreateUser.js',
		'//extensions/wikia/UserLogin/js/FacebookFormConnectUser.js',
		'//extensions/wikia/UserLogin/js/UserLoginAjaxForm.js',
		'//extensions/wikia/UserLogin/js/UserLoginModal.js',
	]
];

/**
 * We allow logged in users to create account without logging out
 */
$config['user_signup_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//extensions/wikia/UserLogin/js/UserSignupAjaxValidation.js',
		'//extensions/wikia/UserLogin/js/MarketingOptIn.js',
		'//extensions/wikia/UserLogin/js/mixins/UserSignup.mixin.js',
		'//extensions/wikia/UserLogin/js/UserSignup.js',
	]
];

/**
 * For logged in users in oasis
 * @todo Remove this and clean up references UC-228
 */
$config['oasis_user_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => []
);

/** GameGuides */
$config['gameguides_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/GameGuides/css/GameGuides.scss'
	)
);

//this combines couple of WikiaMobile groups to make it possible to load all js via one request
//also unfortunately loads bit more than needed not to change WikiaMobile assets too much
$config['gameguides_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/GameGuides/js/initGameGuides.js',
		'//extensions/wikia/WikiaMobile/js/html_js_class.js',

		//libraries/frameworks
		'//resources/wikia/libraries/modil/modil.js',
		'//resources/jquery/jquery-2.0.3.js',
		'//resources/wikia/libraries/Ponto/ponto.js',

		//core modules
		'//resources/wikia/modules/window.js',
		'//resources/wikia/modules/location.js',
		'//resources/wikia/modules/nirvana.js',
		'//resources/wikia/modules/loader.js',
		'//resources/wikia/modules/querystring.js',
		'//resources/wikia/modules/history.js',
		'//resources/wikia/modules/log.js',

		//tracker
		'#group_tracker_js',

		//platform components
		'//extensions/wikia/JSMessages/js/JSMessages.js',
		'//extensions/wikia/JSSnippets/js/JSSnippets.js',

		//feature detection
		'//extensions/wikia/WikiaMobile/js/features.js',
		'//extensions/wikia/WikiaMobile/js/feature-detects/positionfixed.wikiamobile.js',
		'//extensions/wikia/WikiaMobile/js/feature-detects/overflow.wikiamobile.js',
		'//extensions/wikia/GameGuides/js/isGameGuides.js',

		//polyfills
		'//extensions/wikia/WikiaMobile/js/viewport.js',
		'//resources/wikia/libraries/iScroll/iscroll.js',

		//groups
		'#group_wikiamobile_tables_js',
		'#group_wikiamobile_mediagallery_js',

		//video
		'//extensions/wikia/VideoHandlers/js/VideoBootstrap.js',

		//modules
		'//resources/wikia/modules/thumbnailer.js',
		'//resources/wikia/libraries/sloth/sloth.js',
		'//extensions/wikia/WikiaMobile/js/toc.js',
		'//extensions/wikia/WikiaMobile/js/lazyload.js',
		'//extensions/wikia/WikiaMobile/js/track.js',
		'//extensions/wikia/WikiaMobile/js/events.js',
		'//extensions/wikia/WikiaMobile/js/throbber.js',
		'//extensions/wikia/WikiaMobile/js/toast.js',
		'//extensions/wikia/WikiaMobile/js/pager.js',
		'//extensions/wikia/WikiaMobile/js/modal.js',
		'//extensions/wikia/WikiaMobile/js/media.class.js',
		'//extensions/wikia/WikiaMobile/js/media.js',
		'//extensions/wikia/WikiaMobile/js/sections.js',
		'//extensions/wikia/WikiaMobile/js/layout.js',

		//entrypoint
		'//extensions/wikia/WikiaMobile/js/WikiaMobile.js',
		'//extensions/wikia/GameGuides/js/GameGuides.js',
	)
);

/** WikiaMobile **/
$config['wikiamobile_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/css/WikiaMobile.scss',
	)
);

$config['wikiamobile_editor_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobileEditor/css/WikiaMobileEditor.scss',
	)
);

$config['wikiamobile_editor_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'#group_wikiamobile_tables_js',
		'//resources/wikia/libraries/mustache/mustache.js',
		'//extensions/wikia/WikiaMobileEditor/js/preview.js'
	)
);

$config['wikiamobile_editor_view_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobileEditor/css/WikiaMobileEditor.edit.scss'
	)
);

$config['wikiamobile_404_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/css/404.scss',
	)
);

$config['wikiamobile_404_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/js/404.js',
	)
);

//loaded at the bottom of the page in the body section
$config['wikiamobile_js_body_minimal'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		//libraries/frameworks
		'//resources/wikia/libraries/modil/modil.js',
		'//resources/jquery/jquery-2.0.3.js',

		//core modules
		'//resources/wikia/modules/window.js',
		'//resources/wikia/modules/document.js',
		'//resources/wikia/modules/location.js',
		'//resources/wikia/modules/localStorage.js',
		'//resources/wikia/modules/querystring.js',
		'//resources/wikia/modules/history.js',
		'//resources/wikia/modules/cookies.js',
		//depends on querystring.js and cookies.js
		'//resources/wikia/modules/log.js',
		'//resources/wikia/modules/abTest.js',
		'//resources/wikia/modules/geo.js',
		'//resources/wikia/modules/instantGlobals.js',

		//feature detection
		'//extensions/wikia/WikiaMobile/js/features.js',
		'//extensions/wikia/WikiaMobile/js/feature-detects/positionfixed.wikiamobile.js',

		//tracker
		'#group_tracker_js',

		// performance
		'#group_bucky_js',

		//modules
		'//resources/wikia/modules/nirvana.js',
		'//resources/wikia/modules/loader.js',
		'//resources/wikia/modules/cache.js',

		// video
		'//extensions/wikia/VideoHandlers/js/VideoBootstrap.js',
	)
);

//loaded at the bottom of the page in the body section
$config['wikiamobile_js_body_full'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'#group_wikiamobile_js_body_minimal',

		//feature detection
		'//extensions/wikia/WikiaMobile/js/feature-detects/overflow.wikiamobile.js',

		//polyfills
		'//extensions/wikia/WikiaMobile/js/viewport.js',

		//libraries
		'//resources/wikia/libraries/iScroll/iscroll.js',
		'//extensions/wikia/WikiaMobile/js/webview.js',

		//platform components
		'//extensions/wikia/AssetsManager/js/AssetsManager.js',
		'//extensions/wikia/JSMessages/js/JSMessages.js',
		'//extensions/wikia/JSSnippets/js/JSSnippets.js',

		//modules
		'//resources/wikia/libraries/mustache/mustache.js',
		'//resources/wikia/libraries/sloth/sloth.js',
		'//resources/wikia/modules/thumbnailer.js',
		'//extensions/wikia/WikiaMobile/js/lazyload.js',
		'//extensions/wikia/WikiaMobile/js/track.js',
		'//extensions/wikia/WikiaMobile/js/infobox.js',
		'//extensions/wikia/WikiaMobile/js/events.js',
		'//extensions/wikia/WikiaMobile/js/topbar.js',
		'//extensions/wikia/WikiaMobile/js/sections.js',
		'//extensions/wikia/WikiaMobile/js/throbber.js',
		'//extensions/wikia/WikiaMobile/js/toast.js',
		'//extensions/wikia/WikiaMobile/js/pager.js',
		'//extensions/wikia/WikiaMobile/js/modal.js',
		'//extensions/wikia/WikiaMobile/js/media.class.js',
		'//extensions/wikia/WikiaMobile/js/media.js',
		'//extensions/wikia/WikiaMobile/js/layout.js',
		'//extensions/wikia/WikiaMobile/js/navigation.wiki.js',
		'//extensions/wikia/WikiaMobile/js/curtain.js',

		//entrypoint
		'//extensions/wikia/WikiaMobile/js/WikiaMobile.js'
	)
);

$config['wikiamobile_js_preview'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'#group_wikiamobile_js_body_full',
	)
);

$config['wikiamobile_scss_preview'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/css/WikiaMobile.preview.scss'
	)
);

$config['wikiamobile_js_toc'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/TOC/js/modules/toc.js',
		'//extensions/wikia/WikiaMobile/js/toc.js',
	)
);

$config['wikiamobile_scss_toc'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/css/toc.scss',
	)
);

//mustache is generic but currently only used by smartbanner move if needed
$config['wikiamobile_smartbanner_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => [
		'//resources/wikia/libraries/mustache/mustache.js',
		'//extensions/wikia/WikiaMobile/SmartBanner/smartbanner.js',
	]
];

$config['wikiamobile_smartbanner_init_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => [
		'//extensions/wikia/WikiaMobile/SmartBanner/smartbanner.bootstrap.js',
	]
];

$config['wikiamobile_tables_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/js/tables.js'
	)
);

$config['mobile_base_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		// Modules
		'//resources/wikia/modules/lazyqueue.js',
		'//resources/wikia/modules/iframeWriter.js',

		// Advertisement libs
		'//extensions/wikia/AdEngine/js/AdContext.js',
		'//extensions/wikia/AdEngine/js/AdDecoratorLegacyParamFormat.js',
		'//extensions/wikia/AdEngine/js/AdEngine2.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageParams.js',
		'//extensions/wikia/AdEngine/js/AdTracker.js',
		'//extensions/wikia/AdEngine/js/EventDispatcher.js',
		'//extensions/wikia/AdEngine/js/MessageListener.js',
		'//extensions/wikia/AdEngine/js/SlotTracker.js',
		'//extensions/wikia/AdEngine/js/SlotTweaker.js',
		'//extensions/wikia/AdEngine/js/WikiaAdHelper.js',
		'//extensions/wikia/AdEngine/js/config/mobile.js',
		'//extensions/wikia/AdEngine/js/lookup/amazonMatch.js',
		'//extensions/wikia/AdEngine/js/lookup/services.js',
		'//extensions/wikia/AdEngine/js/provider/directGptMobile.js',
		'//extensions/wikia/AdEngine/js/provider/factoryWikiaGpt.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adDetect.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adElement.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeFilter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeConverter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/googleTag.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/helper.js',
		'//extensions/wikia/AdEngine/js/provider/openX.js',
		'//extensions/wikia/AdEngine/js/provider/openX.targeting.js',
		'//extensions/wikia/AdEngine/js/provider/paidAssetDrop.js',
		'//extensions/wikia/AdEngine/js/provider/remnantGptMobile.js',

		// Video ads
		'//extensions/wikia/AdEngine/js/WikiaDartVideoHelper.js',

		// Paid asset drop
		'//extensions/wikia/PaidAssetDrop/js/paidAssetDrop.js',
	)
);

$config['mercury_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// Common modules
		'//resources/wikia/libraries/modil/modil.js',
		'#group_tracker_js',
		'//resources/wikia/modules/log.js',
		'//resources/wikia/modules/window.js',
		'//resources/wikia/modules/document.js',
		'//resources/wikia/modules/location.js',
		'//resources/wikia/modules/querystring.js',
		'//resources/wikia/modules/history.js',
		'//resources/wikia/modules/cookies.js',
		'//resources/wikia/modules/geo.js',
		'//resources/wikia/modules/instantGlobals.js',

		'#group_mobile_base_ads_js',
		'//resources/wikia/modules/cache.js',
		'//resources/wikia/modules/localStorage.js',

		// Advertisement libs
		'//extensions/wikia/AdEngine/js/AdLogicPageViewCounter.js',
		'//extensions/wikia/AbTesting/js/AbTest.js',
		'//resources/wikia/modules/abTest.js',
		'//extensions/wikia/AdEngine/js/CustomAdsLoader.js',
		'//extensions/wikia/AdEngine/js/template/floor.js',
		'//extensions/wikia/AdEngine/js/template/modal.js',
		'//resources/wikia/modules/krux.js',

		'//extensions/wikia/AdEngine/js/run/mercury.run.js',
	)
);

$config['interactivemaps_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'interactivemaps',
	'assets' => array(
		'//resources/wikia/libraries/modil/modil.js',

		// Modules
		'//resources/wikia/modules/document.js',
		'//resources/wikia/modules/location.js',
		'//resources/wikia/modules/log.js',
		'//resources/wikia/modules/querystring.js',
		'//resources/wikia/modules/history.js',
		'//resources/wikia/modules/window.js',

		// Advertisement libs
		'//extensions/wikia/AdEngine/js/provider/factoryWikiaGpt.js',
		'//extensions/wikia/AdEngine/js/provider/directGptMaps.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adElement.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeFilter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/adSizeConverter.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/googleTag.js',
		'//extensions/wikia/AdEngine/js/provider/gpt/helper.js',
		'//extensions/wikia/AdEngine/js/SlotTweaker.js',
		'//extensions/wikia/AdEngine/InteractiveMaps/ads.js'
	)
);

$config['wikiamobile_ads_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/AdEngine/js/AdLogicPageViewCounter.js',

		// Krux
		'//resources/wikia/modules/krux.js',

		'#group_mobile_base_ads_js',

		// Interactive maps integration
		'#group_adengine2_interactive_maps_js',

		// Run!
		'//extensions/wikia/AdEngine/js/run/wikiamobile.run.js',
	)
);

$config['wikiamobile_mediagallery_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/js/mediagallery.js'
	)
);

$config['wikiamobile_autocomplete_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/js/autocomplete.js'
	)
);

$config['wikiamobile_usersignup_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/UserLogin/css/UserSignup.wikiamobile.scss'
	)
);

$config['wikiamobile_usersignup_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/tooltip.js',
		'//extensions/wikia/WikiaStyleGuide/js/Form.js',
		'//extensions/wikia/UserLogin/js/UserSignupAjaxValidation.js',
		'//extensions/wikia/UserLogin/js/MarketingOptIn.js',
		'//extensions/wikia/UserLogin/js/mixins/UserSignup.mixin.js',
		'//extensions/wikia/UserLogin/js/UserSignup.js',
	)
);

$config['wikiamobile_categorypage_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaMobile/js/category_page.js',
	)
);

$config['wikiapoll_wikiamobile_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaPoll/css/WikiaPoll.wikiamobile.scss',
	)
);

$config['wikiapoll_wikiamobile_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaPoll/js/WikiaPoll.wikiamobile.js',
	)
);


$config['special_contact_wikiamobile_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/SpecialContact2/SpecialContact.wikiamobile.scss',
	)
);

$config['special_contact_wikiamobile_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/SpecialContact2/SpecialContact.wikiamobile.js',
	)
);

/** MonoBook **/
$config['monobook_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_shared_core_js',
		'#group_oasis_jquery',
		'#group_bucky_js',
		'#group_articlecomments_js',

		// TODO: remove dependency on YUI (see BugId:3116)
		'//resources/wikia/libraries/yui/utilities/utilities.js',
		'//resources/wikia/libraries/yui/cookie/cookie-beta.js',
		'//resources/wikia/libraries/yui/container/container.js',
		'//resources/wikia/libraries/yui/autocomplete/autocomplete.js',
		'//resources/wikia/libraries/yui/logger/logger.js',
		'//resources/wikia/libraries/yui/menu/menu.js',
		'//resources/wikia/libraries/yui/tabview/tabview.js',
		'//resources/wikia/libraries/yui/extra/tools-min.js',

//		'//resources/mediawiki/mediawiki.util.js', # instead of //skins/common/wikibits.js'
//		'//skins/common/ajax.js',

		'//skins/monobook/main.js',
		'//resources/wikia/modules/lazyqueue.js',
		'//extensions/wikia/JSMessages/js/JSMessages.js',
		'#group_imglzy_js',
		'#group_spotlights_js',
		'//resources/wikia/libraries/ghostwriter/gw.min.js',
		'//skins/shared/scripts/onScroll.js',
		'//extensions/wikia/VideoHandlers/js/VideoBootstrap.js',
		'//extensions/wikia/JSSnippets/js/JSSnippets.js',

		'//resources/wikia/libraries/bootstrap/tooltip.js',
		'//resources/wikia/libraries/bootstrap/popover.js',
	)
);

/********** Extensions packages **********/

/** Article Comments **/
$config['articlecomments_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array('oasis', 'monobook'),
	'assets' => array(
		'//extensions/wikia/ArticleComments/js/ArticleComments.js'
	)
);

$config['articlecomments_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'oasis',
	'assets' => array(
		'//skins/oasis/css/core/ArticleComments.scss'
	)
);

$config['articlecomments_mini_editor_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'oasis',
	'assets' => array(
		'#group_articlecomments_scss',
		'//extensions/wikia/MiniEditor/css/MiniEditor.scss',
		'//extensions/wikia/MiniEditor/css/ArticleComments/ArticleComments.scss'
	)
);

$config['articlecomments_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/ArticleComments/js/ArticleComments.wikiamobile.js'
	)
);

$config['articlecomments_init_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/ArticleComments/js/ArticleComments_init.wikiamobile.js'
	)
);

$config['articlecomments_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/ArticleComments/css/ArticleComments.wikiamobile.scss'
	)
);

$config['filepage_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/FilePage/scripts/FilePage.wikiamobile.js'
	)
);

$config['filepage_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/FilePage/css/FilePage.wikiamobile.scss'
	)
);

$config['relatedpages_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis', 'monobook', 'wikiamobile' ],
	'assets' => array(
		'//resources/wikia/libraries/sloth/sloth.js',
		'//resources/wikia/libraries/mustache/mustache.js',
		'//extensions/wikia/RelatedPages/js/RelatedPages.js'
	)
);

$config['relatedpages_wikiamobile_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'wikiamobile' ],
	'assets' => array(
		'//extensions/wikia/RelatedPages/js/relatedPages.wikiamobile.js'
	)
);

/** EditPageLayout **/
$config['editpage_events_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/EditPreview/js/editpage.event.preview.js',
		'//extensions/wikia/EditPreview/js/editpage.event.diff.js',
		'//extensions/wikia/EditPreview/js/editpage.event.helper.js',
		'//extensions/wikia/EditPreview/js/editpage.events.js'
	)
];

$config['editpage_common_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/EditPageLayout/js/loaders/EditPageEditorLoader.js',
		'//extensions/wikia/EditPreview/js/preview.js',
		'#group_editpage_events_js',
	)
);

$config['rte'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
			'#function_AssetsConfig::getRTEAssets'
	)
);
// Generic edit page JavaScript
$config['epl'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
			'#group_editpage_common_js',
			'#function_AssetsConfig::getEPLAssets',
	)
);
// Generic edit page JavaScript + reskined rich text editor
$config['eplrte'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_epl',
		'#group_rte'
	)
);

/** Ace Editor **/
$config['ace_editor_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_editpage_common_js',
		'//resources/wikia/libraries/Ace/wikia.ace.editor.js',
		'//extensions/wikia/EditPageLayout/js/editor/AceEditor.js',
	)
);

/** MiniEditor **/
$config['mini_editor_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// MediaWiki Edit stack
		'//resources/jquery/jquery.byteLimit.js',
		'//resources/jquery/jquery.textSelection.js',
		'//resources/mediawiki.action/mediawiki.action.edit.js',
		// Edit Page Layout
		'//extensions/wikia/EditPageLayout/js/editor/WikiaEditor.js',
		'//extensions/wikia/EditPageLayout/js/editor/WikiaEditorStorage.js',
		'//extensions/wikia/EditPageLayout/js/editor/Buttons.js',
		'//extensions/wikia/EditPageLayout/js/editor/Modules.js',
		'//extensions/wikia/EditPageLayout/js/plugins/MiniEditor.js',
		'//extensions/wikia/EditPageLayout/js/plugins/Tracker.js',
		'//extensions/wikia/EditPageLayout/js/plugins/Collapsiblemodules.js',
		'//extensions/wikia/EditPageLayout/js/plugins/Cssloadcheck.js',
		'//extensions/wikia/EditPageLayout/js/plugins/Edittools.js',
		'//extensions/wikia/EditPageLayout/js/plugins/Loadingstatus.js',
		'//extensions/wikia/EditPageLayout/js/extras/Buttons.js',
		'//extensions/wikia/EditPageLayout/js/modules/Container.js',
		'//extensions/wikia/EditPageLayout/js/modules/ButtonsList.js',
		'//extensions/wikia/EditPageLayout/js/modules/FormatMiniEditor.js',
		'//extensions/wikia/EditPageLayout/js/modules/FormatMiniEditorSource.js',
		'//extensions/wikia/EditPageLayout/js/modules/Insert.js',
		'//extensions/wikia/EditPageLayout/js/modules/InsertMiniEditor.js',
		'//extensions/wikia/EditPageLayout/js/modules/ModeSwitch.js',
		// Photo and Video tools
		'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.js',
		'//extensions/wikia/WikiaMiniUpload/js/WMU.js',
		// Third Party
		'//resources/jquery.ui/jquery.ui.core.js',
		'//resources/jquery.ui/jquery.ui.widget.js',
		'//resources/jquery.ui/jquery.ui.position.js',
		'//resources/jquery.ui/jquery.ui.autocomplete.js',
		'//resources/wikia/libraries/mustache/mustache.js',
	)
);

$config['mini_editor_rte_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_mini_editor_js',
		'#group_rte'
	)
);

$config['chat_js2'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_jquery',
		'#group_oasis_shared_core_js',

		// shared libraries
		'//extensions/wikia/AssetsManager/js/AssetsManager.js',
		'//extensions/wikia/JSMessages/js/JSMessages.js',

		'//resources/wikia/modules/querystring.js',
		'//resources/wikia/modules/history.js',
		'//resources/wikia/modules/cookies.js',
		//depends on querystring.js and cookies.js
		'//resources/wikia/modules/log.js',

		//'//extensions/wikia/Chat2/js/lib/socket.io.client.js',
		// must be before controllers.js
		'//extensions/wikia/Chat2/js/emoticons.js',
		'//extensions/wikia/Chat2/js/lib/underscore.js',
		'//extensions/wikia/Chat2/js/lib/backbone.js',
		'//extensions/wikia/Chat2/js/models/models.js',
		'//extensions/wikia/Chat2/js/controllers/controllers.js',
		'//extensions/wikia/Chat2/js/views/views.js',
		'//extensions/wikia/Chat2/js/views/ChatBanModal.js',
	)
);

$config['chat_ban_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/Chat2/js/views/ChatBanModal.js',
		'//extensions/wikia/Chat2/js/controllers/ChatBanModalLogs.js'
	)
);

/** ThemeDesigner **/
$config['theme_designer_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_wikia_js',
		'#group_oasis_jquery',

		'//resources/jquery.ui/jquery.ui.widget.js',
		'//resources/jquery.ui/jquery.ui.mouse.js',
		'//resources/jquery.ui/jquery.ui.slider.js',
		'//resources/jquery.ui/jquery.ui.core.js',
		'//resources/wikia/modules/aim.js',
		'//resources/wikia/libraries/bootstrap/tooltip.js',
		'//resources/wikia/libraries/bootstrap/popover.js',
		'//extensions/wikia/JSMessages/js/JSMessages.js',
		'//extensions/wikia/ThemeDesigner/js/ThemeDesigner.js'
	)
);

/** MessageWall **/
$config['wall_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/wikia/libraries/jquery/autoresize/jquery.autoresize.js',
		'//resources/wikia/libraries/jquery/scrollto/jquery.scrollTo-1.4.2.js',
		'//extensions/wikia/Wall/js/Wall.js',
		'//extensions/wikia/Wall/js/WallPagination.js',
		'//extensions/wikia/Wall/js/WallBackendBridge.js',
		'//extensions/wikia/Wall/js/WallMessageForm.js',
		'//extensions/wikia/Wall/js/WallNewMessageForm.js',
		'//extensions/wikia/Wall/js/WallEditMessageForm.js',
		'//extensions/wikia/Wall/js/WallReplyMessageForm.js',
		'//extensions/wikia/Wall/js/WallSortingBar.js',
		'//extensions/wikia/Wall/js/WallSetup.js'
	)
);

$config['wall_notifications_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/WallNotifications/scripts/WallNotifications.js',
	)
);

$config['wall_mini_editor_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/MiniEditor/js/Wall/Wall.Setup.js',
		'//extensions/wikia/MiniEditor/js/Wall/Wall.Animations.js',
		'//extensions/wikia/MiniEditor/js/Wall/Wall.EditMessageForm.js',
		'//extensions/wikia/MiniEditor/js/Wall/Wall.NewMessageForm.js',
		'//extensions/wikia/MiniEditor/js/Wall/Wall.ReplyMessageForm.js'
	)
);

$config['wall_history_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/Wall/js/Wall.js',
		'//extensions/wikia/Wall/js/WallHistory.js',
		'//extensions/wikia/Wall/js/WallSortingBar.js'
	)
);

/** Forum **/
$config['forum_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
//		'#group_wall_js',
		'//extensions/wikia/Forum/js/Forum.js',
		'//extensions/wikia/Forum/js/ForumNewMessageForm.js',
		'//extensions/wikia/Forum/js/ForumSortingBar.js'
	)
);

/** Wall MessageTopic (certain parts of Wall and Forum uses this) **/
$config['wall_topic_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/Wall/js/MessageTopic.js',
	)
);

$config['forum_mini_editor_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_wall_mini_editor_js',
		'//extensions/wikia/MiniEditor/js/Forum/Forum.Setup.js',
//		'//extensions/wikia/MiniEditor/js/Forum/Forum.Animations.js',
//		'//extensions/wikia/MiniEditor/js/Forum/Forum.EditMessageForm.js',
		'//extensions/wikia/MiniEditor/js/Forum/Forum.NewMessageForm.js',
//		'//extensions/wikia/MiniEditor/js/Forum/Forum.ReplyMessageForm.js'
	)
);

$config['VET_js'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/WikiaStyleGuide/js/Dropdown.js',
		'//extensions/wikia/VideoEmbedTool/js/VET.js',
		'//resources/jquery.ui/jquery.ui.widget.js',
		'//resources/jquery.ui/jquery.ui.mouse.js',
		'//resources/jquery.ui/jquery.ui.slider.js',
	)
);

/**
 * @name VideoPageTool
 * @description Assets for the VideoPageTool, same styles as SpecialEditHub
 * The JS is split into two different packages for the dashboard and edit pages
 */

$config['videopageadmin_dashboard_js'] = [
	'skin' => ['oasis'],
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		// Library Dependencies
		'//extensions/wikia/VideoPageTool/scripts/lib/lodash/dist/lodash.underscore.js',
		'//extensions/wikia/VideoPageTool/scripts/lib/backbone/backbone.js',
		'//resources/jquery.ui/jquery.ui.datepicker.js',

		'//extensions/wikia/VideoPageTool/scripts/admin/models/datepicker.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/datepicker.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/dashboard.js',
	]
];
$config['videopageadmin_edit_js'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// Lodash is an underscore.js rewrite and a prereq for Backbone
		'//extensions/wikia/VideoPageTool/scripts/lib/lodash/dist/lodash.underscore.js',
		'//extensions/wikia/VideoPageTool/scripts/lib/backbone/backbone.js',

		// Library Dependencies
		'//resources/wikia/libraries/jquery/owl.carousel/owl.carousel.js',
		'//resources/jquery.ui/jquery.ui.core.js',
		'//resources/jquery/jquery.validate.js',
		'//resources/wikia/modules/aim.js',
		'//extensions/wikia/WikiaMiniUpload/js/WMU.js',
		// TODO: probably move this jQuery plugin to /resources at some point
		'//extensions/wikia/VideoPageTool/scripts/shared/views/switcher.js',

		// Compiled Mustache templates
		'//extensions/wikia/VideoPageTool/scripts/templates.mustache.js',

		'//extensions/wikia/VideoPageTool/scripts/admin/models/thumbnail.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/models/validator.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/thumbnailupload.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/editbase.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/featured.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/collections/category.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/collections/categorydata.js',

		'//extensions/wikia/VideoPageTool/scripts/shared/views/owlcarousel.js',
		'//extensions/wikia/VideoPageTool/scripts/shared/views/carouselThumb.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/carousel.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/autocompleteitem.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/autocomplete.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/categoryforms.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/category.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/views/index.js',
	)
);

$config['videopageadmin_css'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_CSS,
	'assets' => array(
		'//resources/jquery.ui/themes/default/jquery.ui.datepicker.css',
		'//resources/wikia/libraries/jquery/owl.carousel/owl.carousel.css',
	)
);

$config['videopageadmin_scss'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_SCSS,
	'assets' => array(
		'//skins/oasis/css/modules/CorporateDatepicker.scss',
		'//extensions/wikia/WikiaMiniUpload/css/WMU.scss',
		'//extensions/wikia/VideoPageTool/css/admin/VideoPageTool.scss',
		'//extensions/wikia/VideoPageTool/css/admin/VideoPageTool_Header.scss',
		'//extensions/wikia/VideoPageTool/css/carousel.scss',
	)
);

/*
 * @name videohomepage
 * @description Assets for http://video.wikia.com/
 */

$config['videohomepage_js'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		// jQuery plugins
		'//resources/wikia/libraries/jquery/bxslider/jquery.bxslider.js',
		'//resources/wikia/libraries/jquery/owl.carousel/owl.carousel.js',

		// Lodash is an underscore.js rewrite and a prereq for Backbone
		'//extensions/wikia/VideoPageTool/scripts/lib/lodash/dist/lodash.underscore.js',
		'//extensions/wikia/VideoPageTool/scripts/lib/backbone/backbone.js',

		// Compiled Mustache templates
		'//extensions/wikia/VideoPageTool/scripts/templates.mustache.js',

		// Featured video slider
		'//extensions/wikia/VideoPageTool/scripts/homepage/collections/featuredslides.js',
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/featured.js',

		// Latest videos / category carousels
		'//extensions/wikia/VideoPageTool/scripts/homepage/models/categorycarousel.js',
		'//extensions/wikia/VideoPageTool/scripts/homepage/models/categorythumb.js',

		'//extensions/wikia/VideoPageTool/scripts/admin/collections/category.js',
		'//extensions/wikia/VideoPageTool/scripts/admin/collections/categorydata.js',

		'//extensions/wikia/VideoPageTool/scripts/shared/views/carouselThumb.js',
		'//extensions/wikia/VideoPageTool/scripts/shared/views/owlcarousel.js',
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/carouselThumb.js',
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/carousels.js',
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/carousel.js',

		// Search box
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/search.js',

		// "Router" for views
		'//extensions/wikia/VideoPageTool/scripts/homepage/views/index.js',
	)
);

$config['videohomepage_css'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_CSS,
	'assets' => array(
		'//resources/wikia/libraries/jquery/owl.carousel/owl.carousel.css',
	)
);

$config['videohomepage_scss'] = array(
	'skin' => array( 'oasis' ),
	'type' => AssetsManager::TYPE_SCSS,
	'assets' => array(
		// Dependencies
		'//resources/wikia/libraries/jquery/bxslider/jquery.bxslider.scss',
		// VideoHomePage
		'//extensions/wikia/VideoPageTool/css/homepage/main.scss',
		'//extensions/wikia/VideoPageTool/css/carousel.scss',
		'//extensions/wikia/VideoPageTool/css/homepage/featured.scss',
	)
);

/** UserLogin **/
$config['userlogin_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
			'//extensions/wikia/UserLogin/css/UserLogin.wikiamobile.scss'
	)
);

$config['userlogin_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
			'//extensions/wikia/UserLogin/js/UserLoginSpecial.wikiamobile.js'
	)
);

$config['userlogin_facebook_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/UserLogin/js/UserLoginFacebook.wikiamobile.js'
	)
);

/** UserProfilePage **/
$config['userprofilepage_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/UserProfilePageV3/css/UserProfilePage.wikiamobile.scss'
	)
);

/** WikiaHomepage **/
$config['wikiahomepage_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'oasis',
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.scss',
		'//skins/oasis/css/wikiagrid.scss',
		'//skins/oasis/css/modules/WikiaMediaCarousel.scss',
	)
);

$config['wikiahomepage_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'oasis',
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.js',
		'//extensions/wikia/WikiaHomePage/js/WikiaHomePage.js',
	)
);

$config['wikiahomepage_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/WikiaHomePage/css/WikiaHomePage.wikiamobile.scss'
	)
);

/** WikiaHubsV3 **/
$config['wikiahubs_v3'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array('oasis'),
	'assets' => array(
		'//extensions/wikia/WikiaHubsV3/js/WikiaHubsV3.js'
	)
);

$config['wikiahubs_v3_modal'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array('oasis'),
	'assets' => array(
		'//extensions/wikia/WikiaHubsV3/js/WikiaHubsV3Modals.js'
	)
);

$config['wikiahubs_v3_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array('oasis'),
	'assets' => array(
		'//extensions/wikia/WikiaHubsV3/css/WikiaHubsV3.scss'
	)
);

$config['wikiahubs_v3_scss_mobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array('wikiamobile'),
	'assets' => array(
		'//extensions/wikia/WikiaHubsV3/css/WikiaHubsV3Mobile.scss'
	)
);

/** WAMPage **/
$config['wampage_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array('oasis'),
	'assets' => array(
		'//extensions/wikia/WAMPage/css/WAMPage.scss'
	)
);

$config['wampage_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array('oasis'),
	'assets' => array(
		'//extensions/wikia/WAMPage/js/WAMPage.js',
	)
);

/** WikiaSearch **/
$config['wikiasearch_js_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/Search/js/WikiaSearch.wikiamobile.js'
	)
);

$config['wikiasearch_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
		'//extensions/wikia/Search/css/WikiaSearch.wikiamobile.scss'
	)
);

/** Places **/
$config['places_js'] = array(
	'skin' => array( 'oasis', 'monobook', 'wikiamobile', 'venus' ),
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
			'//extensions/wikia/Places/js/Places.js'
	)
);

$config['places_css'] = array(
	'type' => AssetsManager::TYPE_CSS,
	'skin' => array( 'oasis', 'monobook', 'wikiamobile', 'venus' ),
	'assets' => array(
			'//extensions/wikia/Places/css/Places.css',
	)
);

/** WikiaPhotoGallery **/
$config['wikiaphotogallery_slider_js_wikiamobile'] = array(
	'skin' => 'wikiamobile',
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
			'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.slider.wikiamobile.js'
	)
);

$config['wikiaphotogallery_slider_scss_wikiamobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => 'wikiamobile',
	'assets' => array(
			'//extensions/wikia/WikiaPhotoGallery/css/WikiaPhotoGallery.slidertag.wikiamobile.scss'
	)
);

$config['wikia_photo_gallery_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.view.js',
	]
];

$config['wikia_photo_gallery_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/css/gallery.scss',
	]
];

$config['wikia_photo_gallery_slideshow_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//resources/wikia/libraries/jquery/slideshow/jquery-slideshow-0.4.js',
		'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.slideshow.js',
	]
];

$config['wikia_photo_gallery_slideshow_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/css/slideshow.scss',
	]
];

$config['wikia_photo_gallery_slider_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.slider.js',
	]
];

$config['wikia_photo_gallery_slider_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'venus', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/css/WikiaPhotoGallery.slidertag.scss',
	]
];

$config['wikia_photo_gallery_mosaic_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//resources/wikia/libraries/modernizr/modernizr-2.0.6.js',
		'//extensions/wikia/WikiaPhotoGallery/js/WikiaPhotoGallery.slider.mosaic.js',
	]
];

$config['wikia_photo_gallery_mosaic_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//extensions/wikia/WikiaPhotoGallery/css/WikiaPhotoGallery.slidertag.mosaic.scss',
	]
];

// ImageDrop
$config['imagedrop_js'] = array(
	'skin' => array( 'monobook', 'oasis' ),
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/hacks/ImageDrop/js/ImageDrop.js',
		'//resources/wikia/libraries/jquery/filedrop/jquery.filedrop.js'
	)
);

$config['imagedrop_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'monobook', 'oasis' ),
	'assets' => array(
		'//extensions/wikia/ImageDrop/css/ImageDrop.scss'
	)
);

/** AnalyticsEngine **/
/** Note: this group is also used in Oasis! */
$config['universal_analytics_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'wikiamobile', 'venus' ],
	'assets' => [
		'//extensions/wikia/AnalyticsEngine/js/universal_analytics.js'
	]
);

$config['analytics_bluekai_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['wikiamobile' ],
	'assets' => [
		'//extensions/wikia/AdEngine/js/AdContext.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageParams.js',
		'//extensions/wikia/AdEngine/js/AdLogicPageViewCounter.js',
	]
);

/** WikiMap Extension **/
$config['wiki_map_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/hacks/WikiMap/js/d3.v2.js',
		'//extensions/wikia/hacks/WikiMap/js/jquery.xcolor.js',
		'//extensions/wikia/hacks/WikiMap/js/WikiMapIndexContent.js'
	)
);

/* Special:Leaderboard in AchievementsII extensions */
$config['special_leaderboard_oasis_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array( 'oasis' ),
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.js',
		'//extensions/wikia/AchievementsII/js/SpecialLeaderboard.js',
		'//skins/oasis/js/Achievements.js',
	)
);

$config['special_leaderboard_oasis_css'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'oasis' ),
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.scss',
		'//extensions/wikia/AchievementsII/css/leaderboard_oasis.scss',
	)
);

/* Achievements module */
$config['achievements_css'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'oasis' ),
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.scss',
		'//extensions/wikia/AchievementsII/css/oasis.scss',
	)
);

$config['achievements_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array( 'oasis' ),
	'assets' => array(
		'//resources/wikia/libraries/bootstrap/popover.js',
		'//skins/oasis/js/Achievements.js',
	)
);

/* Special:Videos */
$config['special_videos_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array( 'oasis', 'monobook' ),
	'assets' => array(
		'//extensions/wikia/SpecialVideos/scripts/SpecialVideos.js',
		'//extensions/wikia/WikiaStyleGuide/js/Dropdown.js',
	)
);

$config['special_videos_css'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'oasis', 'monobook' ),
	'assets' => array(
		'//extensions/wikia/SpecialVideos/styles/SpecialVideos.scss',
		'//extensions/wikia/WikiaStyleGuide/css/Dropdown.scss',
	)
);

$config['special_videos_css_monobook'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'monobook' ),
	'assets' => array(
		'//skins/oasis/css/lib/foundation.custom/foundation.custom.scss',
	)
);

$config['special_videos_js_mobile'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => array( 'wikiamobile' ),
	'assets' => array(
		'//extensions/wikia/SpecialVideos/scripts/templates.mustache.js',
		'//extensions/wikia/SpecialVideos/scripts/mobile/collections/video.js',
		'//extensions/wikia/SpecialVideos/scripts/mobile/views/index.js',
		'//extensions/wikia/SpecialVideos/scripts/mobile/controller.js',
	)
);

$config['special_videos_css_mobile'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => array( 'wikiamobile' ),
	'assets' => array(
		'//skins/oasis/css/core/thumbnails.scss',
		'//extensions/wikia/SpecialVideos/styles/mobile.scss',
	)
);

/* CategorySelect */
$config['categoryselect_edit_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/jquery.ui/jquery.ui.core.js',
		'//resources/jquery.ui/jquery.ui.widget.js',
		'//resources/jquery.ui/jquery.ui.mouse.js',
		'//resources/jquery.ui/jquery.ui.sortable.js',
		'//extensions/wikia/CategorySelect/js/CategorySelect.js',
	)
);

/* FilePage */
$config['wikia_file_page_js'] = array(
	'type'=> AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/FilePage/scripts/WikiaFilePage.js',
	)
);
$config['file_page_tabbed_js'] = array(
	'type'=> AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/FilePage/scripts/FilePageTabbed.js',
	)
);
$config['file_page_tabbed_css'] = array(
	'type' =>AssetsManager::TYPE_SCSS,
	'assets' => array(
		'//extensions/wikia/FilePage/css/FilePageTabbed.scss',
	)
);

/* LyricFind */
$config['LyricsFindTracking'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'wikiamobile', 'monobook'],
	'assets' => array(
		'//extensions/3rdparty/LyricWiki/LyricFind/js/modules/LyricFind.Tracker.js',
		'//extensions/3rdparty/LyricWiki/LyricFind/js/tracking.js',
	)
);

/* ManageWikiaHome */
$config['manage_wikia_home_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/SpecialManageWikiaHome/js/ManageWikiaHome.js',
		'//extensions/wikia/SpecialManageWikiaHome/js/CollectionsSetup.js',
		'//extensions/wikia/SpecialManageWikiaHome/js/CollectionsNavigation.js',
	)
);

$config['licensed_video_swap_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/LicensedVideoSwap/js/lvsTracker.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsCommonAjax.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsVideoControls.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsCallout.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsEllipses.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsSuggestions.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsSwapKeep.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsUndo.js',
		'//extensions/wikia/LicensedVideoSwap/js/lvsHistoryPage.js',
		'//extensions/wikia/LicensedVideoSwap/js/LicensedVideoSwap.js',
	),
);

/* SpecialCSS */
$config['special_css_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/wikia/libraries/Ace/wikia.ace.editor.js',
		'#group_editpage_events_js',
		'//extensions/wikia/SpecialCss/js/SpecialCss.js',
		'//resources/wikia/libraries/mustache/mustache.js',
		'//resources/wikia/libraries/mustache/jquery.mustache.js'
	)
);

/* UI repo JS API */
$config['ui_repo_api_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => array(
		'//resources/wikia/modules/nirvana.js',
		'//resources/wikia/modules/uifactory.js',
		'//resources/wikia/modules/uicomponent.js',
	)
);

$config['touchstorm_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'assets' => array(
		'//extensions/wikia/TouchStorm/css/TouchStorm.scss'
	)
);

$config['touchstorm_js'] = array(
		'type' => AssetsManager::TYPE_JS,
		'assets' => array(
				'//extensions/wikia/TouchStorm/js/TouchStorm.js'
		)
);

$config['toc_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//resources/wikia/libraries/mustache/mustache.js',
		'//extensions/wikia/TOC/js/modules/toc.js',
		'//extensions/wikia/TOC/js/tocWikiaArticle.js'
	)
);

// FIXME: paths to dist
$config['api_docs_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/ApiDocs/js/lib/jquery-1.8.0.min.js',
		'//extensions/wikia/ApiDocs/js/lib/jquery.wiggle.min.js',
		'//extensions/wikia/ApiDocs/js/lib/jquery.ba-bbq.min.js',
		'//extensions/wikia/ApiDocs/js/lib/jquery.slideto.min.js',
		'//extensions/wikia/ApiDocs/js/lib/highlight.7.3.pack.js',
		'//extensions/wikia/ApiDocs/js/lib/handlebars-1.0.0.js',
		'//extensions/wikia/ApiDocs/js/lib/underscore-min.js',
		'//extensions/wikia/ApiDocs/js/lib/backbone-min.js',
		'//extensions/wikia/ApiDocs/js/lib/swagger.js',

		'//extensions/wikia/ApiDocs/js/jquery.zclip.min.js',

		'//extensions/wikia/ApiDocs/js/doc.js',
		'//extensions/wikia/ApiDocs/js/HeaderView.js',
		'//extensions/wikia/ApiDocs/js/OperationView.js',
		'//extensions/wikia/ApiDocs/js/StatusCodeView.js',
		'//extensions/wikia/ApiDocs/js/ParameterView.js',
		'//extensions/wikia/ApiDocs/js/SignatureView.js',
		'//extensions/wikia/ApiDocs/js/MainView.js',
		'//extensions/wikia/ApiDocs/js/ResourceView.js',
		'//extensions/wikia/ApiDocs/js/ContentTypeView.js',
		'//extensions/wikia/ApiDocs/js/SwaggerUi.js',

		'//extensions/wikia/ApiDocs/templates/handlebars/content_type.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/main.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/operation.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/param.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/param_list.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/param_readonly.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/param_readonly_required.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/param_required.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/resource.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/signature.handlebars.js',
		'//extensions/wikia/ApiDocs/templates/handlebars/status_code.handlebars.js',

	)
);

$config['api_docs_scss'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'assets' => array(
		'//extensions/wikia/ApiDocs/css/ApiDocs.scss',
	)
);

$config['videos_module_common_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//extensions/wikia/Thumbnails/scripts/templates.mustache.js',
		'//extensions/wikia/Thumbnails/scripts/views/titleThumbnail.js',
		'//extensions/wikia/VideosModule/scripts/templates.mustache.js',
		'//extensions/wikia/VideosModule/scripts/models/videos.js',
		'//extensions/wikia/VideosModule/scripts/views/titleThumbnail.js',
		'//extensions/wikia/VideosModule/scripts/views/index.js',
	]
];

$config['videos_module_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'#group_videos_module_common_js',
		'//extensions/wikia/VideosModule/scripts/controllers/rail.js',
	]
];

$config['videos_module_venus_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['venus'],
	'assets' => [
		'#group_videos_module_common_js',
		'//extensions/wikia/VideosModule/scripts/controllers/inContent.js',
		'//resources/wikia/modules/nodeFinder.js',
	]
];

$config['qualaroo_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/Qualaroo/scripts/Qualaroo.js',
	]
];

$config['imglzy_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//extensions/wikia/ImageLazyLoad/js/ImgLzy.module.js',
		'//extensions/wikia/ImageLazyLoad/js/ImageLazyLoad.js',
	]
];

$config['rail_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/Rail/scripts/Rail.js',
	]
];

/** Qualaroo blocking **/
$config['qualaroo_blocking_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//extensions/wikia/Qualaroo/scripts/QualarooBlocking.js',
	)
);

/** Optimizely Blocking **/
$config['optimizely_blocking_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis', 'wikiamobile', 'venus' ],
	'assets' => array(
		'//extensions/wikia/Optimizely/scripts/OptimizelyBlocking.js',
	)
);

/** GlobalFooter extension */
$config['global_footer_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => array(
		'//extensions/wikia/GlobalFooter/scripts/GlobalFooter.js'
	)
);

/** CorporateFooter extension */
$config['corporate_footer_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => array(
		'//extensions/wikia/CorporateFooter/scripts/CorporateFooterTracker.js'
	)
);

/* extension/wikia/Bucky */
$config['bucky_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis', 'venus' ],
	'assets' => array(
		'//extensions/wikia/Bucky/vendor/Weppy/dist/weppy.js',
		'//extensions/wikia/Bucky/js/bucky_init.js',
		'//extensions/wikia/Bucky/js/bucky_resources_timing.js',
		'//extensions/wikia/Bucky/js/bucky_metrics.js',
	)
);

/* Monetization Module */
$config['monetization_module_css'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => [ 'oasis' ],
	'assets' => [
		'//extensions/wikia/MonetizationModule/styles/MonetizationModule.scss',
	]
);

$config['monetization_module_css_no_breakpoints'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => [ 'oasis' ],
	'assets' => [
		'//extensions/wikia/MonetizationModule/styles/MonetizationModuleNoBreakpoints.scss',
	]
);

$config['monetization_module_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis' ],
	'assets' => [
		'//extensions/wikia/MonetizationModule/scripts/MonetizationModule.js',

	]
);

$config['monetization_module_top_script_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'skin' => [ 'oasis' ],
	'assets' => [
		'//extensions/wikia/MonetizationModule/scripts/MonetizationModuleTopScript.js',

	]
);

/* extension/wikia/WikiaMaps */

$config['wikia_maps_ponto'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//resources/wikia/libraries/Ponto/ponto.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsPontoBridge.js',
	]
];

$config['wikia_maps_special_page_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsConfig.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'#group_wikia_maps_ponto',
		'//extensions/wikia/WikiaMaps/js/WikiaMaps.js',
	]
];

$config['wikia_maps_in_modal_display_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'#group_wikia_maps_ponto',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsConfig.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
	]
];

$config['wikia_maps_create_map_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsCreateMapTileSet.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsCreateMapPreview.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsCreateMapModal.js',
	]
];

$config['wikia_maps_create_map_contribute_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsConfig.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
	]
];

$config['wikia_maps_poi_categories_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsPoiCategories.js',
		'//extensions/wikia/WikiaMaps/js/models/WikiaMapsPoiCategoriesModel.js',
		'//resources/wikia/libraries/jquery/serialize-object/jquery.serialize-object.js',
	]
];

$config['wikia_maps_poi_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsPoi.js',
	]
];

$config['wikia_maps_delete_map_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsDeleteMap.js',
	]
];

$config['wikia_maps_undelete_map_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUndeleteMap.js',
	]
];

$config['wikia_maps_embed_map_code'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'//extensions/wikia/WikiaMaps/js/WikiaMapsEmbedMapCode.js',
	]
];

$config['wikia_maps_special_page_scss_wikiamobile'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['wikiamobile'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/css/WikiaMaps.wikiamobile.scss',
	]
];

$config['wikia_maps_contribution_button_create_map_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsCreateMap.js',
	]
];

$config['wikia_maps_parser_tag_scss_wikiamobile'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['wikiamobile'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/css/WikiaMapsParserTag.scss',
		'//extensions/wikia/WikiaMaps/css/WikiaMapsParserTag.wikiamobile.scss',
	]
];

$config['wikia_maps_special_page_js_wikiamobile'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['wikiamobile'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsUtils.js',
		'#group_wikia_maps_ponto',
		'//extensions/wikia/WikiaMaps/js/WikiaMaps.wikiamobile.js',
	]
];

$config['wikia_maps_parser_tag_js_wikiamobile'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['wikiamobile'],
	'assets' => [
		'//extensions/wikia/WikiaMaps/js/WikiaMapsParserTag.wikiamobile.js',
	]
];

/** GlobalNavigation extension */
$config[ 'global_navigation_scss' ] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigation.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationSearch.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationAccountNavigation.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationHubsMenu.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationUserLoginDropdown.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationNotifications.scss',
		'//extensions/wikia/GlobalNavigation/styles/GlobalNavigationInverse.scss',
		'//skins/shared/styles/transparent-out.scss'
	]
];

$config[ 'global_navigation_js' ] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'#group_menu_aim_js',
		'#group_delayed_hover_js',
		'//resources/wikia/modules/scrollToLink.js',
		'//skins/shared/scripts/transparent-out.js',
		'//extensions/wikia/Venus/scripts/layout.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationDropdownsHandler.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationiOSScrollFix.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationScrollToLink.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationTracking.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationLazyLoad.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationHubsMenu.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationSearch.js',
		'//extensions/wikia/GlobalNavigation/scripts/SearchSuggestions.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationInverseTransition.js',
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationAccountNavigation.js',
		'//extensions/wikia/UserLogin/js/UserBaseAjaxForm.js',
		'//extensions/wikia/UserLogin/js/UserLoginAjaxForm.js',
		'//resources/wikia/libraries/bootstrap/tooltip.js'
	]
];

$config['wall_notifications_global_navigation_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/GlobalNavigation/scripts/GlobalNavigationNotifications.js',
	]
];

$config[ 'local_navigation_oasis_scss' ] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => [ 'oasis' ],
	'assets' => [
		'//extensions/wikia/LocalNavigation/styles/LocalNavigationOasis.scss'
	]
];

$config['media_gallery_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//extensions/wikia/MediaGallery/scripts/templates.mustache.js',
		'//extensions/wikia/MediaGallery/scripts/views/caption.js',
		'//extensions/wikia/MediaGallery/scripts/views/media.js',
		'//extensions/wikia/MediaGallery/scripts/views/toggler.js',
		'//extensions/wikia/MediaGallery/scripts/views/gallery.js',
		'//extensions/wikia/MediaGallery/scripts/controllers/galleries.js',
	]
];

$config['facebook_client_preferences_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/FacebookClient/scripts/preferences.js',
	]
];

$config['facebook_client_preferences_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/FacebookClient/styles/preferences.scss',
	]
];

$config['banner_notifications_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['monobook'],
	'assets' => [
		'//extensions/wikia/BannerNotifications/css/BannerNotifications.scss',
		'//extensions/wikia/BannerNotifications/css/BannerNotifications.monobook.scss',
	]
];

$config['banner_notifications_js'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'monobook', 'venus'],
	'assets' => [
		'//extensions/wikia/BannerNotifications/js/BannerNotifications.js',
		'//extensions/wikia/BannerNotifications/js/templates.mustache.js',
	]
];

$config['wikia_in_your_lang_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/WikiaInYourLang/modules/ext.wikiaInYourLang.js',
	]
];

$config['cookie_policy_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/CookiePolicy/scripts/cookiePolicy.js',
	]
];

$config['facebook_client_fbtags_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/FacebookClient/scripts/FacebookClient.facebookTags.js',
	]
];

$config['delayed_hover_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//resources/wikia/libraries/delayed-hover/js-delayed-hover.js',
		'//resources/wikia/modules/delayedHover.js'
	]
];

$config['menu_aim_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'venus'],
	'assets' => [
		'//resources/wikia/libraries/menu-aim/menu-aim.js',
		'//resources/wikia/modules/menuAim.js'
	]
];

$config['njord_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//resources/jquery.ui/jquery.ui.core.js',
		'//resources/jquery.ui/jquery.ui.widget.js',
		'//resources/jquery.ui/jquery.ui.position.js',
		'//resources/jquery.ui/jquery.ui.mouse.js',
		'//resources/jquery.ui/jquery.ui.draggable.js',
		'//resources/jquery.ui/jquery.ui.droppable.js',
		'//extensions/wikia/NjordPrototype/scripts/jquery.caret.js',
		'//extensions/wikia/NjordPrototype/scripts/Njord.js'
	]
];

$config['upload_photos_dialog_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaNewFiles/scripts/uploadPhotosDialog.js'
	]
];

$config['upload_photos_dialog_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/WikiaNewFiles/styles/UploadPhotoDialog.scss'
	]
];

$config['page_share_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/PageShare/scripts/PageShare.js',
		'//extensions/wikia/PageShare/scripts/PageShareInit.js'
	]
];

$config['page_share_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/PageShare/styles/PageShare.scss'
	],
];

$config['global_footer_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/GlobalFooter/styles/GlobalFooter.scss'
	]
];

$config['captcha_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/Captcha/scripts/Captcha.js',
	]
];

$config['fancycaptcha_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/Captcha/styles/FancyCaptcha.scss',
	]
];

$config['poweruser'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//extensions/wikia/PowerUser/js/pageViewTracking.js',
		'//extensions/wikia/PowerUser/js/powerUser.run.js',
	]
];

$config['portable_infobox_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['oasis'],
	'assets' => [
		'//extensions/wikia/PortableInfobox/styles/PortableInfobox.scss'
	]
];

$config['portable_infobox_monobook_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => ['monobook'],
	'assets' => [
		'#group_portable_infobox_scss',
		'//extensions/wikia/PortableInfobox/styles/PortableInfoboxMonobook.scss'
	]
];

$config['flags_view_scss'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => [ 'oasis', 'monobook' ],
	'assets' => [
		'//extensions/wikia/Flags/styles/Flags.scss',
		'//extensions/wikia/Flags/styles/FlagsViewEditEntryPoint.scss',
	],
];

$config['flags_editform_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'skin' => ['oasis', 'monobook'],
	'assets' => [
		'//extensions/wikia/Flags/scripts/FlagsModal.js'
	]
];

$config['paid_asset_drop_desktop_js'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//extensions/wikia/PaidAssetDrop/js/run/desktop.run.js',
		'//extensions/wikia/PaidAssetDrop/js/paidAssetDrop.js'
	]
];

$config['special_email_admin_css'] = [
	'type' => AssetsManager::TYPE_SCSS,
	'assets' => [
		'//extensions/wikia/Email/styles/specialSendEmail.scss'
	]
];

$config['sitemap_page_css'] = array(
	'type' => AssetsManager::TYPE_SCSS,
	'skin' => [ 'oasis', 'wikiamobile' ],
	'assets' => [
		'//extensions/wikia/SitemapPage/styles/SitemapPage.scss',
	]
);

$config['template_draft'] = [
	'type' => AssetsManager::TYPE_JS,
	'assets' => [
		'//extensions/wikia/TemplateDraft/scripts/rightRailModule.js',
		'//extensions/wikia/TemplateDraft/scripts/templateDraft.run.js',
		'//extensions/wikia/TemplateDraft/scripts/templateDraftTracking.js'
	]
];
