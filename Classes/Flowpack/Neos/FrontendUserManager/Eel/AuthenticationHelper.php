<?php
namespace Flowpack\Neos\FrontendUserManager\Eel;


/*                                                                                                  *
 * This script belongs to the TYPO3 Flow package "Flowpack.ElasticSearch.ContentRepositoryAdaptor". *
 *                                                                                                  *
 * It is free software; you can redistribute it and/or modify it under                              *
 * the terms of the GNU Lesser General Public License, either version 3                             *
 *  of the License, or (at your option) any later version.                                          *
 *                                                                                                  *
 * The TYPO3 project - inspiring people to share!                                                   *
 *                                                                                                  */

use TYPO3\Eel\ProtectedContextAwareInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Context;
use TYPO3\Flow\Security\Authentication\TokenInterface;
use TYPO3\Party\Domain\Service\PartyService;

/**
 * Eel Helper to get Authentication Information in TypoScript
 */
class AuthenticationHelper implements ProtectedContextAwareInterface {

	/**
	 * @var Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var PartyService
	 */
	protected $partyService;

	/**
	 * Injects the Security Context
	 *
	 * @param Context $securityContext
	 * @return void
	 */
	public function injectSecurityContext(Context $securityContext) {
		$this->securityContext = $securityContext;
	}

	/**
	 * Returns the Users Fullname or the Account Identifier
	 *
	 * @param $providerName
	 * @param string $returnValue
	 * @return mixed
	 */
	public function identifier($providerName, $returnValue = 'user') {
		$account = $this->securityContext->getAccount();
		if ($account !== NULL) {
			$user = $this->partyService->getAssignedPartyOfAccount($account);
		}
		if (!empty($user) && $user instanceof $providerName) {
			switch ($returnValue) {
				case 'user':
					return $user;
				case 'identifier':
					return $user->getName()->getAlias();
			}
		}
	}

	/**
	 * Checks whether a User is authenticated
	 *
	 * @param string $providerName
	 * @return boolean
	 * @api
	 */
	public function isAuthenticated($providerName) {
		$activeTokens = $this->securityContext->getAuthenticationTokens();
		/** @var $token TokenInterface */
		foreach ($activeTokens as $token) {
			if ($token->getAuthenticationProviderName() === $providerName && $token->isAuthenticated()) {
				return TRUE;
			}
		}
	}

	/**
	 * All methods are considered safe
	 *
	 * @param string $methodName
	 * @return boolean
	 */
	public function allowsCallOfMethod($methodName) {
		return TRUE;
	}
}