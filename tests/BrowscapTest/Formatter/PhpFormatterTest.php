<?php
declare(strict_types = 1);
namespace BrowscapTest\Formatter;

use Browscap\Data\PropertyHolder;
use Browscap\Formatter\FormatterInterface;
use Browscap\Formatter\PhpFormatter;
use PHPUnit\Framework\TestCase;

class PhpFormatterTest extends TestCase
{
    /**
     * @var PhpFormatter
     */
    private $object;

    protected function setUp() : void
    {
        $propertyHolder = $this->getMockBuilder(PropertyHolder::class)
            ->disableOriginalConstructor()
            ->setMethods(['isOutputProperty'])
            ->getMock();

        $propertyHolder
            ->expects(static::any())
            ->method('isOutputProperty')
            ->willReturn(true);

        $this->object = new PhpFormatter($propertyHolder);
    }

    /**
     * tests getter for the formatter type
     */
    public function testGetType() : void
    {
        static::assertSame(FormatterInterface::TYPE_PHP, $this->object->getType());
    }

    /**
     * tests formatting a property name
     */
    public function testFormatPropertyName() : void
    {
        static::assertSame('text', $this->object->formatPropertyName('text'));
    }

    /**
     * Data Provider for the test testGetPropertyType
     */
    public function propertyNameTypeDataProvider() : array
    {
        return [
            ['Comment', 'test', '"test"'],
            ['Browser', 'test', '"test"'],
            ['Platform', 'test', '"test"'],
            ['Platform_Description', 'test', '"test"'],
            ['Device_Name', 'test', '"test"'],
            ['Device_Maker', 'test', '"test"'],
            ['RenderingEngine_Name', 'test', '"test"'],
            ['RenderingEngine_Description', 'test', '"test"'],
            ['Parent', 'test', '"test"'],
            ['Platform_Version', 'test', 'test'],
            ['RenderingEngine_Version', 'test', 'test'],
            ['Version', 'test', 'test'],
            ['MajorVer', 'test', 'test'],
            ['MinorVer', 'test', 'test'],
            ['CssVersion', 'test', 'test'],
            ['AolVersion', 'test', 'test'],
            ['Alpha', 'true', '"true"'],
            ['Beta', 'false', '"false"'],
            ['Win16', 'test', ''],
            ['Browser_Type', 'Browser', '"Browser"'],
            ['Device_Type', 'Tablet', '"Tablet"'],
            ['Device_Pointing_Method', 'mouse', '"mouse"'],
        ];
    }

    /**
     * tests formatting a property value
     *
     * @dataProvider propertyNameTypeDataProvider
     *
     * @param string $propertyName
     * @param string $inputValue
     * @param string $expectedValue
     *
     * @throws \Exception
     */
    public function testFormatPropertyValue(string $propertyName, string $inputValue, string $expectedValue) : void
    {
        $actualValue = $this->object->formatPropertyValue($inputValue, $propertyName);
        static::assertSame($expectedValue, $actualValue, "Property {$propertyName} should be {$expectedValue} (was {$actualValue})");
    }

    /**
     * tests formatting a property value
     *
     * @throws \Exception
     */
    public function testFormatPropertyValueWithException() : void
    {
        $actualValue = $this->object->formatPropertyValue('Browserx', 'Device_Pointing_Method');
        static::assertSame('', $actualValue);
    }

    /**
     * tests formatting a property value
     *
     * @throws \Exception
     */
    public function testFormatPropertyValueWithUnknownValue() : void
    {
        $actualValue = $this->object->formatPropertyValue('unknown', 'Browser_Type');
        static::assertSame('"unknown"', $actualValue);
    }

    /**
     * tests formatting a property value
     *
     * @throws \Exception
     */
    public function testFormatPropertyValueWithSpecialChars() : void
    {
        $actualValue = $this->object->formatPropertyValue('1.0', 'Platform_Version');
        static::assertSame('"1.0"', $actualValue);
    }

    /**
     * tests formatting a property value
     *
     * @throws \Exception
     */
    public function testFormatPropertyValueWithoutSpecialChars() : void
    {
        $actualValue = $this->object->formatPropertyValue('1', 'Platform_Version');
        static::assertSame('1', $actualValue);
    }
}
