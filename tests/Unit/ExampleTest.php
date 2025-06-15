<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test basic assertion - memastikan testing framework berfungsi
     */
    public function test_basic_assertion(): void
    {
        $this->assertTrue(true);
        $this->assertEquals(4, 2 + 2);
        $this->assertIsString('Flarum CI/CD Pipeline');
    }
    
    /**
     * Test array operations - testing manipulasi array
     */
    public function test_array_operations(): void
    {
        $array = [1, 2, 3, 4, 5];
        
        $this->assertCount(5, $array);
        $this->assertContains(3, $array);
        $this->assertEquals(15, array_sum($array));
    }
    
    /**
     * Test string operations - testing string functions
     */
    public function test_string_operations(): void
    {
        $string = "Flarum CI/CD Pipeline";
        
        $this->assertStringContainsString('CI/CD', $string);
        $this->assertStringStartsWith('Flarum', $string);
        $this->assertStringEndsWith('Pipeline', $string);
    }
    
    /**
     * Test dengan data provider
     * 
     * @dataProvider calculationProvider
     */
    public function test_calculations($a, $b, $expected): void
    {
        $this->assertEquals($expected, $a + $b);
    }
    
    /**
     * Data provider untuk testing calculations
     */
    public static function calculationProvider(): array
    {
        return [
            'simple addition' => [1, 1, 2],
            'medium numbers' => [25, 75, 100],
            'large numbers' => [500, 500, 1000],
            'negative numbers' => [-10, 5, -5]
        ];
    }
    
    /**
     * Test environment variables
     */
    public function test_environment_setup(): void
    {
        // Test bahwa kita di testing environment
        $this->assertEquals('testing', $_ENV['APP_ENV'] ?? 'testing');
        
        // Test PHP version compatibility
        $this->assertGreaterThanOrEqual('8.0', PHP_VERSION);
    }
}