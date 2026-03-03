# Hexagonal Maker Bundle

The Hexagonal Maker Bundle is a code generator for Symfony that automates the use case creation process for Hexagonal Architecture. It extends Symfony's Maker Bundle to generate properly structured use cases, requests, responses, and presenter interfaces.

## ⚙️ Installation

Add the bundle with composer:
``` bash
composer require --dev adrienlbt/hexagonal-maker-bundle
```

If Symfony Flex doesn't add the bundle automatically, activate it manually:
``` php
// config/bundles.php
return [
    // ...
    AdrienLbt\HexagonalMakerBundle\HexagonalMakerBundle::class => ['dev' => true]
];
```

**That's it!** The bundle is now ready to use. No additional configuration steps are required.

## ⚙️ Configuration (Optional)

You can customize the default paths for your hexagonal architecture layers:

``` yaml
# config/packages/hexagonal_maker.yaml
hexagonal_maker:
  application_path: 'Application'  # Default
  domain_path: 'Domain'            # Default
  infrastructure_path: 'Infrastructure'  # Default
```

## 📖 Usage

### Create Use Case
Run the following command and follow the interactive prompts:

``` bash
bin/console make:hexagonal:usecase
```

This will generate:
- **UseCase** class in `Domain\UseCase\{folder}\{Name}`
- **Request** class in `Domain\Request\{folder}\{Name}Request`
- **Response** class in `Domain\Response\{folder}\{Name}Response`
- **PresenterInterface** in `Domain\API\{folder}\{Name}PresenterInterface`

### Example

``` bash
bin/console make:hexagonal:usecase User CreateUser

# This generates:
# - Domain/UseCase/User/CreateUser.php
# - Domain/Request/User/CreateUserRequest.php
# - Domain/Response/User/CreateUserResponse.php
# - Domain/API/User/CreateUserPresenterInterface.php
```

## 🏗️ Generated Structure

The `make:hexagonal:usecase` command generates the following files:

```
src/
├── Domain/
│   ├── UseCase/{folder}/        # Use case implementations
│   ├── Request/{folder}/        # Request DTOs
│   ├── Response/{folder}/       # Response DTOs
│   └── API/{folder}/            # Presenter interfaces
```


## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This bundle is released under the MIT License.



