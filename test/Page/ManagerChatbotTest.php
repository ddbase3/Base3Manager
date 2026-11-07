<?php declare(strict_types=1);

namespace Base3Manager\Test\Page;

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Dto\AuthorizationDecision;
use Base3Manager\Page\ManagerChatbot;
use Chatbot\Content\ChatbotDisplay;
use PHPUnit\Framework\TestCase;

final class ManagerChatbotTest extends TestCase {

	public function testFeatureDenialCannotInstantiateChatbot(): void {
		$auth = $this->createStub(IAuthorizationService::class);
		$auth->method('getDefaultAuthority')->willReturn('base3');
		$auth->method('decide')->willReturn(AuthorizationDecision::deny());
		$map = $this->createMock(IClassMap::class);
		$map->expects(self::never())->method('getInstanceByInterfaceName');
		$endpoint = new ManagerChatbot($auth, $map);
		self::assertSame('', $endpoint->getOutput());
		self::assertSame(403, http_response_code());
	}

	public function testAuthorizedEndpointUsesTheSameFeatureDecisionAsTheLauncher(): void {
		$auth = $this->createMock(IAuthorizationService::class);
		$auth->method('getDefaultAuthority')->willReturn('base3');
		$auth->expects(self::once())->method('decide')->with(self::callback(
			static fn($request): bool => $request->authority === 'base3'
				&& $request->permission->scope === 'feature'
				&& $request->permission->permission === 'globalchatbotlauncherfeature'
		))->willReturn(AuthorizationDecision::allow());
		$display = $this->createMock(IDisplay::class);
		$display->expects(self::once())->method('setData')->with(['config_group' => 'chatbot', 'config_name' => 'default']);
		$display->expects(self::once())->method('getOutput')->with('html', false)->willReturn('chatbot html');
		$map = $this->createStub(IClassMap::class);
		$map->method('getInstanceByInterfaceName')->willReturnCallback(
			static fn(string $interface, string $name): ?IDisplay =>
				$interface === IDisplay::class && $name === ChatbotDisplay::getName() ? $display : null
		);
		$endpoint = new ManagerChatbot($auth, $map);
		self::assertSame('chatbot html', $endpoint->getOutput());
	}

	public function testUnsupportedOutputCannotInstantiateChatbot(): void {
		$auth = $this->createMock(IAuthorizationService::class);
		$auth->expects(self::never())->method('decide');
		$map = $this->createMock(IClassMap::class);
		$map->expects(self::never())->method('getInstanceByInterfaceName');
		self::assertSame('', (new ManagerChatbot($auth, $map))->getOutput('json'));
		self::assertSame(403, http_response_code());
	}
}
