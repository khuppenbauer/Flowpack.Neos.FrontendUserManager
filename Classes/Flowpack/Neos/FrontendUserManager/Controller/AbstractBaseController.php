<?php
namespace Flowpack\Neos\FrontendUserManager\Controller;

/*                                                                                   *
 * This script belongs to the TYPO3 Flow package "Flowpack.Neos.FrontendUserManager".*
 *                                                                                   *
 *                                                                                   */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Neos\Domain\Repository\DomainRepository;
use Flowpack\Neos\FrontendUserManager\Service\HelperService;

/**
 * An action controller with base functionality
 *
 * @Flow\Scope("singleton")
 */
abstract class AbstractBaseController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * The pluginService
	 *
	 * @var HelperService
	 * @Flow\Inject
	 */
	protected $helperService;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

}
