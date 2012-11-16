<?php
/**
 * Controller to fetch information about articles
 *
 * @author Federico "Lox" Lucignano <federico@wikia-inc.com>
 */

class ArticlesApiController extends WikiaApiController {
	const ITEMS_PER_BATCH = 25;
	const CACHE_VERSION = 1;

	/**
	 * Get the top articles by pageviews optionally filtering by vertical namespace
	 *
	 * @requestParam string $namespaces [OPTIONAL] The name of the namespaces (e.g. Main, Category, File, etc.) to use as a filter, comma separated
	 * @requestParam integer $limit [OPTIONAL] The maximum number of results to fetch, defaults to 25
	 * @requestParam integer $batch [OPTIONAL] The batch/page index to retrieve, defaults to 1
	 *
	 * @responseParam array $items The list of top articles by pageviews matching the optional filtering
	 * @responseParam integer $total The total number of results
	 * @responseParam integer $currentBatch The index of the current batch/page
	 * @responseParam integer $batches The total number of batches/pages
	 * @responseParam integer $next The amount of items in the next batch/page
	 *
	 * @example http://glee.wikia.com/wikia.php?controller=ArticlesApi&method=getList&namespaces=Main,Category
	 */
	public function getList() {
		$this->wf->ProfileIn( __METHOD__ );

		$namespaces = $this->request->getVal( 'namespaces', null );
		$limit = $this->request->getInt( 'limit', self::ITEMS_PER_BATCH );
		$batch = $this->request->getInt( 'batch', 1 );

		if ( !empty( $namespaces ) ) {
			$namespaces = explode( ',', $namespaces );

			foreach ( $namespaces as &$n ) {
				$n = ( strtolower( $n ) === 'main' ) ? 0 : $this->wg->ContLang->getNsIndex( $n );
			}
		}

		$articles = DataMartService::getTopArticlesByPageview( $this->wg->CityId, null, $namespaces, false, 250 );
		$batches = array();
		$collection = array();

		if ( !empty( $articles ) ) {
			$ids = array();

			foreach ( array_keys( $articles ) as $i ) {
				$cache = $this->wg->Memc->get( $this->getArticleCacheKey( $i ) );

				if ( !is_array( $cache ) ) {
					$ids[] = $i;
				} else {
					$collection[$i] = $cache;
				}
			}

			if ( count( $ids) > 0 ) {
				$titles = Title::newFromIDs( $ids );

				if ( !empty( $titles ) ) {
					foreach ( $titles as $t ) {
						$ns = $t->getNamespace();
						$id =$t->getArticleID();
						$collection[$id] = array(
							'title' => $t->getText(),
							'url' => $t->getFullURL(),
							'namespace' => array(
								'id' => $t->getNamespace(),
								'text' => ( $ns === 0 ) ? 'Main' : $t->getNsText()
							)
						);

						$this->wg->Memc->set( $this->getArticleCacheKey( $id ), $collection[$id], 86400 );
					}
				}
			}

			$batches = $this->wf->PaginateArray( $collection, $limit, $batch );
		}

		$this->response->setCacheValidity(
			86400,
			86400,
			array(
				WikiaResponse::CACHE_TARGET_BROWSER,
				WikiaResponse::CACHE_TARGET_VARNISH
			)
		);

		foreach ( $batches as $name => $value ) {
			$this->response->setVal( $name, $value );
		}

		$this->wf->ProfileOut( __METHOD__ );
	}

	private function getArticleCacheKey( $id ) {
		return $this->wf->MemcKey( __CLASS__, self::CACHE_VERSION, 'article', $id );
	}
}