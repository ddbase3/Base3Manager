<?php declare(strict_types=1);

namespace Base3Manager\Test\Page;

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use Base3\Api\IOutput;
use Base3Manager\Page\Content;
use Base3Manager\Service\Base3Manager;
use PHPUnit\Framework\TestCase;

final class ContentConfiguredDisplayTest extends TestCase {

	public function testConfiguredArrayIsPassedToDisplayBeforeRendering(): void {
		$data = ['group' => 'chatbot', 'name' => 'default', 'resources' => ['enabled' => true]];
		$display = $this->createMock(IDisplay::class);
		$display->expects(self::once())->method('setData')->with($data);
		$display->expects(self::once())->method('getOutput')->willReturn('configured chatbot');

		$map = $this->createMock(IClassMap::class);
		$map->expects(self::once())->method('getInstanceByInterfaceName')
			->with(IDisplay::class, 'chatbotconfigdisplay')->willReturn($display);

		$manager = $this->createMock(Base3Manager::class);
		$manager->method('getModule')->with('chatbot')->willReturn([
			'tabs' => [['tab' => 'configuration', 'content' => 'chatbotconfigdisplay', 'data' => $data]]
		]);

		self::assertSame('configured chatbot', $this->render($map, $manager, [
			'alias' => 'chatbot', 'tabalias' => 'configuration'
		]));
	}

	public function testScalarAndNullDataAreForwardedUnchanged(): void {
		foreach (['missionbay_ai_usage_models', null] as $data) {
			$display = $this->createMock(IDisplay::class);
			$display->expects(self::once())->method('setData')->with($data);
			$display->method('getOutput')->willReturn('report');

			$map = $this->createMock(IClassMap::class);
			$map->expects(self::once())->method('getInstanceByInterfaceName')
				->with(IDisplay::class, 'generalreportdisplay')->willReturn($display);

			$manager = $this->createMock(Base3Manager::class);
			$manager->method('getModule')->willReturn([
				'tabs' => [['tab' => 'models', 'content' => 'generalreportdisplay', 'data' => $data]]
			]);

			self::assertSame('report', $this->render($map, $manager, [
				'alias' => 'aiusage', 'tabalias' => 'models'
			]));
		}
	}

	public function testTabWithoutDataKeepsExistingOutputResolution(): void {
		$output = $this->createMock(IOutput::class);
		$output->expects(self::once())->method('getOutput')->willReturn('old output');

		$map = $this->createMock(IClassMap::class);
		$map->expects(self::once())->method('getInstanceByInterfaceName')
			->with(IOutput::class, 'legacycontentcontrol')->willReturn($output);

		$manager = $this->createMock(Base3Manager::class);
		$manager->method('getModule')->willReturn([
			'tabs' => [['tab' => 'legacy', 'content' => 'legacycontentcontrol']]
		]);

		self::assertSame('old output', $this->render($map, $manager, [
			'alias' => 'legacy', 'tabalias' => 'legacy'
		]));
	}

	public function testSubnavigationAlsoPassesConfiguredData(): void {
		$display = $this->createMock(IDisplay::class);
		$display->expects(self::once())->method('setData')->with('report-id');
		$display->method('getOutput')->willReturn('subnavigation report');

		$map = $this->createMock(IClassMap::class);
		$map->expects(self::once())->method('getInstanceByInterfaceName')
			->with(IDisplay::class, 'generalreportdisplay')->willReturn($display);

		$manager = $this->createMock(Base3Manager::class);
		$manager->method('getModule')->willReturn([
			'subnavi' => [['subnavi' => 'report', 'content' => 'generalreportdisplay', 'data' => 'report-id']]
		]);

		self::assertSame('subnavigation report', $this->render($map, $manager, [
			'alias' => 'reports', 'subnavialias' => 'report'
		]));
	}

	private function render(IClassMap $classmap, Base3Manager $manager, array $request): string {
		$previous = $_REQUEST;
		try {
			$_REQUEST = $request;
			return (new Content($classmap, $manager))->getOutput();
		} finally {
			$_REQUEST = $previous;
		}
	}
}
