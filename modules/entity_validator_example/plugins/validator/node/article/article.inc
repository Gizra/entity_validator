<?php

$plugin = array(
  'label' => t('Article'),
  'description' => t('Validate the article content type.'),
  'entity_type' => 'node',
  'bundle' => 'article',
  'class' => 'EntityValidatorExampleArticleValidator',
);
