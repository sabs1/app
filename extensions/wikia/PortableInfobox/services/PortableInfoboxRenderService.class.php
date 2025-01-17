<?php

class PortableInfoboxRenderService extends WikiaService {
	const LOGGER_LABEL = 'portable-infobox-render-not-supported-type';
	const DESKTOP_THUMBNAIL_WIDTH = 270;
	const MOBILE_THUMBNAIL_WIDTH = 360;
	const MOBILE_TEMPLATE_POSTFIX = '-mobile';

	private $templates = [
		'wrapper' => 'PortableInfoboxWrapper.mustache',
		'title' => 'PortableInfoboxItemTitle.mustache',
		'header' => 'PortableInfoboxItemHeader.mustache',
		'image' => 'PortableInfoboxItemImage.mustache',
		'image-mobile' => 'PortableInfoboxItemImageMobile.mustache',
		'data' => 'PortableInfoboxItemData.mustache',
		'group' => 'PortableInfoboxItemGroup.mustache',
		'navigation' => 'PortableInfoboxItemNavigation.mustache'
	];
	private $templateEngine;

	function __construct() {
		$this->templateEngine = ( new Wikia\Template\MustacheEngine )
			->setPrefix( dirname( __FILE__ ) . '/../templates' );
	}

	/**
	 * renders infobox
	 *
	 * @param array $infoboxdata
	 * @return string - infobox HTML
	 */
	public function renderInfobox( array $infoboxdata, $theme, $layout ) {
		wfProfileIn( __METHOD__ );
		$infoboxHtmlContent = '';

		foreach ( $infoboxdata as $item ) {
			$data = $item[ 'data' ];
			$type = $item[ 'type' ];

			switch ( $type ) {
				case 'group':
					$infoboxHtmlContent .= $this->renderGroup( $data );
					break;
				case 'navigation':
					$infoboxHtmlContent .= $this->renderItem( 'navigation', $data );
					break;
				default:
					if ( $this->validateType( $type ) ) {
						$infoboxHtmlContent .= $this->renderItem( $type, $data );
					};
			}
		}

		if(!empty($infoboxHtmlContent)) {
			$output = $this->renderItem( 'wrapper', [ 'content' => $infoboxHtmlContent, 'theme' => $theme, 'layout' => $layout ] );
		} else {
			$output = '';
		}

		wfProfileOut( __METHOD__ );

		return $output;
	}

	/**
	 * renders group infobox component
	 *
	 * @param array $groupData
	 * @return string - group HTML markup
	 */
	private function renderGroup( $groupData ) {
		$groupHTMLContent = '';
		$dataItems = $groupData['value'];
		$layout = $groupData['layout'];

		foreach ( $dataItems as $item ) {
			$type = $item['type'];

			if ( $this->validateType( $type ) ) {
				$groupHTMLContent .= $this->renderItem( $type, $item['data'] );
			}
		}

		return $this->renderItem( 'group', [ 'content' => $groupHTMLContent, 'layout' => $layout] );
	}

	/**
	 * renders part of infobox
	 * If image element has invalid thumbnail, doesn't render this element at all.
	 *
	 * @param string $type
	 * @param array $data
	 * @return bool|string - HTML
	 */
	private function renderItem( $type, array $data ) {
		//TODO: with validated the performance of render Service and in the next phase we want to refactor it (make
		// it modular) While doing this we also need to move this logic to appropriate image render class
		if ( $type === 'image' ) {
			$data[ 'key' ] = urlencode( $data[ 'key' ] );
			$thumbnail = $this->getThumbnail( $data['name'] );

			if (!$thumbnail) {
				return false;
			}

			$data[ 'height' ] = $thumbnail->getHeight();
			$data[ 'width' ] = $thumbnail->getWidth();
			$data[ 'thumbnail' ] = $thumbnail->getUrl();

			if ( $this->isWikiaMobile() ) {
				$type = $type . self::MOBILE_TEMPLATE_POSTFIX;
			}
		}

		return $this->templateEngine->clearData()
			->setData( $data )
			->render( $this->templates[ $type ] );
	}

	/**
	 * @desc create a thumb of the image from file title
	 * @param $title
	 * @return bool|MediaTransformOutput
	 */
	protected function getThumbnail( $title ) {
		$file = \WikiaFileHelper::getFileFromTitle( $title );

		if ( $file ) {
			$width = $this->isWikiaMobile() ?
				self::MOBILE_THUMBNAIL_WIDTH :
				self::DESKTOP_THUMBNAIL_WIDTH;
			$thumb = $file->transform( ['width' => $width] );

			if (!is_null($thumb) && !$thumb->isError()) {
				return $thumb;
			}
		}
		return false;
	}

	/** 
	 * required for testing mobile template rendering
	 * @return bool
	 */
	protected function isWikiaMobile() {
		return F::app()->checkSkin( 'wikiamobile' );
	}

	/**
	 * check if item type is supported and logs unsupported types
	 *
	 * @param string $type - template type
	 * @return bool
	 */
	private function validateType( $type ) {
		$isValid = true;

		if ( !isset( $this->templates[ $type ] ) ) {
			Wikia\Logger\WikiaLogger::instance()->info( self::LOGGER_LABEL, [
				'type' => $type
			] );

			$isValid = false;
		}

		return $isValid;
	}
}
