<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NamespaceConsistencyTest extends TestCase
{
    public function test_psr4_namespaces_match_file_paths(): void
    {
        $root = dirname(__DIR__, 2);
        $mappings = [
            $root . '/app' => 'App\\',
            $root . '/database/factories' => 'Database\\Factories\\',
            $root . '/database/seeders' => 'Database\\Seeders\\',
            $root . '/tests' => 'Tests\\',
        ];

        $mismatches = [];

        foreach ($mappings as $directory => $baseNamespace) {
            if (! is_dir($directory)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                if (! $file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $relativePath = ltrim(str_replace($directory, '', $file->getPathname()), DIRECTORY_SEPARATOR);
                $expectedNamespace = $this->calculateExpectedNamespace($baseNamespace, $relativePath);
                $declaredNamespace = $this->extractDeclaredNamespace($file->getPathname());

                if ($declaredNamespace === null) {
                    $mismatches[] = sprintf(
                        '[%s] missing namespace declaration, expected %s',
                        $relativePath,
                        $expectedNamespace
                    );
                    continue;
                }

                if ($declaredNamespace !== $expectedNamespace) {
                    $mismatches[] = sprintf(
                        '[%s] declared %s, expected %s',
                        $relativePath,
                        $declaredNamespace,
                        $expectedNamespace
                    );
                }
            }
        }

        $message = "Namespace mismatches found:\n" . implode("\n", $mismatches);

        $this->assertEmpty($mismatches, $message);
    }

    private function calculateExpectedNamespace(string $baseNamespace, string $relativePath): string
    {
        $relativePath = preg_replace('~\.php$~i', '', $relativePath);
        $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
        $relativePath = trim($relativePath, '\\');

        if ($relativePath === '') {
            return rtrim($baseNamespace, '\\');
        }

        $pathNamespace = dirname($relativePath);
        $pathNamespace = $pathNamespace === '.' ? '' : str_replace(['/', '\\'], '\\', $pathNamespace);

        if ($pathNamespace === '') {
            return rtrim($baseNamespace, '\\');
        }

        return rtrim($baseNamespace, '\\') . '\\' . $pathNamespace;
    }

    private function extractDeclaredNamespace(string $path): ?string
    {
        $content = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($content === false) {
            return null;
        }

        foreach ($content as $line) {
            if (preg_match('~^\s*namespace\s+([^;]+);~i', $line, $matches)) {
                return trim($matches[1]);
            }

            if (strpos($line, 'class ') !== false || strpos($line, 'interface ') !== false || strpos($line, 'trait ') !== false) {
                break;
            }
        }

        return null;
    }
}
