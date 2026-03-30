# Enum Attribute Lookup Bundle

This Symfony bundle provides a way to look up attributes on PHP enum cases at runtime. It gathers metadata from attributes that implement `EnumCaseAttributeInterface` and makes it accessible through a trait.

## Key Features

- **Automated Metadata Collection**: A compiler pass scans your project's `src` directory for enums using the `AttributeLookupTrait`.
- **Selective Attribution**: Only attributes that implement `EnumCaseAttributeInterface` are gathered, preventing unwanted overhead.
- **Fast Runtime Lookups**: Attributes are stored in a static registry, which is initialized once when the Symfony kernel boots.

## Installation

Add the bundle to your Symfony project via Composer:

```bash
composer require jawitold/enum-attribute-lookup-bundle
```

The bundle should be automatically registered by Symfony Flex. If not, add it to your `config/bundles.php`:

```php
return [
    // ...
    JaWitold\EnumAttributeLookupBundle\JaWitoldEnumAttributeLookupBundle::class => ['all' => true],
];
```

## Usage

### 1. Create a Metadata Attribute

Define an attribute class that implements `EnumCaseAttributeInterface`:

```php
namespace App\Attribute;

use JaWitold\EnumAttributeLookupBundle\Contract\EnumCaseAttributeInterface;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class RoleMetadata implements EnumCaseAttributeInterface
{
    public function __construct(
        public string $label,
        public string $icon = 'user',
    ) {}
}
```

### 2. Apply to an Enum

Apply the attribute to your enum cases and use the `AttributeLookupTrait`:

```php
namespace App\Enum;

use App\Attribute\RoleMetadata;
use JaWitold\EnumAttributeLookupBundle\AttributeLookupTrait;use JaWitold\EnumAttributeLookupBundle\AttributeLookupTrait;

enum UserRole: string
{
    use AttributeLookupTrait;

    #[RoleMetadata(label: 'Administrator', icon: 'shield')]
    case Admin = 'admin';

    #[RoleMetadata(label: 'Standard User')]
    case User = 'user';
}
```

### 3. Query the Enum

Use the methods provided by `AttributeLookupTrait` to retrieve cases or their metadata.

#### Get Cases by Attribute Class

```php
// Returns a list of cases that have a RoleMetadata attribute
$cases = UserRole::getCasesByAttribute(RoleMetadata::class);
```

#### Get Attributes for a Specific Case

```php
// Returns a list of RoleMetadata objects applied to the Admin case
$attributes = UserRole::getAttributes(UserRole::Admin, fn ($attr) => $attr instanceof RoleMetadata);
$label = $attributes[0]->label; // 'Administrator'
```

#### Custom Case Filtering

```php
// Returns cases that have a RoleMetadata attribute with a specific label
$cases = UserRole::getCases(fn (object $attr) => $attr instanceof RoleMetadata && $attr->label === 'Administrator');
```

## How It Works

1. **Compilation**: The `EnumAttributesCompilerPass` scans all PHP files in your `src/` directory.
2. **Identification**: It identifies enums using `AttributeLookupTrait`.
3. **Extraction**: For each identified enum, it extracts attributes implementing `EnumCaseAttributeInterface` from each case.
4. **Service Registration**: Each unique attribute is registered as a service in the container, ensuring that dependencies can be injected if needed (though typically they are just data objects).
5. **Initialization**: The `EnumRegistry` is tagged as a `kernel.boot` listener and is initialized with the map of enums and their attributes once at runtime.

## Requirements

- PHP 8.2 or higher
- Symfony 7.0 or higher
