<?php

/**
 * NoUserOrGroupSet.
 */

declare(strict_types = 1);

namespace SFC\Staticfilecache\Cache\Rule;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * NoUserOrGroupSet.
 */
class NoUserOrGroupSet extends AbstractRule
{
    /**
     * Check if no user or group is set.
     *
     * @param TypoScriptFrontendController $frontendController
     * @param string                       $uri
     * @param array                        $explanation
     * @param bool                         $skipProcessing
     */
    public function checkRule(TypoScriptFrontendController $frontendController, string $uri, array &$explanation, bool &$skipProcessing)
    {
        if ($this->isUserOrGroupSet($frontendController)) {
            $explanation[__CLASS__] = 'User or group are set';
        }
    }

    /**
     * Fix this bug: https://forge.typo3.org/issues/83212.
     *
     * @see TypoScriptFrontendController::isUserOrGroupSet
     *
     * @param TypoScriptFrontendController $frontendController
     *
     * @return bool TRUE if either a login user is found (array fe_user->user and valid id) OR if the gr_list is set to something else than '0,-1' (could be done even without a user being logged in!)
     */
    public function isUserOrGroupSet(TypoScriptFrontendController $frontendController)
    {
        $version9orHigher = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version()) >= VersionNumberUtility::convertVersionNumberToInteger('9.0.0');
        if (!$version9orHigher) {
            return (\is_array($frontendController->fe_user->user) && isset($frontendController->fe_user->user['uid'])) || '0,-1' !== $frontendController->gr_list;
        }

        $context = GeneralUtility::makeInstance(Context::class);

        try {
            $userIsLoggedIn = (bool)$context->getPropertyFromAspect('frontend.user', 'isLoggedIn');
            $groupIds = (array)$context->getPropertyFromAspect('frontend.user', 'groupIds');
        } catch (AspectNotFoundException $e) {
            return false;
        }

        return $userIsLoggedIn || [0, -1] !== $groupIds;
    }
}
