# ObjectMapper

## Usage
```php
// Create mapping object as $mapping

$objectMapper->register($mapping);

$foo = $objectMapper->map($object, Foo::class);
```

## Mapping
See concept.xml for the mapping specification.

### Mapping languages
* Initially: PHP
* Roadmap: XML
* Optionally: YAML
* Optionally: custom language
