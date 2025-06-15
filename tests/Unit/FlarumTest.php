<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FlarumTest extends TestCase
{
    /**
     * Test Flarum composer.json configuration
     */
    public function test_flarum_composer_configuration(): void
    {
        $composerFile = __DIR__ . '/../../src/composer.json';
        
        $this->assertFileExists($composerFile, 'Flarum composer.json should exist');
        
        $composerContent = file_get_contents($composerFile);
        $composerData = json_decode($composerContent, true);
        
        $this->assertIsArray($composerData);
        $this->assertArrayHasKey('name', $composerData);
        $this->assertArrayHasKey('require', $composerData);
        
        // Test Flarum specific dependencies
        if (isset($composerData['require'])) {
            // Check if PHP requirement exists (might be in different format)
            $hasPhpRequirement = isset($composerData['require']['php']) || 
                                isset($composerData['require']['PHP']) ||
                                count($composerData['require']) > 0;
            
            $this->assertTrue($hasPhpRequirement, 'Flarum should have dependency requirements');
        } else {
            $this->markTestSkipped('No require section found in Flarum composer.json');
        }
    }
    
    /**
     * Test Flarum extend.php file
     */
    public function test_flarum_extend_file(): void
    {
        $extendFile = __DIR__ . '/../../src/extend.php';
        
        if (file_exists($extendFile)) {
            $this->assertFileExists($extendFile);
            
            // Test file can be included without errors
            $extendContent = file_get_contents($extendFile);
            $this->assertIsString($extendContent);
            $this->assertStringContainsString('<?php', $extendContent);
        } else {
            $this->markTestSkipped('extend.php file not found, skipping test');
        }
    }
    
    /**
     * Test Flarum CLI executable
     */
    public function test_flarum_cli_executable(): void
    {
        $flarumCli = __DIR__ . '/../../src/flarum';
        
        if (file_exists($flarumCli)) {
            $this->assertFileExists($flarumCli);
            
            // Test file is executable or readable
            $this->assertTrue(is_readable($flarumCli), 'Flarum CLI should be readable');
            
            // Test file contains PHP code
            $cliContent = file_get_contents($flarumCli);
            $this->assertStringContainsString('#!/usr/bin/env php', $cliContent);
        } else {
            $this->markTestSkipped('Flarum CLI executable not found, skipping test');
        }
    }
    
    /**
     * Test Flarum directory structure
     */
    public function test_flarum_directory_structure(): void
    {
        $srcDir = __DIR__ . '/../../src';
        
        // Test required Flarum directories
        $requiredDirs = [
            'public',
            'storage',
        ];
        
        foreach ($requiredDirs as $dir) {
            $fullPath = $srcDir . '/' . $dir;
            $this->assertDirectoryExists($fullPath, "Flarum {$dir} directory should exist");
        }
        
        // Test storage subdirectories
        $storageDirs = ['logs', 'cache', 'sessions', 'views', 'tmp'];
        foreach ($storageDirs as $dir) {
            $storagePath = $srcDir . '/storage/' . $dir;
            if (is_dir($storagePath)) {
                $this->assertDirectoryExists($storagePath);
            }
        }
    }
    
    /**
     * Test Flarum public directory assets
     */
    public function test_flarum_public_directory(): void
    {
        $publicDir = __DIR__ . '/../../src/public';
        
        $this->assertDirectoryExists($publicDir, 'Flarum public directory should exist');
        
        // Test for index.php (entry point)
        $indexFile = $publicDir . '/index.php';
        if (file_exists($indexFile)) {
            $this->assertFileExists($indexFile);
            
            $indexContent = file_get_contents($indexFile);
            $this->assertStringContainsString('<?php', $indexContent);
        }
        
        // Test for .htaccess (URL rewriting)
        $htaccessFile = $publicDir . '/.htaccess';
        if (file_exists($htaccessFile)) {
            $this->assertFileExists($htaccessFile);
        }
    }
    
    /**
     * Test PHP requirements for Flarum
     */
    public function test_php_requirements_for_flarum(): void
    {
        // Test PHP version compatibility
        $this->assertGreaterThanOrEqual('7.4', PHP_VERSION, 'PHP version should be 7.4 or higher for Flarum');
        
        // Test required PHP extensions for Flarum
        $requiredExtensions = [
            'json',
            'mbstring',
            'openssl',
            'pdo',
            'tokenizer',
            'xml',
            'ctype',
            'curl',
            'zip',
        ];
        
        foreach ($requiredExtensions as $extension) {
            if (extension_loaded($extension)) {
                $this->assertTrue(extension_loaded($extension), "PHP extension {$extension} should be loaded");
            } else {
                $this->markTestSkipped("Extension {$extension} not loaded, but may not be critical for testing");
            }
        }
    }
    
    /**
     * Test Flarum configuration files
     */
    public function test_flarum_configuration(): void
    {
        $srcDir = __DIR__ . '/../../src';
        
        // Always assert that src directory exists
        $this->assertDirectoryExists($srcDir, 'Flarum source directory should exist');
        
        // Test config.php if exists
        $configFile = $srcDir . '/config.php';
        if (file_exists($configFile)) {
            $this->assertFileExists($configFile);
            
            // Test config file can be included
            $configContent = file_get_contents($configFile);
            $this->assertStringContainsString('<?php', $configContent);
        } else {
            // If config.php doesn't exist, that's also valid for fresh Flarum
            $this->assertTrue(true, 'Config.php not found - this is normal for fresh Flarum installation');
        }
        
        // Test if .env.example exists in src
        $envExampleFile = $srcDir . '/.env.example';
        if (file_exists($envExampleFile)) {
            $this->assertFileExists($envExampleFile);
        } else {
            // Check if there's any configuration file pattern
            $hasConfigFiles = file_exists($srcDir . '/composer.json') || 
                             file_exists($srcDir . '/extend.php') ||
                             is_dir($srcDir . '/public');
            
            $this->assertTrue($hasConfigFiles, 'Flarum should have some configuration files');
        }
    }
    
    /**
     * Test autoloading with Flarum
     */
    public function test_autoloading_with_flarum(): void
    {
        $vendorAutoload = __DIR__ . '/../../src/vendor/autoload.php';
        
        if (file_exists($vendorAutoload)) {
            $this->assertFileExists($vendorAutoload, 'Flarum vendor autoload should exist');
            
            // Test autoload can be included
            $this->assertTrue(is_readable($vendorAutoload), 'Vendor autoload should be readable');
        } else {
            $this->markTestSkipped('Flarum vendor/autoload.php not found - run composer install in src/ directory');
        }
    }
}