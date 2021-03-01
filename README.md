# ObjectMapper

## Motivation
Mapping one objects can be a cumbersome and boring task. This object-mapper package provides a structured way to map objects with a consistent implementation.

Using your favorite DI container, the `ObjectMapper` is the only service you will ever need to inject. No more thinking about which mapper(s) you need.

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
