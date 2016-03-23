<?php
namespace Flowpack\Neos\FrontendUserManager\ViewHelpers;

/*                                                                                    *
 * This script belongs to the TYPO3 Flow package "Flowpack.Neos.FrontendUserManager". *
 *                                                                                    *
 *                                                                                    */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authentication\TokenInterface;
use TYPO3\Fluid\Core\ViewHelper;

/**
 * Used to check if a node should be visible in navigation by it's authentication visibility.

 * = Examples =
 *
 * <frontendUserManager:isVisible authenticationVisibility="{item.node.properties.authenticationVisibility}">...</frontendUserManager>
 *
 *
 * @api
 */
class IsVisibleViewHelper extends ViewHelper\AbstractConditionViewHelper {

	/**
	 * providerName
	 *
	 * @var string
	 * @Flow\Inject(setting="providerName")
	 */
	protected $providerName;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Injects the security context
	 *
	 * @param \TYPO3\Flow\Security\Context $securityContext The security context
	 * @return void
	 */
	public function injectSecurityContext(\TYPO3\Flow\Security\Context $securityContext) {
		$this->securityContext = $securityContext;
	}

	/**
	 * renders <f:then> child if the node should be shown in navigation by its authentication visibility
	 * otherwise renders <f:else> child.
	 *
	 * @param string $authenticationVisibility The Node's authentication visibility
	 * @return string the rendered string
	 * @api
	 */
	public function render($authenticationVisibility = '') {
		$activeTokens = $this->securityContext->getAuthenticationTokens();
		/** @var $token TokenInterface */
		$isAuthenticated = FALSE;
		foreach ($activeTokens as $token) {
			if ($token->getAuthenticationProviderName() === $this->providerName && $token->isAuthenticated()) {
				$isAuthenticated = TRUE;
			}
		}
		switch ($authenticationVisibility) {
			case 'hideAtAnyLogin':
				$isVisible = !$isAuthenticated;
				break;
			case 'showAtAnyLogin':
				$isVisible = $isAuthenticated;
				break;
			default:
				$isVisible = TRUE;
		}
		if ($isVisible === TRUE) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}
