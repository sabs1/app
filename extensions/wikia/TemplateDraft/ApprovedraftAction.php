<?php
/**
 * Move draft page content to parent page
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 * @author Kamil Koterba <kamil@wikia-inc.com>
 */

class ApprovedraftAction extends FormlessAction {

	public function getName() {
		return 'approvedraft';
	}

	protected function getDescription() {
		return '';
	}

	public function onView() {
		$title = $this->getTitle();

		$redirectParams = wfArrayToCGI( array_diff_key(
			$this->getRequest()->getQueryValues(),
			[ 'title' => null, 'action' => null ]
		));

		$templateDraftHelper = new TemplateDraftHelper();

		if ( !$title->exists() ) {

			$this->addBannerNotificationMessage( 'templatedraft-approval-no-page-error' );
			$redirectTitle = $title;

		} elseif ( !$templateDraftHelper->isTitleDraft( $title ) ) {

			$this->addBannerNotificationMessage( 'templatedraft-approval-no-templatedraft-error' );
			$redirectTitle = $title;

		} else {

			$templateDraftHelper->approveDraft( $title );

			$redirectTitle = $title->getBaseText();
			$redirectTitle = Title::newFromText( $redirectTitle, $title->getNamespace() );

		}

		$this->getOutput()->redirect( $redirectTitle->getFullUrl( $redirectParams ) );
	}

	/**
	 * Show a friendly error message to a user after redirect
	 */
	private function addBannerNotificationMessage( $messageName ) {
		BannerNotificationsController::addConfirmation(
			wfMessage( $messageName )->escaped(),
			BannerNotificationsController::CONFIRMATION_ERROR
		);
	}

}
