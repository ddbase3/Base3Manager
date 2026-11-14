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


namespace Base3\Base3Manager\Test\Page;

use PHPUnit\Framework\TestCase;

final class ContentContainerCompatibilityTest extends TestCase {

	public function testContentRootIsNotAFormButKeepsItsEstablishedId(): void {
		$source = file_get_contents(dirname(__DIR__, 2) . '/tpl/Page/Index.php');
		self::assertIsString($source);
		self::assertStringContainsString('<div id="content">', $source);
		self::assertStringNotContainsString('<form id="content"', $source);
	}

	public function testXrmSaveStillSerializesAllNamedFieldsWithinLoadedContent(): void {
		$source = file_get_contents(dirname(__DIR__, 3) . '/ManageXrm/tpl/ToolbarControl/SaveEntryToolbarControl.php');
		self::assertIsString($source);
		self::assertStringContainsString('$("#content").find(":input").serialize()', $source);
		self::assertStringNotContainsString('$("#content").serialize()', $source);
		self::assertStringContainsString('data = "id=" + currentEntryId + "&" + data;', $source);
		self::assertStringContainsString('"&fn=entry&mode=save"', $source);
	}

	public function testPhotographyContentStylesDoNotRequireFormElement(): void {
		$source = file_get_contents(dirname(__DIR__, 3) . '/ManageDancephotography/assets/css/style.css');
		self::assertIsString($source);
		self::assertStringNotContainsString('form#content', $source);
		self::assertStringContainsString('#base3manager.dancephotography #content #wrap {', $source);
		self::assertStringContainsString('#base3manager.dancephotography #content #wrap fieldset {', $source);
	}
}
