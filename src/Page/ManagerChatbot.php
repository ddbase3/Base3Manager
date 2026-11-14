<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Manager for BASE3 Framework.
 *
 * Base3Manager provides manager pages and controls for BASE3.
 * It composes administration navigation, toolbars and views.
 * Extensions contribute controls through the existing plugin contracts.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/


namespace Base3\Base3Manager\Page;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IDisplay;
use Base3\CoreFoundation\Api\IOutput;
use Base3\CoreFoundation\Authorization\Api\IAuthorizationService;
use Base3\CoreFoundation\Authorization\Dto\AuthorizationRequest;
use Base3\CoreFoundation\Usermanager\Permission;
use Base3\Chatbot\Content\ChatbotDisplay;
use Base3\ClientStack\GlobalChatbotLauncher\GlobalChatbotLauncherFeature;
use Throwable;

/** Host-authorized content endpoint for the global ClientStack launcher. */
final class ManagerChatbot implements IOutput {

	public function __construct(
		private readonly IAuthorizationService $authorization,
		private readonly IClassMap $classmap
	) {}

	public static function getName(): string {
		return 'managerchatbot';
	}

	public function getOutput(string $out = 'html', bool $final = false): string {
		if ($out !== 'html') {
			http_response_code(403);
			return '';
		}
		try {
			$allowed = $this->authorization->decide(new AuthorizationRequest(
				$this->authorization->getDefaultAuthority(),
				Permission::for('feature', GlobalChatbotLauncherFeature::getName())
			))->isAllowed();
		} catch (Throwable) {
			$allowed = false;
		}
		if (!$allowed) {
			http_response_code(403);
			return '';
		}

		$display = $this->classmap->getInstanceByInterfaceName(IDisplay::class, ChatbotDisplay::getName());
		if (!$display instanceof IDisplay) {
			http_response_code(503);
			return '';
		}
		$display->setData(['config_group' => 'chatbot', 'config_name' => 'default']);
		return $display->getOutput('html', false);
	}
}
