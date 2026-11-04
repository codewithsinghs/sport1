<?php

namespace App\Modules\Content\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Whitelist sanitizer for association-authored rich text. Quill output is
 * normalised on save and again when rendered so stored markup can never
 * introduce scripts, event handlers or javascript: links.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'ul', 'ol', 'li',
        'h2', 'h3', 'h4', 'blockquote', 'a', 'span',
    ];

    private const DANGEROUS_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button',
        'textarea', 'select', 'option', 'svg', 'math', 'link', 'meta', 'base',
        'template', 'noscript',
    ];

    private const ALLOWED_CLASSES = [
        'ql-align-center', 'ql-align-right', 'ql-align-justify',
        'ql-indent-1', 'ql-indent-2', 'ql-indent-3', 'ql-indent-4',
        'ql-indent-5', 'ql-indent-6', 'ql-indent-7', 'ql-indent-8',
    ];

    public function clean(?string $html): string
    {
        if (! is_string($html) || trim($html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $loaded = $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="sanitizer-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('sanitizer-root');

        if (! $loaded || $root === null) {
            return htmlspecialchars(strip_tags($html), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        $this->sanitizeChildren($root);

        $output = '';

        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim($output);
    }

    private function sanitizeChildren(DOMNode $parent): void
    {
        $child = $parent->firstChild;

        while ($child !== null) {
            $next = $child->nextSibling;

            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);

                if (in_array($tag, self::DANGEROUS_TAGS, true)) {
                    $parent->removeChild($child);
                } elseif (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    $this->sanitizeChildren($child);
                    $this->unwrap($child);
                } else {
                    $this->sanitizeAttributes($child);
                    $this->sanitizeChildren($child);
                }
            } elseif ($child->nodeType === XML_COMMENT_NODE) {
                $parent->removeChild($child);
            }

            $child = $next;
        }
    }

    private function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if ($parent === null) {
            return;
        }

        while ($element->firstChild !== null) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private function sanitizeAttributes(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $href = null;
        $target = null;
        $classes = [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $value = trim($attribute->value);

            if ($tag === 'a' && $name === 'href' && $this->isSafeLink($value)) {
                $href = $value;
            } elseif ($tag === 'a' && $name === 'target' && $value === '_blank') {
                $target = '_blank';
            } elseif ($name === 'class') {
                $classes = array_values(array_filter(
                    preg_split('/\s+/', $value) ?: [],
                    fn (string $class): bool => in_array($class, self::ALLOWED_CLASSES, true)
                ));
            }

            $element->removeAttribute($attribute->name);
        }

        if ($tag === 'a' && $href !== null) {
            $element->setAttribute('href', $href);

            if ($target === '_blank') {
                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        if ($classes !== []) {
            $element->setAttribute('class', implode(' ', $classes));
        }
    }

    private function isSafeLink(string $value): bool
    {
        if ($value === '' || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            return false;
        }

        if (str_starts_with($value, '/') || str_starts_with($value, '#')) {
            return true;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);

        if ($scheme === null || $scheme === false) {
            return false;
        }

        return in_array(strtolower((string) $scheme), ['http', 'https', 'mailto', 'tel'], true);
    }
}
