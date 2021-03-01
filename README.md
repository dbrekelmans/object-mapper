# ObjectMapper

## Usage
```php
// Create mapping object as $mapping

$objectMapper->register($mapping);

$foo = $objectMapper->map($object, Foo::class);
```

## Mapping
See `concept.xml` for the mapping specification.

## TODO
- [ ] Implement method mapper
- [ ] Implement constraints
- [ ] Implement transformers
- [ ] 100% unit test coverage
- [ ] Mutation testing
