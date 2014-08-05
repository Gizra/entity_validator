[![Build Status](https://travis-ci.org/Gizra/entity_validator.svg?branch=7.x-1.x)](https://travis-ci.org/Gizra/entity_validator)

# Entity Validator
The entity validator try to solve a simple problem in Drupal 7: validate the
entity object before written them into the DB. That problem solved in Drupal 8
due to the OOP approach and the understanding that the written data to the DB
need to be valid.

The first thought that cross your head is: "When is submit the node's form i get
errors. What's the problem?". The problem is that the validation is done in the
form level. If you take for example the feed module you can understand the issue
a little bit more. The feed module can create nodes without titles. This is
wrong since the node title is a required field.

## The basics
*All the example are taken from the entity validator example module.*

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

After that you'll need to create the next files structure:
|- validator

|-- *entity_type*

|---- *entity_bundle*

|------ *entity_type*__*entity_bundle*.inc

|------ *name_of_class*.class.php

If we look on the entity_validator_example module you'll see a file called
*node__article.inc*. This is the plugin definition:
```php
$plugin = array(
  'label' => t('Article'),
  'description' => t('Validate the article content type.'),
  'entity_type' => 'node',
  'bundle' => 'article',
  'class' => 'EntityValidatorExampleArticleValidator',
);

```
In this case we validate a node article. The validator handler,
*EntityValidatorExampleArticleValidator*, is in the file
*EntityValidatorExampleArticleValidator.class.php*.

## Start validating
After defining the validator handler we can start and set the method for that:
```php
  /**
   * Overrides EntityValidateBase::getFieldsInfo().
   */
  public function getFieldsInfo() {
    $fields = parent::getFieldsInfo();

    $fields['title']['validators'][] = 'validateTitleText';
    $fields['body']['validators'][] = 'validateBodyText';

    return $fields;
  }
```

In this method we defining the fields and properties handlers. There are two
type of handlers:
  - Validator - In a validator method you can set errors according to the
    value the field/property have.
  - Pre-process - Although this is not in use, you can change the field/property
    value.

The next example set validators to the title property and the body field.
Although required fields or a mandatory properties are automatically checked
for not being empty, the example module add another validators as a proof of
concept:
```php
  /**
   * Validate the title is at least 3 characters long.
   */
  public function validateTitleText($field_name, $value) {
    if (strlen($value) < 3) {
      $this->setError($field_name, 'The @field should be at least 3 characters long.');
    }
  }

  /**
   * Validate the description has the word "Gizra".
   */
  public function validateBodyText($field_name, $value) {
    if (empty($value['value']) || strpos($value['value'], 'Drupal') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Drupal".');
    }
  }
```

You can see that the *validateTitleText* method checking the length of the
string.
You can also see that the the text we set as an error did not pass
through t(). That's correct. When displaying the errors to the user, by default,
the text is passed via t(). We get to this part later.

Another handler is the pre-process handler:
```php
  /**
   * Overrides EntityValidateBase::getFieldsInfo().
   */
  public function getFieldsInfo() {
    $fields = parent::getFieldsInfo();

    $fields['title']['preprocess'][] = 'preprocessTitleText';

    return $fields;
  }

  /**
   * Altering the title of the node.
   */
  public function preprocessTitleText($field_name, $value) {
    return 'This is a new title';
  }
```

## Interact with the entity validator
After setting the methods you, or of your modules, need to validate an object.
First initialize the object it self:

```php
$handler = entity_validator_get_validator_handler('node', 'article');
```

You can validate an object in three ways:
1. With exceptions:
```php
$handler->validate($node);
```
If there any errors set by the methods you'll get them as an exceptions.

2. If you just want to see if there any error, without getting exceptions, you
can use a silent mode:

```php
$result = $handler->validate($node, TRUE);
```
The $result will be a boolean variable.

3. You can handle the errors by your self. You'll need first to validate the
object in a silent way, exactly like in 2. You can get the errors squashed, all
the placeholders from the errors replaced by the real value, i.e: @field
replaced with the name of the field:

```php
    $result = $handler->validate($node, TRUE);
    if($result) {
      drupal_set_message(t('Validate in silent mode did not throw an exception.'), 'error');
    }
```

You can get the errors without being squashed. This will return an array in the
next format:
```php
    $result = $handler->getErrors(FALSE);
    $expected_result = array(
      'title' => array(
        array(
          'message' => 'The field @field cannot be empty.',
          'params' => array(
            '@field' => 'title',
          ),
        ),
        array(
          'message' => 'The @field should be at least 3 characters long.',
          'params' => array(
            '@field' => 'title',
          ),
        ),
      ),
    );
```

## Credits

* Developerd by [Gizra](http://gizra.com)
* Sponsored by [Harvard OpenScholar](http://openscholar.harvard.edu/)
