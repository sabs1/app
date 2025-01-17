<?php

/**
 * Email
 *
 * @author Garth Webb
 * @author James Sutterfield
 * @author Matt Klucsarits
 *
 * @date 2015-03-10
 */

$wgExtensionCredits['Email'][] = [
	'name' => 'Email',
	'author' => [
		'Garth Webb <garth@wikia-inc.com>',
		'James Sutterfield <james@wikia-inc.com>',
		'Matt Klucsarits <mattk@wikia-inc.com>',
	],
	'descriptionmsg' => 'email-desc',
	'url' => 'https://github.com/Wikia/app/tree/dev/extensions/wikia/Email'
];

$dir = dirname( __FILE__ ) . '/';

/**
 * classes
 */
$wgAutoloadClasses['Email\EmailController'] =  $dir . 'EmailController.class.php';
$wgAutoloadClasses['Email\Helper'] =  $dir . 'EmailHelper.class.php';
$wgAutoloadClasses['Email\ImageHelper'] =  $dir . 'EmailImageHelper.class.php';
$wgAutoloadClasses['Email\ControllerException'] =  $dir . 'EmailExceptions.class.php';
$wgAutoloadClasses['Email\Fatal'] =  $dir . 'EmailExceptions.class.php';
$wgAutoloadClasses['Email\Check'] =  $dir . 'EmailExceptions.class.php';
$wgAutoloadClasses['Email\Controller\ForgotPasswordController'] =  $dir . 'Controller/ForgotPasswordController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageEditedController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageProtectedController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageUnprotectedController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageRenamedController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\WatchedPageDeletedController'] =  $dir . 'Controller/WatchedPageController.class.php';
$wgAutoloadClasses['Email\Controller\ArticleCommentController'] =  $dir . 'Controller/CommentController.class.php';
$wgAutoloadClasses['Email\Controller\BlogCommentController'] =  $dir . 'Controller/CommentController.class.php';
$wgAutoloadClasses['Email\Controller\UserBlogPostController'] =  $dir . 'Controller/BlogPostController.class.php';
$wgAutoloadClasses['Email\Controller\ListBlogPostController'] =  $dir . 'Controller/BlogPostController.class.php';
$wgAutoloadClasses['Email\Controller\WallMessageController'] =  $dir . 'Controller/WallMessageController.class.php';
$wgAutoloadClasses['Email\Controller\FollowedWallMessageController'] =  $dir . 'Controller/WallMessageController.class.php';
$wgAutoloadClasses['Email\Controller\OwnWallMessageController'] =  $dir . 'Controller/WallMessageController.class.php';
$wgAutoloadClasses['Email\Controller\ReplyWallMessageController'] =  $dir . 'Controller/WallMessageController.class.php';
$wgAutoloadClasses['Email\Controller\ForumController'] =  $dir . 'Controller/ForumController.class.php';
$wgAutoloadClasses['Email\Controller\ReplyForumController'] =  $dir . 'Controller/ForumController.class.php';
$wgAutoloadClasses['Email\Controller\WeeklyDigestController'] =  $dir . 'Controller/WeeklyDigestController.class.php';
$wgAutoloadClasses['Email\Controller\AbstractEmailConfirmationController'] =  $dir . 'Controller/EmailConfirmationController.class.php';
$wgAutoloadClasses['Email\Controller\EmailConfirmationController'] =  $dir . 'Controller/EmailConfirmationController.class.php';
$wgAutoloadClasses['Email\Controller\EmailConfirmationReminderController'] =  $dir . 'Controller/EmailConfirmationController.class.php';
$wgAutoloadClasses['Email\Controller\ConfirmationChangedEmailController'] = $dir . 'Controller/EmailConfirmationController.class.php';
$wgAutoloadClasses['Email\Controller\CategoryAddController'] = $dir . 'Controller/CategoryAddController.class.php';
$wgAutoloadClasses['Email\Controller\AbstractFounderEditController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\Controller\FounderEditController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\Controller\FounderAnonEditController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\Controller\FounderMultiEditController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\Controller\FounderActiveController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\Controller\FounderNewMemberController'] =  $dir . 'Controller/FounderController.class.php';
$wgAutoloadClasses['Email\SpecialSendEmailController'] = $dir .  'SpecialSendEmailController.class.php';
$wgAutoloadClasses['Email\Tracking\TrackingCategories'] = $dir .  'tracking/TrackingCategories.class.php';

/**
 * special pages
 */
$wgSpecialPages[ 'SendEmail' ] =  'Email\SpecialSendEmailController';

/**
 * messages
 */
$wgExtensionMessagesFiles['Email'] = $dir . 'Email.i18n.php';
$wgExtensionMessagesFiles['EmailWatchedPage'] = $dir . 'i18n/WatchedPage.i18n.php';
$wgExtensionMessagesFiles['EmailComment'] = $dir . 'i18n/Comment.i18n.php';
$wgExtensionMessagesFiles['EmailBlogPost'] = $dir . 'i18n/BlogPost.i18n.php';
$wgExtensionMessagesFiles['EmailForum'] = $dir . 'i18n/Forum.i18n.php';
$wgExtensionMessagesFiles['EmailWallMessage'] = $dir . 'i18n/WallMessage.i18n.php';
$wgExtensionMessagesFiles['EmailWeeklyDigest'] = $dir . 'i18n/WeeklyDigest.i18n.php';
$wgExtensionMessagesFiles['EmailFounder'] = $dir . 'i18n/Founder.i18n.php';
$wgExtensionMessagesFiles['EmailConfirmation'] = $dir . 'i18n/EmailConfirmation.i18n.php';
$wgExtensionMessagesFiles['EmailCategoryAdd'] = $dir . 'i18n/CategoryAdd.i18n.php';
$wgExtensionMessagesFiles['SpecialSendEmail'] = $dir . 'i18n/specialSendEmail.i18n.php';
$wgExtensionMessagesFiles['ForgotPassword'] = $dir . 'i18n/ForgotPassword.i18n.php';

/**
 * permissions
 */
$wgGroupPermissions['*']['access-sendemail'] = false;
$wgGroupPermissions['staff']['access-sendemail'] = true;
$wgGroupPermissions['helper']['access-sendemail'] = true;
$wgGroupPermissions['translator']['access-sendemail'] = true;

$wgAddGroups['staff'][] = 'translator';
$wgRemoveGroups['staff'][] = 'translator';
