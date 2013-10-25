var ThemeDesigner = {
	slideByDefaultWidth: 760,
	slideByItems: 5,
	isSliding: false,
	minWidthforDynamicBg: 1050,

	init: function() {
		'use strict';

		var that = this;
		// theme settings
		this.settings = window.themeSettings;

		// settings history
		this.history = window.themeHistory;

		$().log(this.history);

		// themes
		this.themes = window.themes;

		//$().log(ThemeDesigner, 'ThemeDesigner');


		// handle navigation clicks - switching between tabs
		$('#Navigation a').click(that.navigationClick);

		// handle "Save" and "Cancel" button clicks
		$('#Toolbar').find('.save').click(that.saveClick)
                    .end()
                    .find('.cancel').click(that.cancelClick);

		// init tabs
		this.themeTabInit();
		this.customizeTabInit();
		this.wordmarkTabInit();

		// click appropriate tab based on the settings
		if(this.settings.theme === 'custom') {
			$('#Navigation [rel="CustomizeTab"]').click();
		} else {
			$('#Navigation [rel="ThemeTab"]').click();
		}

		// init Tool Bar
		this.toolBarInit();

		this.applySettings(false, false);

		// init tooltips
		this.initTooltips();

		// Cashe selectors
		this.themeDesignerPicker = $('#ThemeDesignerPicker');
		this.previewFrame = $('#PreviewFrame');

		// iframe resizing
		$(window).resize($.proxy(this.resizeIframe, this)).resize();
	},

	initTooltips: function() {
		'use strict';

		var tooltipTimeout = 0;

		function setTooltipTimeout(elem) {
			tooltipTimeout = setTimeout(function() {
				elem.tooltip('hide');
			}, 300);
		}

		// This tooltip will not go away if you hover inside the tooltip
		$('.form-questionmark').tooltip({
			trigger: 'manual',
			placement: 'right'
		}).on('mouseenter', function() {
			clearTimeout(tooltipTimeout);
			$(this).tooltip('show');
		}).on('mouseleave', function() {
			var $this = $(this);
			setTooltipTimeout($this);
			$('.tooltip').mouseenter(function() {
				clearTimeout(tooltipTimeout);
			}).mouseleave(function() {
				$().log('mouse leaving');
				setTooltipTimeout($this);
			});
		});

	},

	themeTabInit: function() {
		'use strict';

		var slideBy = ThemeDesigner.slideByDefaultWidth,
			slideMax = -Math.floor($('#ThemeTab').find('.slider').find('ul').find('li').length /
				ThemeDesigner.slideByItems) *ThemeDesigner.slideByDefaultWidth;

		// click handler for next and previous arrows in theme slider
		$('#ThemeTab .previous, #ThemeTab .next').click(function(event) {
			event.preventDefault();
			if (!ThemeDesigner.isSliding) {
				var list = $('#ThemeTab .slider ul'),
					arrow = $(this),
					slideTo = null;

				// prevent disabled clicks
				if(arrow.hasClass('disabled')) {
					return;
				}

				ThemeDesigner.isSliding = true;
				// slide
				if (arrow.hasClass('previous')) {
					slideTo = parseInt(list.css('margin-left'), 10) + slideBy;
				} else {
					slideTo = parseInt(list.css('margin-left'), 10) - slideBy;
				}
				list.animate({marginLeft: slideTo}, 'slow', function() {
					ThemeDesigner.isSliding = false;
				});

				// calculate which buttons should be enabled
				if (slideTo === slideMax) {
					$('#ThemeTab .next').addClass('disabled');
					$('#ThemeTab .previous').removeClass('disabled');
				} else if (slideTo === 0) {
					$('#ThemeTab .next').removeClass('disabled');
					$('#ThemeTab .previous').addClass('disabled');
				} else {
					$('#ThemeTab .next, #ThemeTab .previous').removeClass('disabled');
				}
			}
		});

		// click handler for themes thumbnails
		$('#ThemeTab').find('.slider').find('li').click(function() {

			var targetObject = $(this);

			// highlight selected theme
			targetObject.parent().find('.selected').removeClass('selected').end().end().addClass('selected');

			ThemeDesigner.set('theme', targetObject.attr('data-theme'));
		});

		// select current theme
		$('#ThemeTab').find('[data-theme=' + ThemeDesigner.settings.theme + ']').addClass('selected');
	},

	customizeTabInit: function() {
		'use strict';

		$('#CustomizeTab').find('li').find('img[id*="color"]').click(function(event) {
			ThemeDesigner.showPicker(event, 'color');
		});
		$('#swatch-image-background').click(function(event) {
			ThemeDesigner.showPicker(event, 'image');
		});
		$('#tile-background').change(function() {
			ThemeDesigner.set('background-tiled', $(this).attr('checked') ? 'true' : 'false');
		});
		$('#fix-background').change(function() {
			ThemeDesigner.set('background-fixed', $(this).attr('checked') ? 'true' : 'false');
		});
		// TODO: Remove IF statement after fluid layout global release
		if (window.wgOasisResponsive) {
			$('#dynamic-background').change(function() {
				ThemeDesigner.set('background-dynamic', $(this).attr('checked') ? 'true' : 'false');
			});

			// Background cannot be tiled and dynamic at once
			if (ThemeDesigner.settings['background-tiled'] === 'true') {
				$('#dynamic-background').attr('disabled', true);
			} else if (ThemeDesigner.settings['background-dynamic'] === 'true') {
				$('#tile-background').attr('disabled', true);
			}

			if (ThemeDesigner.settings['color-body'] !== ThemeDesigner.settings['color-body-middle']) {
				$('#color-body-middle').attr('checked', true);
				$('#CustomizeTab').find('.color-body-middle').css('display', 'block');
			}
			$('#swatch-color-background-middle').css('background-color', ThemeDesigner.settings['color-body-middle']);

			// If background middle color is checked we are setting color from picker
			// otherwise we are setting color from background color
			$('#color-body-middle').change(function() {
				if ($(this).attr('checked')) {
					ThemeDesigner.set(
							'color-body-middle',
							ThemeDesigner.rgb2hex($('#swatch-color-background-middle').css('background-color'))
					);
					$('#CustomizeTab').find('.color-body-middle').css('display', 'block');
				} else {
					ThemeDesigner.set('color-body-middle', ThemeDesigner.settings['color-body']);
					$('#CustomizeTab').find('.color-body-middle').css('display', 'none');
				}
			});
		}

		// submit handler for uploading custom background image
		$('#BackgroundImageForm').submit(function(){
			$.AIM.submit(this, ThemeDesigner.backgroundImageUploadCallback);
		});

		var currentVal = ThemeDesigner.settings['page-opacity'],
			base = 70;
		$('#OpacitySlider').slider({
			value: 100 - ((base - currentVal) * (100 / (base - 100)) ),
			stop: function(e, ui) {
				var val = ui.value,
					wikiaNormalized = 100 - Math.round((val/100) * (100 - base));
				ThemeDesigner.set('page-opacity', wikiaNormalized);
			}
		});
	},

	wordmarkTabInit: function() {
		'use strict';

		// handle font family and font size menu change
		$('#wordmark-font').change(function() {
			ThemeDesigner.set('wordmark-font', $(this).val());
		});
		$('#wordmark-size').change(function() {
			ThemeDesigner.set('wordmark-font-size', $(this).val());
		});

		// handle wordmark editing
		$('#wordmark-edit').find('button').click(function(event) {
			event.preventDefault();
			var value = $('#wordmark-edit').find('input[type="text"]').val().trim();
			if (value.length > 0) {
				ThemeDesigner.set('wordmark-text', value);
			} else {
				$.getMessages('ThemeDesigner', function() {
					window.alert($.msg('themedesigner-wordmark-preview-error'));
				});
			}

		});

		//graphic wordmark clicking
		$('#WordmarkTab').find('.graphic').find('.preview').find('.wordmark').click(function() {
			ThemeDesigner.set('wordmark-type', 'graphic');
		});

		//grapic wordmark button
		$('#WordmarkTab').find('.graphic').find('.preview').find('a').click(function(event) {
			event.preventDefault();
			ThemeDesigner.set('wordmark-type', 'text');
			ThemeDesigner.set('wordmark-image-url', window.wgBlankImgUrl);

			// Can't use js to clear file input value so reseting form
			$('#WordMarkUploadForm')[0].reset();
		});

		// submit handler for uploading custom logo image
		$('#WordMarkUploadForm').submit(function() {
			$.AIM.submit(this, ThemeDesigner.wordmarkUploadCallback);
		});

        //remove favicon link
		$('#WordmarkTab').find('.favicon').find('.preview').find('a').click(function(event) {
			event.preventDefault();
			ThemeDesigner.set('favicon-image-url', window.wgBlankImgUrl);

			// Can't use js to clear file input value so reseting form
			$('#FaviconUploadForm')[0].reset();
		});

		// submit handler for uploading favicon image
		$('#FaviconUploadForm').submit(function() {
			$.AIM.submit(this, ThemeDesigner.faviconUploadCallback);
		});
	},

	wordmarkShield: function() {
		'use strict';

		var shield = $('#wordmark-shield'),
			parent;

		if (ThemeDesigner.settings['wordmark-type'] === 'graphic') {
			parent = shield.parent();

			shield
				.css({
					height: parent.outerHeight(true),
					width: parent.outerWidth(true)
					})
				.show();
		} else {
			shield.hide();
		}
	},

	toolBarInit: function() {
		'use strict';

		$('#Toolbar .history')
			.click(function() {
				$(this).find('ul').show();
			})
			.find('ul').mouseleave(function() {
				$(this).hide();
			})
			.find('li').click(ThemeDesigner.revertToPreviousTheme);
	},

	showPicker: function(event, type) {
		'use strict';

		$().log('running showPicker');
		ThemeDesigner.hidePicker();
		event.stopPropagation();
		var swatch = $(event.currentTarget),
			swatchName = event.currentTarget.className,
			swatches,
			duplicate,
			swatchNodes,
			expression,
			i,
			img,
			imgUrl;

		// check the type (color or image)
		if(type === 'color') {

			//add swatches from array
			swatchNodes = '';
			for (i = 0; i < ThemeDesigner.swatches[swatchName].length; i++) {
				swatchNodes += '<li style="background-color: #' + ThemeDesigner.swatches[swatchName][i] + ';"></li>';
			}
			this.themeDesignerPicker.children('.color').find('.swatches').append(swatchNodes);

			//add user color if different than swatches
			swatches = this.themeDesignerPicker.children('.color').find('.swatches');
			duplicate = false;

			swatches.find('li').each(function() {
				if(swatch.css('background-color') === $(this).css('background-color')) {
					duplicate = true;
					return false;
				}
			});

			if(!duplicate) {
				swatches.append('<li class="user" style="background-color: ' +
					swatch.css('background-color') + '"></li>');
			}

			// handle swatch clicking
			swatches.find('li').click(function() {
				ThemeDesigner.hidePicker();
				if (swatchName === 'color-body-middle') {
					$('#color-body-middle').attr('checked', true);
				} else if (swatchName === 'color-body' && !$('#color-body-middle').attr('checked')) {
					ThemeDesigner.set('color-body-middle', ThemeDesigner.rgb2hex($(this).css('background-color')));
				}
				ThemeDesigner.set(swatchName, ThemeDesigner.rgb2hex($(this).css('background-color')));
				ThemeDesigner.set('theme', 'custom');
			});

			//handle custom colors
			$('#ColorNameForm').submit(function(event) {
				event.preventDefault();

				var color = $.trim($('#color-name').val().toLowerCase());

				// was anything submitted?
				if (color === '' || color === $('#color-name').attr('placeholder')) {
					return;
				}

				// RT:70673 trim string
				//color = $.trim(color);

				// add hash if needed
				expression = /^[0-9a-f]{3,6}/i;
				if (expression.test(color)) {
					color = '#' + color;
				}

				// test color
				$('<div id="ColorTester"></div>').appendTo(document.body);
				try {
					$('#ColorTester').css('background-color', color);
				} catch(error) {

				}
				if ($('#ColorTester').css('background-color') === 'transparent' ||
					$('#ColorTester').css('background-color') === 'rgba(0, 0, 0, 0)') {
					return;
				}
				$('#ColorTester').remove();

				ThemeDesigner.hidePicker();
				ThemeDesigner.set(swatchName, ThemeDesigner.rgb2hex(color));
				ThemeDesigner.set('theme', 'custom');
			});

		} else if (type === 'image') {

			swatches = this.themeDesignerPicker.children('.image').find('.swatches');
			// add admin background
			if (ThemeDesigner.settings['user-background-image']) {
				$('<li class="user"><img src="' + ThemeDesigner.settings['user-background-image-thumb'] +
					'" data-image="' + ThemeDesigner.settings['user-background-image'] + '"></li>')
					.insertBefore(swatches.find('.no-image'));
			}

			// click handling
			this.themeDesignerPicker.children('.image').find('.swatches').find('li').click(function() {

				//set correct image
				if ($(this).attr('class') === 'no-image') {
					ThemeDesigner.set('background-image', '');
				} else {
					img = new Image();
					imgUrl = $(this).children('img').attr('data-image');

					img.onload = function() {
						if (img.width && img.height) {
							ThemeDesigner.set('background-image-width', img.width);
							ThemeDesigner.set('background-image-height', img.height);
							ThemeDesigner.set('background-image', imgUrl);
							ThemeDesigner.checkBgIsDynamic(img.width);
						}
					};
					img.src = imgUrl;
				}

				ThemeDesigner.hidePicker();
			});
		}

		// show picker
		this.themeDesignerPicker
			.css({
				top: swatch.offset().top + 10,
				left: swatch.offset().left + 10
			})
			.removeClass('color image')
			.addClass(type);

		// clicking away will close picker
		$('body').bind('click.picker', $.proxy(ThemeDesigner.hidePicker, this));
		this.themeDesignerPicker.click(function(event) {
			event.stopPropagation();
		});
	},

	hidePicker: function() {
		'use strict';

		$('body').unbind('.picker');
		$('#ColorNameForm').unbind();
		this.themeDesignerPicker
			.removeClass('color image')
			.find('.user').remove().end()
			.find('.color li').remove().end()
			.find('.image li').unbind('click');
		$('#color-name').val('').blur();
	},

	checkBgIsDynamic: function(width) {
		'use strict';

		// TODO: Remove IF statement after fluid layout global release
		if ( window.wgOasisResponsive ) {
			if ( width < ThemeDesigner.minWidthforDynamicBg ) {
				ThemeDesigner.disableDynamicBg();
			} else {
				$('#dynamic-background').attr('disabled', false);
			}
		}
	},

	disableDynamicBg: function() {
		'use strict';

		if ( $('#dynamic-background').attr('checked') ) {
			$('#dynamic-background').attr('checked', false);
			ThemeDesigner.set('background-dynamic', false);
		}
		$('#dynamic-background').attr('disabled', true);
	},

	/**
	 * @author: Inez Korczynski
	 */
	set: function(setting, newValue) {
		'use strict';

		$().log('Setting: "' + setting + '" to: "' + newValue + '"');

		ThemeDesigner.settings[setting] = newValue;

		if(setting === 'wordmark-image-name' || setting === 'background-image-name') {
			return;
		}

		if (setting === 'background-tiled') {
			if (newValue === 'true') {
				ThemeDesigner.previewFrame.contents().find('body').removeClass('background-not-tiled');
				$('#dynamic-background').attr('disabled', true);
			} else {
				ThemeDesigner.previewFrame.contents().find('body').addClass('background-not-tiled');
				$('#dynamic-background').attr('disabled', false);
			}
		}

		if (setting === 'background-fixed') {
			if (newValue === 'true') {
				ThemeDesigner.previewFrame.contents().find('body').addClass('background-fixed');
			} else {
				ThemeDesigner.previewFrame.contents().find('body').removeClass('background-fixed');
			}
		}

		if (setting === 'background-dynamic') {
			if (newValue === 'true') {
				ThemeDesigner.previewFrame.contents().find('body').addClass('background-dynamic');
				$('#tile-background').attr('disabled', true);
				$('#CustomizeTab').find('.wrap-middle-color').css('display', 'block');
			} else {
				ThemeDesigner.previewFrame.contents().find('body').removeClass('background-dynamic');
				$('#tile-background').attr('disabled', false);
				$('#CustomizeTab').find('.wrap-middle-color').css('display', 'none');
			}
		}

		var reloadCSS = false,
			updateSkinPreview = false;

		if(setting === 'theme' && newValue !== 'custom') {
			$.extend(ThemeDesigner.settings, ThemeDesigner.themes[newValue]);
			reloadCSS = true;
		}

		if(setting === 'color-body' || setting === 'color-body-middle' || setting === 'color-page' ||
			setting === 'color-buttons' || setting === 'color-links' || setting === 'background-image' ||
			setting === 'color-header' || setting === 'wordmark-font') {
			reloadCSS = true;
		}

		if(setting === 'wordmark-font-size' || setting === 'wordmark-text' || setting === 'wordmark-type' ||
			setting === 'page-opacity') {
			updateSkinPreview = true;
		}

		ThemeDesigner.applySettings(reloadCSS, updateSkinPreview);
	},

	/**
	 * Async callback for uploading wordmark image
	 *
	 * @author: Inez Korczynski
	 */
	wordmarkUploadCallback : {
		onComplete: function(response) {
			'use strict';

			var resp = JSON.parse(response);

			if(resp.errors && resp.errors.length > 0) {

				window.alert(resp.errors.join('\n'));

			} else {

				ThemeDesigner.set('wordmark-image-name', resp.wordmarkImageName);
				ThemeDesigner.set('wordmark-image-url', resp.wordmarkImageUrl);
				ThemeDesigner.set('wordmark-type', 'graphic');
			}
		}
	},

	/**
	 * Wordmark image upload button handler which cancel async request when image is not selected
	 *
	 * @author: Inez Korczynski
	 */
	wordmarkUpload: function(/*e*/) {
		'use strict';

		return $('#WordMarkUploadFile').val() !== '';

	},

	/**
	 * Favicon upload callback
	 */
	faviconUploadCallback : {
		onComplete: function(response) {
			'use strict';

			var resp = JSON.parse(response);

			if(resp.errors && resp.errors.length > 0) {

				window.alert(resp.errors.join('\n'));

			} else {
				ThemeDesigner.set('favicon-image-name', resp.faviconImageName);
				ThemeDesigner.set('favicon-image-url', resp.faviconImageUrl);
			}
		}
	},

	faviconUpload: function(/*e*/) {
		'use strict';
		// do validation
	},

	/**
	 * Async callback for uploading background image
	 *
	 * @author: Inez Korczynski
	 */
	backgroundImageUploadCallback : {
		onComplete: function(response) {
			'use strict';

			var resp = JSON.parse(response);
			$().log(resp);
			if(resp.errors && resp.errors.length > 0) {

				window.alert(resp.errors.join('\n'));

			} else {
				$('#backgroundImageUploadFile').val('');
				ThemeDesigner.hidePicker();

				ThemeDesigner.set('user-background-image', resp.backgroundImageUrl);
				ThemeDesigner.set('user-background-image-thumb', resp.backgroundImageThumb);

				ThemeDesigner.set('theme', 'custom');
				ThemeDesigner.set('background-image-name', resp.backgroundImageName);
				ThemeDesigner.set('background-image-width', resp.backgroundImageWidth);
				ThemeDesigner.set('background-image-height', resp.backgroundImageHeight);

				// This should be last, it triggers a CSS reload
				ThemeDesigner.set('background-image', resp.backgroundImageUrl);
				ThemeDesigner.checkBgIsDynamic( resp.backgroundImageWidth );
			}
		}
	},

	/**
	 * Background image upload button handler which cancel async request when image is not selected
	 *
	 * @author: Inez Korczynski
	 */
	backgroundImageUpload: function(/*e*/) {
		'use strict';

		return $('#BackgroundImageForm').find('input[type="file"]').val() !== '';

	},


	revertToPreviousTheme: function(event) {
		'use strict';

		event.preventDefault();
		event.stopPropagation();
		ThemeDesigner.settings = ThemeDesigner.history[$(this).index()].settings;

		$().log(ThemeDesigner.settings);

		ThemeDesigner.applySettings(true, true);
	},

	cancelClick: function(event) {
		'use strict';

		event.preventDefault();
		document.location = window.returnTo;
	},

	saveClick: function(event) {
		'use strict';

		event.preventDefault();
		$(event.target).attr('disabled', true);
		ThemeDesigner.save();
	},

	save: function() {
		'use strict';

		$().log(ThemeDesigner.settings, 'ThemeDesigner');

		// send current settings to backend

		$.nirvana.sendRequest({
			controller: 'ThemeDesigner',
			method: 'SaveSettings',
			data: {
				settings: ThemeDesigner.settings
			},
			callback: function(/*data*/) {
				// BugId:1349
				ThemeDesigner.purgeReturnToPage(function() {
					if (window.returnTo) {
						// redirect to article from which ThemeDesigner was triggered
						document.location = window.returnTo;
					}
				});
			}
		});
	},

	navigationClick: function(event) {
		'use strict';

		event.preventDefault();

		var clickedLink = $(this),
			command = clickedLink.attr('rel');

		//select the correct tab
		clickedLink.parent().addClass('selected').siblings().removeClass('selected');
		//show the correct panel
		$('#' + command).show().siblings('section').hide();

		//hide wordmark text side if necessary
		if (command === 'WordmarkTab') {
			ThemeDesigner.wordmarkShield();
		}
	},

	resizeIframe: function() {
		'use strict';

		this.previewFrame.css('height', $(window).height() - $('#Designer').height());
	},

	history: false,
	settings: false,
	themes: false,

	applySettings: function(reloadCSS, updateSkinPreview) {
		'use strict';

		$().log('applySettings');

		var file, theme, settingsToLoad, wordmark;

		/*** Theme Tab ***/
		if(ThemeDesigner.settings.theme === 'custom') {
			$('#ThemeTab').find('.slider').find('.selected').removeClass('selected');
		}

		/*** Customize Tab ***/
		// color swatches
		$('#swatch-color-background').css('background-color', ThemeDesigner.settings['color-body']);
		$('#swatch-color-buttons').css('background-color', ThemeDesigner.settings['color-buttons']);
		$('#swatch-color-links').css('background-color', ThemeDesigner.settings['color-links']);
		$('#swatch-color-page').css('background-color', ThemeDesigner.settings['color-page']);
		$('#swatch-color-header').css('background-color', ThemeDesigner.settings['color-header']);

		if (ThemeDesigner.settings['background-image'] === '') {
			//no background image
			$('#swatch-image-background').attr('src', window.wgBlankImgUrl);
			// TODO: Remove IF statement after fluid layout global release
			if ( window.wgOasisResponsive ) {
				ThemeDesigner.disableDynamicBg();
			}
		} else if (ThemeDesigner.settings['background-image'].indexOf('images/themes') > 0) {
			//wikia background image
			file = ThemeDesigner.settings['background-image'].substring(ThemeDesigner.settings['background-image']
				.lastIndexOf('/') + 1);
			theme = file.substr(0, file.length - 4);
			$('#swatch-image-background').attr('src', window.wgExtensionsPath + '/wikia/ThemeDesigner/images/' +
				theme + '_swatch.jpg');
		} else {
			//admin background image
			$('#swatch-image-background').attr('src', ThemeDesigner.settings['user-background-image-thumb']);
		}

		$('#tile-background').attr('checked', ThemeDesigner.settings['background-tiled'] === 'true');
		$('#fix-background').attr('checked', ThemeDesigner.settings['background-fixed'] === 'true');

		// TODO: Remove IF statement after fluid layout global release
		if (window.wgOasisResponsive) {
			$('#dynamic-background').attr('checked', ThemeDesigner.settings['background-dynamic'] === 'true');

			if ($('#color-body-middle').attr('checked')) {
				$('#swatch-color-background-middle').css('background-color',
					ThemeDesigner.settings['color-body-middle']);
			}

			if (ThemeDesigner.settings['background-dynamic'] === 'false') {
				$('#CustomizeTab').find('.wrap-middle-color').css('display', 'none');
			}
		}

		/*** Wordmark Tab ***/
		// style wordmark preview
		$('#wordmark').removeClass().addClass(ThemeDesigner.settings['wordmark-font'])
			.addClass(ThemeDesigner.settings['wordmark-font-size']).html(ThemeDesigner.settings['wordmark-text']);

		// populate wordmark editor
		$('#wordmark-edit').find('input[type="text"]')
			.val(ThemeDesigner.settings['wordmark-text']);

		// select current font
		$('#wordmark-font').find('[value="' + ThemeDesigner.settings['wordmark-font'] + '"]')
			.attr('selected', 'selected');

		// select current size
		$('#wordmark-size').find('[value="' + ThemeDesigner.settings['wordmark-font-size'] + '"]')
			.attr('selected', 'selected');

		// wordmark image
		$('#WordmarkTab .graphic .wordmark').attr('src', ThemeDesigner.settings['wordmark-image-url']);


		if (ThemeDesigner.settings['wordmark-type'] === 'graphic') {
			$('#WordmarkTab').find('.graphic')
				.find('.preview').addClass('active')
				.find('.wordmark').addClass('selected');
			ThemeDesigner.wordmarkShield();
		} else {
			$('#WordmarkTab').find('.graphic')
				.find('.preview').removeClass('active')
				.find('.wordmark').removeClass('selected');
			ThemeDesigner.wordmarkShield();
		}

		// favicon image
		$('#WordmarkTab').find('.favicon').find('.preview').find('img').attr('src',
			ThemeDesigner.settings['favicon-image-url']);

		if(ThemeDesigner.settings['favicon-image-url'] === window.wgBlankImgUrl){
			$('#WordmarkTab').find('.favicon').find('.preview').removeClass('active');
		} else {
			$('#WordmarkTab').find('.favicon').find('.preview').addClass('active');
		}

		if(reloadCSS) {
			$().log('applySettings, reloadCSS');

			settingsToLoad = $.extend({}, ThemeDesigner.settings, window.applicationThemeSettings);

			document.getElementById('PreviewFrame').contentWindow.ThemeDesignerPreview.loadSASS(settingsToLoad);
		}

		if(updateSkinPreview) {
			$().log('applySettings, updateSkinPreview');

			wordmark = this.previewFrame.contents().find('#WikiHeader').find('.wordmark');

			if (ThemeDesigner.settings['wordmark-type'] === 'text') {
				wordmark.removeClass().addClass('wordmark').addClass(ThemeDesigner.settings['wordmark-font-size'])
					.find('a').text(ThemeDesigner.settings['wordmark-text']);
			} else if (ThemeDesigner.settings['wordmark-type'] === 'graphic') {
				wordmark.addClass('graphic')
					.find('a').html('').append('<img src="' + ThemeDesigner.settings['wordmark-image-url'] + '">');
			}

			this.previewFrame.contents().find('#WikiaPageBackground')
				.css('opacity', ThemeDesigner.settings['page-opacity'] / 100);

			if (ThemeDesigner.settings['page-opacity'] < 100) {
				this.previewFrame.contents().find('#WikiHeader .shadow-mask').hide();
			} else {
				this.previewFrame.contents().find('#WikiHeader .shadow-mask').show();
			}
		}
	},

	/**
	 * Purges the page from which user has triggered Theme Designer
	 */
	purgeReturnToPage: function(callback) {
		'use strict';

		if (!window.returnTo) {
			return;
		}

		$.post(window.returnTo, {
			action:'purge'
		}, function() {
			$().log('URL "' + window.returnTo + '" has been purged', 'ThemeDesigner');

			if (typeof callback === 'function') {
				callback();
			}
		});
	},

	/**
	 * Converts from rgb(255, 255, 255) to #fff
	 *
	 * Copied here from WikiaPhotoGallery.js
	 */
	rgb2hex: function(rgb) {
		'use strict';

		function hex(x) {
			return ('0' + parseInt(x, 10).toString(16)).slice(-2);
		}

		var components = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

		if (components) {
			return '#' + hex(components[1]) + hex(components[2]) + hex(components[3]);
		} else {
			//not an rgb color, probably an hex value has been passed, return it
			return rgb;
		}
	},

	swatches: {
		'color-body': [
			'f9ebc3',
			'ede5dd',
			'f7e1d3',
			'dfdbc3',
			'fbe300',
			'ffbf99',
			'ffbf99',
			'fdc355',
			'cdbd89',
			'd5a593',
			'a37719',
			'836d35',
			'776b41',
			'f14700',
			'dd3509',
			'a34111',
			'7b3b09',
			'4f4341',
			'454545',
			'611d03',
			'891100',
			'71130f',
			'ebfffb',
			'ebf1f5',
			'f5ebf5',
			'e7f3d1',
			'bde9fd',
			'dfbddd',
			'c3d167',
			'a5b5c5',
			'6599ff',
			'6b93b1',
			'978f33',
			'53835d',
			'7f6f9f',
			'd335f7',
			'337700',
			'006baf',
			'2b53b5',
			'2d2b17',
			'003715',
			'012d59',
			'6f017b',
			'790145',
			'ffffff',
			'f1f1f1',
			'ebebeb',
			'000000'
		],
		'color-body-middle':[
			'f9ebc3',
			'ede5dd',
			'f7e1d3',
			'dfdbc3',
			'fbe300',
			'ffbf99',
			'ffbf99',
			'fdc355',
			'cdbd89',
			'd5a593',
			'a37719',
			'836d35',
			'776b41',
			'f14700',
			'dd3509',
			'a34111',
			'7b3b09',
			'4f4341',
			'454545',
			'611d03',
			'891100',
			'71130f',
			'ebfffb',
			'ebf1f5',
			'f5ebf5',
			'e7f3d1',
			'bde9fd',
			'dfbddd',
			'c3d167',
			'a5b5c5',
			'6599ff',
			'6b93b1',
			'978f33',
			'53835d',
			'7f6f9f',
			'd335f7',
			'337700',
			'006baf',
			'2b53b5',
			'2d2b17',
			'003715',
			'012d59',
			'6f017b',
			'790145',
			'ffffff',
			'f1f1f1',
			'ebebeb',
			'000000'
		],
		'color-buttons': [
			'fec356',
			'6699ff',
			'6c93b1',
			'a47719',
			'846d35',
			'786c42',
			'f14800',
			'337800',
			'006cb0',
			'dd360a',
			'a34112',
			'474646',
			'7b3b0a',
			'4f4341',
			'0038d8',
			'2d2c18',
			'611e03',
			'003816',
			'891100',
			'012e59',
			'721410',
			'6f027c',
			'7a0146'
		],
		'color-links': [
			'fce300',
			'fec356',
			'c4d167',
			'6699ff',
			'6c93b1',
			'a47719',
			'54845e',
			'337800',
			'006cb0',
			'0148c2',
			'6f027c',
			'ffffff'
		],
		'color-page': [
			'ebf2f5',
			'e7f4d2',
			'f5ebf5',
			'f9ecc3',
			'eee5de',
			'f7e1d4',
			'd4e6f7',
			'dfdbc3',
			'dfbddd',
			'cebe8a',
			'a5b5c6',
			'474646',
			'2d2c18',
			'611e03',
			'012e59',
			'ffffff',
			'f2f2f2',
			'ebebeb',
			'000000'
		],
		'color-header': [
			'D09632',
			'DD4702',
			'2B53B5',
			'3A5766',
			'285F00',
			'4A4612',
			'8F3000',
			'A301B4',
			'6D0D00',
			'002266',
			'580062',
			'808080'
		]
	}

};

$(function() {
	'use strict';

	ThemeDesigner.init();
});
