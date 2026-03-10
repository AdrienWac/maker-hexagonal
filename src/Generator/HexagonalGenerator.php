<?php

namespace AdrienLbt\HexagonalMakerBundle\Generator;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

/**
 * Custom Generator that decorates Symfony MakerBundle's Generator
 *
 * This decorator allows us to customize the createClassNameDetails method
 * to support Domain namespace without the "App\" prefix, which is essential
 * for hexagonal architecture.
 */
class HexagonalGenerator extends Generator
{
    public function __construct(
        private Generator $decorated,
        private string $domainPath = 'Domain'
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createClassNameDetails(
        string $name,
        string $namespacePrefix,
        string $suffix = '',
        string $validationErrorMessage = ''
    ): ClassNameDetails {
        // If the namespace contains the configured domain path (e.g., "Domain"),
        // use it as-is without prepending "App\"
        if (str_contains($namespacePrefix, $this->domainPath)) {
            // We need to create a temporary generator with empty namespace prefix
            // to avoid the "App\" prefix being added
            $reflection = new \ReflectionClass($this->decorated);
            $fileManagerProperty = $reflection->getProperty('fileManager');
            $fileManagerProperty->setAccessible(true);
            $fileManager = $fileManagerProperty->getValue($this->decorated);

            $templateComponentGeneratorProperty = $reflection->getProperty('templateComponentGenerator');
            $templateComponentGeneratorProperty->setAccessible(true);
            $templateComponentGenerator = $templateComponentGeneratorProperty->getValue($this->decorated);

            // Create a temporary generator with the domain namespace as the prefix
            $tempGenerator = new Generator(
                $fileManager,
                '',  // Empty namespace prefix
                null,
                $templateComponentGenerator
            );

            return $tempGenerator->createClassNameDetails(
                $name,
                $namespacePrefix,
                $suffix,
                $validationErrorMessage
            );
        }

        // For non-domain namespaces, use the default behavior
        return $this->decorated->createClassNameDetails(
            $name,
            $namespacePrefix,
            $suffix,
            $validationErrorMessage
        );
    }

    /**
     * Delegate all other method calls to the decorated generator
     */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->decorated->$method(...$arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function generateClass(string $className, string $templateName, array $variables = []): string
    {
        return $this->decorated->generateClass($className, $templateName, $variables);
    }

    /**
     * {@inheritdoc}
     */
    public function generateFile(string $targetPath, string $templateName, array $variables = []): void
    {
        $this->decorated->generateFile($targetPath, $templateName, $variables);
    }

    /**
     * {@inheritdoc}
     */
    public function dumpFile(string $targetPath, string $contents): void
    {
        $this->decorated->dumpFile($targetPath, $contents);
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDirectory(): string
    {
        return $this->decorated->getRootDirectory();
    }

    /**
     * {@inheritdoc}
     */
    public function hasPendingOperations(): bool
    {
        return $this->decorated->hasPendingOperations();
    }

    /**
     * {@inheritdoc}
     */
    public function writeChanges(): void
    {
        $this->decorated->writeChanges();
    }

    /**
     * {@inheritdoc}
     */
    public function getRootNamespace(): string
    {
        return $this->decorated->getRootNamespace();
    }

    /**
     * {@inheritdoc}
     */
    public function getGeneratedFiles(): array
    {
        return $this->decorated->getGeneratedFiles();
    }

    /**
     * {@inheritdoc}
     */
    public function getFileContentsForPendingOperation(string $targetPath): string
    {
        return $this->decorated->getFileContentsForPendingOperation($targetPath);
    }
}
