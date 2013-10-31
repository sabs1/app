/*!
 * VisualEditor UserInterface IconButtonWidget class.
 *
 * @copyright 2011-2013 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * Creates an ve.ui.IconButtonWidget object.
 *
 * @class
 * @extends ve.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [icon] Symbolic name of icon
 * @cfg {string} [title=''] Title text
 */
ve.ui.IconButtonWidget = function VeUiIconButtonWidget( config ) {
	// Parent constructor
	ve.ui.Widget.call( this, config );

	// Events
	this.$.on( 'click', ve.bind( this.onClick, this ) );

	// Initialization
	this.$.addClass( 've-ui-iconButtonWidget' );
	if ( config.icon ) {
		this.setIcon( config.icon );
	}
	if ( config.title ) {
		this.$.attr( 'title', config.title );
	}
};

/* Inheritance */

ve.inheritClass( ve.ui.IconButtonWidget, ve.ui.Widget );

/* Events */

/**
 * @event click
 */

/* Static Properties */

ve.ui.IconButtonWidget.static.tagName = 'a';

/* Methods */

/**
 * Handles mouse click events.
 *
 * @method
 * @param {jQuery.Event} e Mouse click event
 * @emits click
 */
ve.ui.IconButtonWidget.prototype.onClick = function () {
	if ( !this.disabled ) {
		this.emit( 'click' );
	}
	return false;
};

/**
 * Sets the icon and removes any previously set icon.
 *
 * @method
 * @param {string} icon The symbolic name of the icon to set
 */
ve.ui.IconButtonWidget.prototype.setIcon = function ( icon ) {
	if ( !this.$.hasClass( 've-ui-icon-' + icon ) ) {
		this.$
			.removeClass( function ( index, classNames ) {
				return ( classNames.match(/\bve-ui-icon-\S+/g) || [] ).join(' ');
			})
			.addClass( 've-ui-icon-' + icon );
	}
};
