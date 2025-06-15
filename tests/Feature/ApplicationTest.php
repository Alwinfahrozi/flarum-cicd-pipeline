<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * Test project structure - memastikan struktur project benar
     */
    public function test_project_structure(): void
    {
        // Test direktori penting ada
        $this->assertDirectoryExists(__DIR__ . '/../../src');
        $this->assertDirectoryExists(__DIR__ . '/../../docker');
        $this->assertDirectoryExists(__DIR__ . '/../../.github');
        $this->assertDirectoryExists(__DIR__ . '/../../tests');
        
        // Test file konfigurasi ada
        $this->assertFileExists(__DIR__ . '/../../composer.json');
        $this->assertFileExists(__DIR__ . '/../../.gitignore');
        $this->assertFileExists(__DIR__ . '/../../phpunit.xml');
    }
    
    /**
     * Test composer configuration
     */
    public function test_composer_configuration(): void
    {
        $composerFile = __DIR__ . '/../../composer.json';
        $this->assertFileExists($composerFile);
        
        $composerContent = file_get_contents($composerFile);
        $composerData = json_decode($composerContent, true);
        
        $this->assertIsArray($composerData);
        $this->assertArrayHasKey('name', $composerData);
        $this->assertArrayHasKey('require', $composerData);
        $this->assertArrayHasKey('require-dev', $composerData);
        $this->assertArrayHasKey('scripts', $composerData);
        
        // Test project name
        $this->assertEquals('flarum-cicd/pipeline', $composerData['name']);
        
        // Test required dependencies
        $this->assertArrayHasKey('php', $composerData['require']);
        $this->assertArrayHasKey('phpunit/phpunit', $composerData['require-dev']);
    }
    
    /**
     * Test autoloading functionality
     */
    public function test_autoloading(): void
    {
        // Test vendor autoload ada
        $this->assertFileExists(__DIR__ . '/../../vendor/autoload.php');
        
        // Test class-class penting dapat di-load
        $this->assertTrue(class_exists('PHPUnit\Framework\TestCase'));
        $this->assertTrue(class_exists('Composer\Autoload\ClassLoader'));
    }
    
    /**
     * Test CI/CD related files structure
     */
    public function test_cicd_structure(): void
    {
        // Test GitHub Actions directory
        $githubDir = __DIR__ . '/../../.github';
        $this->assertDirectoryExists($githubDir);
        
        // Test Docker directory
        $dockerDir = __DIR__ . '/../../docker';
        $this->assertDirectoryExists($dockerDir);
        
        // Test scripts directory
        $scriptsDir = __DIR__ . '/../../scripts';
        $this->assertDirectoryExists($scriptsDir);
    }
    
    /**
     * Test PHP environment dan extensions
     */
    public function test_php_environment(): void
    {
        // Test PHP version minimum
        $this->assertGreaterThanOrEqual('8.0', PHP_VERSION);
        
        // Test required extensions
        $requiredExtensions = ['json', 'mbstring', 'curl'];
        
        foreach ($requiredExtensions as $extension) {
            $this->assertTrue(
                extension_loaded($extension),
                "PHP extension '{$extension}' harus ter-install"
            );
        }
    }
    
    /**
     * Test development tools availability
     */
    public function test_development_tools(): void
    {
        // Test PHPUnit executable (Windows uses .bat file)
        $phpunitPath = __DIR__ . '/../../vendor/bin/phpunit';
        $phpunitBatPath = __DIR__ . '/../../vendor/bin/phpunit.bat';
        
        $phpunitExists = file_exists($phpunitPath) || file_exists($phpunitBatPath);
        $this->assertTrue($phpunitExists, 'PHPUnit executable should exist');
        
        // Test Composer classes are available
        $composerClasses = [
            'Composer\Autoload\ClassLoader',
            'Composer\Factory'
        ];
        
        $composerAvailable = false;
        foreach ($composerClasses as $class) {
            if (class_exists($class)) {
                $composerAvailable = true;
                break;
            }
        }
        
        $this->assertTrue($composerAvailable, 'Composer classes should be available');
    }
}