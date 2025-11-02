<?php declare(strict_types=1);

namespace AdrienLbt\HexagonalMakerBundle\Maker\Handler;

use AdrienLbt\HexagonalMakerBundle\Maker\Factory\ClassFile\PresenterInterfaceFile;
use AdrienLbt\HexagonalMakerBundle\Maker\Factory\ClassFile\RequestFile;
use AdrienLbt\HexagonalMakerBundle\Maker\MakeTrait;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\MakerBundle\Util\ClassSource\Model\ClassProperty;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Question\Question;

final class RequestHandler extends AbstractHandler
{
    use MakeTrait;

    public function handleRequest(mixed $request): void
    {
        $createRequest = $this->io->confirm(
            RequestFile::getUserQuestion($request['useCaseName']),
            true
        );

        if (!$createRequest) {
            parent::handleRequest($request);
            return;
        }

        // Create request file 
        $requestFile = $this->creator->generateRequest(
            name: $request['useCaseName'],
            folderPath: $request['useCaseFolderPath'],
            domainPath: $request['domainPath']
        );

        $this->handleNextFieldCreation($requestFile, $request);
    }
    
    /**
     * Asking user for adding field in current RequestFile class 
     * Then add properties to RequestFile instance.
     *
     * @param RequestFile $requestFile
     * @param array $request
     * @return void
     */
    private function handleNextFieldCreation(RequestFile $requestFile, array $request): void
    {
        $isFirstField = true;

        while (true) {
            $newClassProperty = $this->buildNewClassPropertyByAskingUser($isFirstField);

            $isFirstField = false;

            if (is_null($newClassProperty)) {
                break;
            }

            $requestFile->addClassAttributes($newClassProperty);
        }
    }

    private function buildNewClassPropertyByAskingUser(bool $isFirstField): ?ClassProperty
    {
        $fieldName = $this->askForNewFieldName($isFirstField);

        if (is_null($fieldName)) {
            return null;
        }

        $fieldType = $this->askForNewFieldType($fieldName);

        $classProperty = new ClassProperty(
            propertyName: $fieldName, 
            type: $fieldType
        );

        $classProperty->nullable = $this->askForNewFieldIsNullable();

        return $classProperty;
    }

    private function askForNewFieldName(bool $isFirstField): ?string
    {
        $fields = [];

        $questionText = RequestFile::getNewFieldQuestion($isFirstField);

        $fieldName = $this->io->ask($questionText, null, function ($name) use ($fields) {
            // allow it to be empty
            if (!$name) {
                return $name;
            }

            if (\in_array($name, $fields)) {
                throw new \InvalidArgumentException(\sprintf('The "%s" property already exists.', $name));
            }

            return Validator::validatePropertyName($name);
        });

        if (!$fieldName) {
            return null;
        }

        return $fieldName;
    } 

    private function askForNewFieldType(string $fieldName): string
    {
        $defaultType = 'string';
        // try to guess the type by the field name prefix/suffix
        // convert to snake case for simplicity
        $snakeCasedField = Str::asSnakeCase($fieldName);

        if ('_at' === $suffix = substr($snakeCasedField, -3)) {
            $defaultType = 'datetime_immutable';
        } elseif ('_id' === $suffix) {
            $defaultType = 'integer';
        } elseif (str_starts_with($snakeCasedField, 'is_')) {
            $defaultType = 'boolean';
        } elseif (str_starts_with($snakeCasedField, 'has_')) {
            $defaultType = 'boolean';
        }

        $type = null;

        $nativeTypes = Type::getTypesMap();

        // @TODO il faudrait récupérer les types possibles, dans la 
        // request, issue du dossier Model du Domain
        $otherValidTypes = [];

        $allValidTypes = array_merge(
            array_keys($nativeTypes),
            $otherValidTypes,
            // ['relation', 'enum']
        );

        while (null === $type) {
            $question = new Question('Field type ', $defaultType);
            $question->setAutocompleterValues($allValidTypes);
            $type = $this->io->askQuestion($question);

            if (!\in_array($type, $allValidTypes)) {
                $this->io->error(\sprintf('Invalid type "%s".', $type));
                $this->io->writeln('');

                $type = null;
            }
        }

        return $type;
    }

    private function askForNewFieldIsNullable(): bool
    {
        return $this->io->confirm(RequestFile::getNullableNewFieldQuestion(), false);
    }
}