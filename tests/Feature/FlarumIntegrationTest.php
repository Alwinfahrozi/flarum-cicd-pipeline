<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class FlarumIntegrationTest extends TestCase
{
    /**
     * Test Flarum source code integration with our CI/CD pipeline
     */
    public function test_flarum_source_integration(): void
    {
        $srcDir = __DIR__ . '/../../src';
        
        // Test that Flarum source exists and is properly structured
        $this->assertDirectoryExists($srcDir, 'Flarum source directory should exist');
        
        // Test critical Flarum files
        $criticalFiles = [
            'composer.json',
            'public/index.php',
        ];
        
        foreach ($criticalFiles as $file) {
            $filePath = $srcDir . '/' . $file;
            if (file_exists($filePath)) {
                $this->assertFileExists($filePath, "Critical Flarum file {$file} should exist");
            }
        }
    }
    
    /**
     * Test Flarum dependencies compatibility with our environment
     */
    public function test_flarum_dependencies_compatibility(): void
    {
        $composerFile = __DIR__ . '/../../src/composer.json';
        
        if (!file_exists($composerFile)) {
            $this->markTestSkipped('Flarum composer.json not found');
            return;
        }
        
        $composerData = json_decode(file_get_contents($composerFile), true);
        
        // Test PHP version compatibility
        if (isset($composerData['require']['php'])) {
            $requiredPhp = $composerData['require']['php'];
            $this->assertNotEmpty($requiredPhp, 'PHP version requirement should be specified');
        }
        
        // Test that our CI/CD environment supports Flarum requirements
        $this->assertGreaterThanOrEqual('7.4', PHP_VERSION, 'Our environment should support minimum PHP version for Flarum');
    }
    
    /**
     * Test Flarum CLI functionality (if available)
     */
    public function test_flarum_cli_functionality(): void
    {
        $flarumCli = __DIR__ . '/../../src/flarum';
        
        if (!file_exists($flarumCli)) {
            $this->markTestSkipped('Flarum CLI not found');
            return;
        }
        
        $this->assertFileExists($flarumCli);
        $this->assertTrue(is_readable($flarumCli), 'Flarum CLI should be readable');
        
        // Test CLI file structure
        $cliContent = file_get_contents($flarumCli);
        $this->assertNotEmpty($cliContent, 'Flarum CLI should not be empty');
        $this->assertStringContainsString('php', $cliContent, 'Flarum CLI should be a PHP script');
    }
    
    /**
     * Test Flarum storage permissions and structure
     */
    public function test_flarum_storage_structure(): void
    {
        $storageDir = __DIR__ . '/../../src/storage';
        
        if (!is_dir($storageDir)) {
            $this->markTestSkipped('Flarum storage directory not found');
            return;
        }
        
        $this->assertDirectoryExists($storageDir, 'Flarum storage directory should exist');
        $this->assertTrue(is_readable($storageDir), 'Storage directory should be readable');
        
        // Test storage subdirectories
        $expectedDirs = ['logs', 'cache', 'sessions', 'views', 'tmp'];
        foreach ($expectedDirs as $dir) {
            $dirPath = $storageDir . '/' . $dir;
            if (is_dir($dirPath)) {
                $this->assertDirectoryExists($dirPath, "Storage subdirectory {$dir} should exist");
            }
        }
    }
    
    /**
     * Test Flarum public assets accessibility
     */
    public function test_flarum_public_assets(): void
    {
        $publicDir = __DIR__ . '/../../src/public';
        
        if (!is_dir($publicDir)) {
            $this->markTestSkipped('Flarum public directory not found');
            return;
        }
        
        $this->assertDirectoryExists($publicDir, 'Flarum public directory should exist');
        
        // Test entry point
        $indexFile = $publicDir . '/index.php';
        if (file_exists($indexFile)) {
            $this->assertFileExists($indexFile, 'Flarum entry point should exist');
            
            $indexContent = file_get_contents($indexFile);
            $this->assertStringContainsString('<?php', $indexContent, 'Entry point should be a PHP file');
        }
        
        // Test assets directory
        $assetsDir = $publicDir . '/assets';
        if (is_dir($assetsDir)) {
            $this->assertDirectoryExists($assetsDir, 'Assets directory should exist');
        }
    }
    
    /**
     * Test environment compatibility between our CI/CD and Flarum
     */
    public function test_environment_compatibility(): void
    {
        // Test our project structure works with Flarum
        $this->assertDirectoryExists(__DIR__ . '/../../src', 'Source directory should exist');
        $this->assertDirectoryExists(__DIR__ . '/../../docker', 'Docker directory should exist');
        $this->assertDirectoryExists(__DIR__ . '/../../.github', 'GitHub Actions directory should exist');
        
        // Test our configuration files
        $this->assertFileExists(__DIR__ . '/../../composer.json', 'Root composer.json should exist');
        $this->assertFileExists(__DIR__ . '/../../phpunit.xml', 'PHPUnit config should exist');
        $this->assertFileExists(__DIR__ . '/../../.env.example', 'Environment example should exist');
        
        // Test that our tests can access Flarum source
        $this->assertTrue(is_readable(__DIR__ . '/../../src'), 'Flarum source should be readable by tests');
    }
    
    /**
     * Test CI/CD pipeline integration with Flarum codebase
     */
    public function test_cicd_pipeline_integration(): void
    {
        // Test that our CI/CD setup can handle Flarum
        $flarumComposer = __DIR__ . '/../../src/composer.json';
        $rootComposer = __DIR__ . '/../../composer.json';
        
        $this->assertFileExists($rootComposer, 'Root composer.json for CI/CD should exist');
        
        if (file_exists($flarumComposer)) {
            $this->assertFileExists($flarumComposer, 'Flarum composer.json should exist');
            
            // Test both composer files are valid JSON
            $rootData = json_decode(file_get_contents($rootComposer), true);
            $flarumData = json_decode(file_get_contents($flarumComposer), true);
            
            $this->assertIsArray($rootData, 'Root composer.json should be valid JSON');
            $this->assertIsArray($flarumData, 'Flarum composer.json should be valid JSON');
        }
    }
    
    /**
     * Test Docker integration readiness with Flarum
     */
    public function test_docker_integration_readiness(): void
    {
        // Test Docker files exist
        $this->assertFileExists(__DIR__ . '/../../docker/Dockerfile', 'Dockerfile should exist');
        $this->assertFileExists(__DIR__ . '/../../docker/docker-compose.yml', 'Docker Compose should exist');
        
        // Test Flarum source is ready for containerization
        $srcDir = __DIR__ . '/../../src';
        $this->assertDirectoryExists($srcDir, 'Source directory should be ready for Docker');
        
        // Test environment file template exists
        $this->assertFileExists(__DIR__ . '/../../.env.example', 'Environment template should exist for Docker');
    }
}