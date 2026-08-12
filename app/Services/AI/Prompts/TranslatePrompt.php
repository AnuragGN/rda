<?php

namespace App\Services\AI\Prompts;

class TranslatePrompt
{
    public static function build(string $text, string $language): string
    {
        return <<<PROMPT
            You are a professional translator.

            Translate the text below into $language.

            Rules:
            - Output ONLY the translated text. No introduction, no explanation, no labels, no quotes.
            - Preserve the original formatting, line breaks, and punctuation.
            - Keep proper nouns, brand names, and technical terms unchanged unless they have a standard $language equivalent.
            - Maintain the same tone (formal/informal) as the original.

            Text to translate:
            $text
            PROMPT;
    }
}
