[![Build Status](https://travis-ci.org/Gizra/entity_validator.svg?branch=7.x-1.x)](https://travis-ci.org/Gizra/entity_validator)

# Entity Validator
The entity validator try to solve a simple problem in Drupal 7: validate the
entity object before they written into the DB.

The first thought that cross your head is: "When is submit a node's form i get
errors. What's the problem?". The problem is that the validation is done in the
form level. If you take for example the feed module you can understand the issue
a little bit more. The feed module can create nodes without titles. This is
wrong since the node title is a required field.

## The basics
*All the example are taken form the entity validator example module.*

You'll first need to declare a ctools plugin directory for you module:
```php
/**
 * Implements hook_ctools_plugin_directory().
 */
function entity_validator_example_ctools_plugin_directory($module, $plugin) {
  if ($module == 'entity_validator') {
    return 'plugins/' . $plugin;
  }
}

```

After that you'll need to create a the next structure:
|- validator

|-- *entity_type*

|---- *entity_bundle*

|------ *entity_type*__*entity_bundle*.inc

|------ *name_of_class*.class.php

## Credits

* Developerd by [Gizra](http://gizra.com)
* Sponsored by [Harvard OpenScholar](http://openscholar.harvard.edu/)
