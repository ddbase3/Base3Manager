<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use Base3\Api\IOutput;
use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Dto\AuthorizationRequest;
use Base3\Usermanager\Permission;
use Chatbot\Content\ChatbotDisplay;
use ClientStack\GlobalChatbotLauncher\GlobalChatbotLauncherFeature;
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
