<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'property' => 'none',
            ],
        ],
        'types_spaces' => ['space' => 'none'],
        'nullable_type_declaration' => ['syntax' => 'question_mark'],
        'static_lambda' => true,
    ])
    ->setFinder($finder);
