<?php
// .php-cs-fixer.php - PHP CS Fixer Configuration
// Alwin Fahrozi Marbun - CI/Testing Engineer

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/tests',
    ])
    ->exclude([
        'vendor',
        'storage',
        'public',
        'coverage',
        'node_modules',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();
$config
    ->setFinder($finder)
    ->setRules([
        // PSR Standards
        '@PSR12' => true,
        
        // Array notation
        'array_syntax' => ['syntax' => 'short'],
        'array_indentation' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        
        // Binary operators
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        
        // Casts
        'cast_spaces' => ['space' => 'single'],
        'lowercase_cast' => true,
        'short_scalar_cast' => true,
        
        // Classes
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
            ]
        ],
        'no_blank_lines_after_class_opening' => true,
        
        // Comments
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'multiline_comment_opening_closing' => true,
        
        // Control structures
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        
        // Functions
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_spaces_after_function_name' => true,
        'return_type_declaration' => ['space_before' => 'none'],
        
        // Imports
        'fully_qualified_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
        'single_import_per_statement' => true,
        
        // Language constructs
        'declare_equal_normalize' => ['space' => 'none'],
        'new_with_braces' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        
        // Operators
        'object_operator_without_whitespace' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        
        // PHP Tags
        'blank_line_after_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        'no_closing_tag' => true,
        
        // PHPDoc
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        
        // Semicolons
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'semicolon_after_instruction' => true,
        
        // Strings
        'single_quote' => ['strings_containing_single_quote_chars' => false],
        
        // Variables
        'no_trailing_comma_in_singleline_array' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        
        // Whitespace
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try', 'if', 'switch', 'for', 'foreach', 'while', 'do']
        ],
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => ['extra', 'throw', 'use', 'use_trait']
        ],
        'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
        'no_whitespace_in_blank_line' => true,
    ])
    ->setLineEnding("\n")
    ->setIndent('    ') // 4 spaces
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

return $config;