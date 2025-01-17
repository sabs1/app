<?php
/**
 * Implements Special:Contributions
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * Special:Contributions, show user contributions in a paged list
 *
 * @ingroup SpecialPage
 */

class SpecialContributions extends SpecialPage {

	protected $opts;

	public function __construct() {
		parent::__construct( 'Contributions' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		$this->opts = array();
		$request = $this->getRequest();

		if ( $par == 'newbies' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} elseif ( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		// check for radiobox
		if ( $request->getVal( 'contribs' ) == 'newbie' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} else {
			$this->opts['contribs'] = 'user';
		}

		$this->opts['deletedOnly'] = $request->getBool( 'deletedOnly' );

		if ( !strlen( $target ) ) {
			$out->addHTML( $this->getForm() );
			return;
		}

		$user = $this->getUser();

		$this->opts['limit'] = $request->getInt( 'limit', $user->getGlobalPreference( 'rclimit' ) );
		$this->opts['target'] = $target;
		$this->opts['topOnly'] = $request->getBool( 'topOnly' );

		$nt = Title::makeTitleSafe( NS_USER, $target );
		if ( !$nt ) {
			$out->addHTML( $this->getForm() );
			return;
		}
		$userObj = User::newFromName( $nt->getText(), false );
		if ( !$userObj ) {
			$out->addHTML( $this->getForm() );
			return;
		}
		$id = $userObj->getID();

		if ( $this->opts['contribs'] != 'newbie' ) {
			$target = $nt->getText();
			$out->addSubtitle( $this->contributionsSub( $userObj ) );
			$out->setHTMLTitle( $this->msg( 'pagetitle', $this->msg( 'contributions-title', $target )->plain() ) );
			$this->getSkin()->setRelevantUser( $userObj );
		} else {
			$out->addSubtitle( $this->msg( 'sp-contributions-newbies-sub' ) );
			$out->setHTMLTitle( $this->msg( 'pagetitle', $this->msg( 'sp-contributions-newbies-title' )->plain() ) );
		}

		if ( ( $ns = $request->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
			$this->opts['namespace'] = intval( $ns );
		} else {
			$this->opts['namespace'] = '';
		}

		$this->opts['associated'] = $request->getBool( 'associated' );

		$this->opts['nsInvert'] = (bool) $request->getVal( 'nsInvert' );

		$this->opts['tagfilter'] = (string) $request->getVal( 'tagfilter' );

		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page
		if ( $user->isAllowed( 'markbotedits' ) && $request->getBool( 'bot' ) ) {
			$this->opts['bot'] = '1';
		}

		$skip = $request->getText( 'offset' ) || $request->getText( 'dir' ) == 'prev';
		# Offset overrides year/month selection
		if ( $skip ) {
			$this->opts['year'] = '';
			$this->opts['month'] = '';
		} else {
			$this->opts['year'] = $request->getIntOrNull( 'year' );
			$this->opts['month'] = $request->getIntOrNull( 'month' );
		}

		$feedType = $request->getVal( 'feed' );
		if ( $feedType ) {
			// Maintain some level of backwards compatability
			// If people request feeds using the old parameters, redirect to API
			$apiParams = array(
				'action' => 'feedcontributions',
				'feedformat' => $feedType,
				'user' => $target,
			);
			if ( $this->opts['topOnly'] ) {
				$apiParams['toponly'] = true;
			}
			if ( $this->opts['deletedOnly'] ) {
				$apiParams['deletedonly'] = true;
			}
			if ( $this->opts['tagfilter'] !== '' ) {
				$apiParams['tagfilter'] = $this->opts['tagfilter'];
			}
			if ( $this->opts['namespace'] !== '' ) {
				$apiParams['namespace'] = $this->opts['namespace'];
			}
			if ( $this->opts['year'] !== null ) {
				$apiParams['year'] = $this->opts['year'];
			}
			if ( $this->opts['month'] !== null ) {
				$apiParams['month'] = $this->opts['month'];
			}

			$url = wfScript( 'api' ) . '?' . wfArrayToCGI( $apiParams );

			$out->redirect( $url, '301' );
			return;
		}

		// Add RSS/atom links
		$this->addFeedLinks( array( 'action' => 'feedcontributions', 'user' => $target ) );

		if ( wfRunHooks( 'SpecialContributionsBeforeMainOutput', array( $id ) ) ) {

			$out->addHTML( $this->getForm() );

			$pager = new ContribsPager( $this->getContext(), array(
				'target' => $target,
				'contribs' => $this->opts['contribs'],
				'namespace' => $this->opts['namespace'],
				'year' => $this->opts['year'],
				'month' => $this->opts['month'],
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
				'nsInvert' => $this->opts['nsInvert'],
				'associated' => $this->opts['associated'],
			) );
			if ( !$pager->getNumRows() ) {
				$out->addWikiMsg( 'nocontribs', $target );
			} else {
				# Show a message about slave lag, if applicable
				$lag = wfGetLB()->safeGetLag( $pager->getDatabase() );
				if ( $lag > 0 )
					$out->showLagWarning( $lag );

				$out->addHTML(
					'<p>' . $pager->getNavigationBar() . '</p>' .
					$pager->getBody() .
					'<p>' . $pager->getNavigationBar() . '</p>'
				);
			}
			$out->preventClickjacking( $pager->getPreventClickjacking() );

			# Show the appropriate "footer" message - WHOIS tools, etc.
			if ( $this->opts['contribs'] != 'newbie' ) {
				$message = 'sp-contributions-footer';
				if ( IP::isIPAddress( $target ) ) {
					$message = 'sp-contributions-footer-anon';
				} else {
					if ( $userObj->isAnon() ) {
						// No message for non-existing users
						return;
					}
				}

				if ( !$this->msg( $message, $target )->isDisabled() ) {
					$out->wrapWikiMsg(
						"<div class='mw-contributions-footer'>\n$1\n</div>",
						array( $message, $target ) );
				}
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param $userObj User object for the target
	 * @return String: appropriately-escaped HTML to be output literally
	 * @todo FIXME: Almost the same as getSubTitle in SpecialDeletedContributions.php. Could be combined.
	 */
	protected function contributionsSub( $userObj ) {
		if ( $userObj->isAnon() ) {
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = Linker::link( $userObj->getUserPage(), htmlspecialchars( $userObj->getName() ) );
		}
		$nt = $userObj->getUserPage();
		$talk = $userObj->getTalkPage();
		if ( $talk ) {
			$tools = $this->getUserLinks( $nt, $talk, $userObj );
			$links = $this->getLanguage()->pipeList( $tools );

			// Show a note if the user is blocked and display the last block log entry.
			if ( $userObj->isBlocked() ) {
				$out = $this->getOutput(); // showLogExtract() wants first parameter by reference
				//If user is blocked globally we do not show local log extract as it doesn't contain information about this block
				if ( wfRunHooks('ContributionsLogEventsList', array( $out, $userObj) ) ) {
					LogEventsList::showLogExtract(
						$out,
						'block',
						$nt,
						'',
						array(
							'lim' => 1,
							'showIfEmpty' => false,
							'msgKey' => array(
								$userObj->isAnon() ?
									'sp-contributions-blocked-notice-anon' :
									'sp-contributions-blocked-notice',
								$userObj->getName() # Support GENDER in 'sp-contributions-blocked-notice'
							),
							'offset' => '' # don't use WebRequest parameter offset
						)
					);
				}
			}
		}

		// Old message 'contribsub' had one parameter, but that doesn't work for
		// languages that want to put the "for" bit right after $user but before
		// $links.  If 'contribsub' is around, use it for reverse compatibility,
		// otherwise use 'contribsub2'.
		$oldMsg = $this->msg( 'contribsub' );
		if ( $oldMsg->exists() ) {
			return $oldMsg->rawParams( "$user ($links)" );
		} else {
			return $this->msg( 'contribsub2' )->rawParams( $user, $links );
		}
	}

	/**
	 * Links to different places.
	 * @param $userpage Title: Target user page
	 * @param $talkpage Title: Talk page
	 * @param $target User: Target user object
	 * @return array
	 */
	public function getUserLinks( Title $userpage, Title $talkpage, User $target ) {

		$id = $target->getId();
		$username = $target->getName();

		$tools[] = Linker::link( $talkpage, $this->msg( 'sp-contributions-talk' )->escaped() );

		if ( ( $id !== null ) || ( $id === null && IP::isIPAddress( $username ) ) ) {
			if ( $this->getUser()->isAllowed( 'block' ) ) { # Block / Change block / Unblock links
				if ( $target->isBlocked() ) {
					$tools[] = Linker::linkKnown( # Change block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$this->msg( 'change-blocklink' )->escaped()
					);
					$tools[] = Linker::linkKnown( # Unblock link
						SpecialPage::getTitleFor( 'Unblock', $username ),
						$this->msg( 'unblocklink' )->escaped()
					);
				} else { # User is not blocked
					$tools[] = Linker::linkKnown( # Block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$this->msg( 'blocklink' )->escaped()
					);
				}
			}
			# Block log link
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log', 'block' ),
				$this->msg( 'sp-contributions-blocklog' )->escaped(),
				array(),
				array(
					'page' => $userpage->getPrefixedText()
				)
			);
		}
		# Uploads
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Listfiles', $username ),
			$this->msg( 'sp-contributions-uploads' )->escaped()
		);

		# Other logs link
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Log', $username ),
			$this->msg( 'sp-contributions-logs' )->escaped()
		);

		# Add link to deleted user contributions for priviledged users
		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'DeletedContributions', $username ),
				$this->msg( 'sp-contributions-deleted' )->escaped()
			);
		}

		# Add a link to change user rights for privileged users
		$userrightsPage = new UserrightsPage();
		$userrightsPage->setContext( $this->getContext() );
		if ( $id !== null && $userrightsPage->userCanChangeRights( $target ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userrights', $username ),
				$this->msg( 'sp-contributions-userrights' )->escaped()
			);
		}

		wfRunHooks( 'ContributionsToolLinks', array( $id, $userpage, &$tools ) );
		return $tools;
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @return String: HTML fragment
	 */
	protected function getForm() {
		global $wgScript;

		$this->opts['title'] = $this->getTitle()->getPrefixedText();
		if ( !isset( $this->opts['target'] ) ) {
			$this->opts['target'] = '';
		} else {
			$this->opts['target'] = str_replace( '_' , ' ' , $this->opts['target'] );
		}

		if ( !isset( $this->opts['namespace'] ) ) {
			$this->opts['namespace'] = '';
		}

		if ( !isset( $this->opts['nsInvert'] ) ) {
			$this->opts['nsInvert'] = '';
		}

		if ( !isset( $this->opts['associated'] ) ) {
			$this->opts['associated'] = false;
		}

		if ( !isset( $this->opts['contribs'] ) ) {
			$this->opts['contribs'] = 'user';
		}

		if ( !isset( $this->opts['year'] ) ) {
			$this->opts['year'] = '';
		}

		if ( !isset( $this->opts['month'] ) ) {
			$this->opts['month'] = '';
		}

		if ( $this->opts['contribs'] == 'newbie' ) {
			$this->opts['target'] = '';
		}

		if ( !isset( $this->opts['tagfilter'] ) ) {
			$this->opts['tagfilter'] = '';
		}

		if ( !isset( $this->opts['topOnly'] ) ) {
			$this->opts['topOnly'] = false;
		}

		$form = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'class' => 'mw-contributions-form' ) );

		# Add hidden params for tracking except for parameters in $skipParameters
		$skipParameters = array( 'namespace', 'nsInvert', 'deletedOnly', 'target', 'contribs', 'year', 'month', 'topOnly', 'associated' );
		foreach ( $this->opts as $name => $value ) {
			if ( in_array( $name, $skipParameters ) ) {
				continue;
			}
			$form .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $this->opts['tagfilter'] );

		if ( $tagFilter ) {
			$filterSelection =
				Xml::tags( 'td', array( 'class' => 'mw-label' ), array_shift( $tagFilter ) ) .
				Xml::tags( 'td', array( 'class' => 'mw-input' ), implode( '&#160', $tagFilter ) );
		} else {
			$filterSelection = Xml::tags( 'td', array( 'colspan' => 2 ), '' );
		}

		$targetSelection = Xml::tags( 'td', array( 'colspan' => 2 ),
			Xml::radioLabel(
				$this->msg( 'sp-contributions-newbies' )->text(),
				'contribs',
				'newbie' ,
				'newbie',
				$this->opts['contribs'] == 'newbie',
				array( 'class' => 'mw-input' )
			) . '<br />' .
			Xml::radioLabel(
				$this->msg( 'sp-contributions-username' )->text(),
				'contribs',
				'user',
				'user',
				$this->opts['contribs'] == 'user',
				array( 'class' => 'mw-input' )
			) . ' ' .
			Html::input(
				'target',
				$this->opts['target'],
				'text',
				array( 'size' => '20', 'required' => '', 'class' => 'mw-input' ) +
					( $this->opts['target'] ? array() : array( 'autofocus' )
				)
			) . ' '
		) ;

		$namespaceSelection =
			Xml::tags( 'td', array( 'class' => 'mw-label' ),
				Xml::label(
					$this->msg( 'namespace' )->text(),
					'namespace',
					''
				)
			) .
			Xml::tags( 'td', null,
				Xml::namespaceSelector( $this->opts['namespace'], '' ) . '&#160;' .
				Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
					Xml::checkLabel(
						$this->msg( 'invert' )->text(),
						'nsInvert',
						'nsInvert',
						$this->opts['nsInvert'],
						array( 'title' => $this->msg( 'tooltip-invert' )->text(), 'class' => 'mw-input' )
					) . '&#160;'
				) .
				Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
					Xml::checkLabel(
						$this->msg( 'namespace_association' )->text(),
						'associated',
						'associated',
						$this->opts['associated'],
						array( 'title' => $this->msg( 'tooltip-namespace_association' )->text(), 'class' => 'mw-input' )
					) . '&#160;'
				)
			) ;

		$extraOptions = Xml::tags( 'td', array( 'colspan' => 2 ),
			Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
				Xml::checkLabel(
					$this->msg( 'history-show-deleted' )->text(),
					'deletedOnly',
					'mw-show-deleted-only',
					$this->opts['deletedOnly'],
					array( 'class' => 'mw-input' )
				)
			) .
			Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
				Xml::checkLabel(
					$this->msg( 'sp-contributions-toponly' )->text(),
					'topOnly',
					'mw-show-top-only',
					$this->opts['topOnly'],
					array( 'class' => 'mw-input' )
				)
			)
		) ;

		$dateSelectionAndSubmit = Xml::tags( 'td', array( 'colspan' => 2 ),
			Xml::dateMenu(
				$this->opts['year'],
				$this->opts['month']
			) . ' ' .
			Xml::submitButton(
				$this->msg( 'sp-contributions-submit' )->text(),
				array( 'class' => 'mw-submit' )
			)
		) ;

		$form .=
			Xml::fieldset( $this->msg( 'sp-contributions-search' )->text() ) .
			Xml::openElement( 'table', array( 'class' => 'mw-contributions-table' ) ) .
				Xml::openElement( 'tr' ) .
					$targetSelection .
				Xml::closeElement( 'tr' ) .
				Xml::openElement( 'tr' ) .
					$namespaceSelection .
				Xml::closeElement( 'tr' ) .
				Xml::openElement( 'tr' ) .
					$filterSelection .
				Xml::closeElement( 'tr' ) .
				Xml::openElement( 'tr' ) .
					$extraOptions .
				Xml::closeElement( 'tr' ) .
				Xml::openElement( 'tr' ) .
					$dateSelectionAndSubmit .
				Xml::closeElement( 'tr' ) .
			Xml::closeElement( 'table' );

		$explain = $this->msg( 'sp-contributions-explain' );
		if ( $explain->exists() ) {
			$form .= "<p id='mw-sp-contributions-explain'>{$explain}</p>";
		}
		$form .= Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
		return $form;
	}
}

/**
 * Pager for Special:Contributions
 * @ingroup SpecialPage Pager
 */
class ContribsPager extends ReverseChronologicalPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;
	var $preventClickjacking = false;

	function __construct( IContextSource $context, array $options ) {
		parent::__construct( $context );

		$msgs = array( 'uctop', 'diff', 'newarticle', 'rollbacklink', 'diff', 'hist', 'rev-delundel', 'pipe-separator' );

		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->escaped();
		}

		$this->target = isset( $options['target'] ) ? $options['target'] : '';
		$this->contribs = isset( $options['contribs'] ) ? $options['contribs'] : 'users';
		$this->namespace = isset( $options['namespace'] ) ? $options['namespace'] : '';
		$this->tagFilter = isset( $options['tagfilter'] ) ? $options['tagfilter'] : false;
		$this->nsInvert = isset( $options['nsInvert'] ) ? $options['nsInvert'] : false;
		$this->associated = isset( $options['associated'] ) ? $options['associated'] : false;

		$this->deletedOnly = !empty( $options['deletedOnly'] );
		$this->topOnly = !empty( $options['topOnly'] );

		$year = isset( $options['year'] ) ? $options['year'] : false;
		$month = isset( $options['month'] ) ? $options['month'] : false;
		$this->getDateCond( $year, $month );

		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;
		return $query;
	}

	function getQueryInfo() {
		list( $tables, $index, $userCond, $join_cond ) = $this->getUserCond();

		$user = $this->getUser();
		$conds = array_merge( $userCond, $this->getNamespaceCond() );

		// Paranoia: avoid brute force searches (bug 17342)
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$conds[] = $this->mDb->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0';
		} elseif ( !$user->isAllowed( 'suppressrevision' ) ) {
			$conds[] = $this->mDb->bitAnd( 'rev_deleted', Revision::SUPPRESSED_USER ) .
				' != ' . Revision::SUPPRESSED_USER;
		}

		# Don't include orphaned revisions
		$join_cond['page'] = Revision::pageJoinCond();
		# Get the current user name for accounts
		$join_cond['user'] = Revision::userJoinCond();

		$queryInfo = array(
			'tables'     => $tables,
			'fields'     => array_merge(
				Revision::selectFields(),
				Revision::selectUserFields(),
				array( 'page_namespace', 'page_title', 'page_is_new',
					'page_latest', 'page_is_redirect', 'page_len' )
			),
			'conds'      => $conds,
			'options'    => array( 'USE INDEX' => array( 'revision' => $index ) ),
			'join_conds' => $join_cond
		);

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		wfRunHooks( 'ContribsPager::getQueryInfo', array( &$this, &$queryInfo ) );
		return $queryInfo;
	}

	function getUserCond() {
		$condition = array();
		$join_conds = array();
		$tables = array( 'revision', 'page', 'user' );
		if ( $this->contribs == 'newbie' ) {
			$tables[] = 'user_groups';
			$max = $this->mDb->selectField( 'user', 'max(user_id)', false, __METHOD__ );
			$condition[] = 'rev_user >' . (int)( $max - $max / 100 );
			$condition[] = 'ug_group IS NULL';
			$index = 'user_timestamp';
			# @todo FIXME: Other groups may have 'bot' rights
			$join_conds['user_groups'] = array( 'LEFT JOIN', "ug_user = rev_user AND ug_group = 'bot'" );
		} else {
			if ( IP::isIPAddress( $this->target ) ) {
				$condition['rev_user_text'] = $this->target;
				$index = 'usertext_timestamp';
			} else {
				$condition['rev_user'] = User::idFromName( $this->target );
				$index = 'user_timestamp';
			}
		}
		if ( $this->deletedOnly ) {
			$condition[] = "rev_deleted != '0'";
		}
		if ( $this->topOnly ) {
			$condition[] = "rev_id = page_latest";
		}
		return array( $tables, $index, $condition, $join_conds );
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			$selectedNS = $this->mDb->addQuotes( $this->namespace );
			$eq_op = $this->nsInvert ? '!=' : '=';
			$bool_op = $this->nsInvert ? 'AND' : 'OR';

			if ( !$this->associated ) {
				return array( "page_namespace $eq_op $selectedNS" );
			} else {
				$associatedNS = $this->mDb->addQuotes (
					MWNamespace::getAssociated( $this->namespace )
				);
				return array(
					"page_namespace $eq_op $selectedNS " .
					$bool_op .
					" page_namespace $eq_op $associatedNS"
				);
			}

		} else {
			return array();
		}
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function doBatchLookups() {
		$this->mResult->rewind();
		$revIds = array();
		foreach ( $this->mResult as $row ) {
			if( $row->rev_parent_id ) {
				$revIds[] = $row->rev_parent_id;
			}
		}
		$this->mParentLens = $this->getParentLengths( $revIds );
		$this->mResult->rewind(); // reset

		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		# Give some pointers to make (last) links
		foreach ( $this->mResult as $row ) {
			if ( $this->contribs === 'newbie' ) { // multiple users
				$batch->add( NS_USER, $row->user_name );
				$batch->add( NS_USER_TALK, $row->user_name );
			}
			$batch->add( $row->page_namespace, $row->page_title );
		}
		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 */
	private function getParentLengths( array $revIds ) {
		$revLens = array();
		if ( !$revIds ) {
			return $revLens; // empty
		}
		wfProfileIn( __METHOD__ );
		$res = $this->getDatabase()->select( 'revision',
			array( 'rev_id', 'rev_len' ),
			array( 'rev_id' => $revIds ),
			__METHOD__ );
		foreach ( $res as $row ) {
			$revLens[$row->rev_id] = $row->rev_len;
		}
		wfProfileOut( __METHOD__ );
		return $revLens;
	}

	/**
	 * @return string
	 */
	function getStartBody() {
		return "<ul>\n";
	}

	/**
	 * @return string
	 */
	function getEndBody() {
		return "</ul>\n";
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with roll-
	 * back privileges. The rollback link restores the most recent version that
	 * was not written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		$rev = new Revision( $row );
		$classes = array();

		$page = Title::newFromRow( $row );
		$link = Linker::link(
			$page,
			htmlspecialchars( $page->getPrefixedText() ),
			array(),
			$page->isRedirect() ? array( 'redirect' => 'no' ) : array()
		);
		# Mark current revisions
		$topmarktext = '';
		if ( $row->rev_id == $row->page_latest ) {
			$topmarktext .= '<span class="mw-uctop">' . $this->messages['uctop'] . '</span>';
			# Add rollback link
			if ( !$row->page_is_new && $page->quickUserCan( 'rollback' )
				&& $page->quickUserCan( 'edit' ) )
			{
				$this->preventClickjacking();
				$topmarktext .= ' ' . Linker::generateRollback( $rev );
			}
		}
		$user = $this->getUser();
		# Is there a visible previous revision?
		if ( $rev->userCan( Revision::DELETED_TEXT, $user ) && $rev->getParentId() !== 0 ) {
			$difftext = Linker::linkKnown(
				$page,
				$this->messages['diff'],
				array(),
				array(
					'diff' => 'prev',
					'oldid' => $row->rev_id
				)
			);
		} else {
			$difftext = $this->messages['diff'];
		}
		$histlink = Linker::linkKnown(
			$page,
			$this->messages['hist'],
			array(),
			array( 'action' => 'history' )
		);

		if ( $row->rev_parent_id === null ) {
			// For some reason rev_parent_id isn't populated for this row.
			// Its rumoured this is true on wikipedia for some revisions (bug 34922).
			// Next best thing is to have the total number of bytes.
			$chardiff = ' . . ' . Linker::formatRevisionSize( $row->rev_len ) . ' . . ';
		} else {
			$parentLen = isset( $this->mParentLens[$row->rev_parent_id] ) ? $this->mParentLens[$row->rev_parent_id] : 0;
			$chardiff = ' . . ' . ChangesList::showCharacterDifference(
					$parentLen, $row->rev_len ) . ' . . ';
		}

		$lang = $this->getLanguage();
		$comment = $lang->getDirMark() . Linker::revComment( $rev, false, true );
		$date = $lang->userTimeAndDate( $row->rev_timestamp, $user );
		if ( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$d = Linker::linkKnown(
				$page,
				htmlspecialchars( $date ),
				array(),
				array( 'oldid' => intval( $row->rev_id ) )
			);
		} else {
			$d = htmlspecialchars( $date );
		}
		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		}

		# Show user names for /newbies as there may be different users.
		# Note that we already excluded rows with hidden user names.
		if ( $this->contribs == 'newbie' ) {
			$userlink = ' . . ' . Linker::userLink( $rev->getUser(), $rev->getUserText() );
			$userlink .= ' ' . $this->msg( 'parentheses' )->rawParams(
				Linker::userTalkLink( $rev->getUser(), $rev->getUserText() ) )->escaped() . ' ';
		} else {
			$userlink = '';
		}

		if ( $rev->getParentId() === 0 ) {
			$nflag = ChangesList::flag( 'newpage' );
		} else {
			$nflag = '';
		}

		if ( $rev->isMinor() ) {
			$mflag = ChangesList::flag( 'minor' );
		} else {
			$mflag = '';
		}

		$del = Linker::getRevDeleteLink( $user, $rev, $page );
		if ( $del !== '' ) {
			$del .= ' ';
		}

		$diffHistLinks = '(' . $difftext . $this->messages['pipe-separator'] . $histlink . ')';
		$ret = "{$del}{$d} {$diffHistLinks}{$chardiff}{$nflag}{$mflag} {$link}{$userlink} {$comment} {$topmarktext}";

		# Denote if username is redacted for this edit
		if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
			$ret .= " <strong>" . $this->msg( 'rev-deleted-user-contribs' )->escaped() . "</strong>";
		}

		# Tags, if any.
		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow( $row->ts_tags, 'contributions' );
		$classes = array_merge( $classes, $newClasses );
		$ret .= " $tagSummary";

		// Let extensions add data
		wfRunHooks( 'ContributionsLineEnding', array( &$this, &$ret, $row ) );

		$classes = implode( ' ', $classes );
		$ret = "<li class=\"$classes\">$ret</li>\n";
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get the Database object in use
	 *
	 * @return DatabaseBase
	 */
	public function getDatabase() {
		return $this->mDb;
	}

	/**
	 * Overwrite Pager function and return a helpful comment
	 */
	function getSqlComment() {
		if ( $this->namespace || $this->deletedOnly ) {
			return 'contributions page filtered for namespace or RevisionDeleted edits'; // potentially slow, see CR r58153
		} else {
			return 'contributions page unfiltered';
		}
	}

	protected function preventClickjacking() {
		$this->preventClickjacking = true;
	}

	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}
}
