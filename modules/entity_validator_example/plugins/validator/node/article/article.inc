<?php

$plugin = array(
  'label' => t('Article'),
  'description' => t('Validating the article content type.'),
  'bundle' => 'article',
  'entity_type' => 'node',
  'class' => 'NodeValidatorExampleArticleValidator',
);
