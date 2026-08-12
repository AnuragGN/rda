<?php

namespace App\Services\AI\Prompts;

class ExplainQuestionPrompt
{
    public static function build(string $question): string
    {
        return <<<PROMPT
            You are a helpful assistant that clarifies what a question is asking.

            Explain the question below in plain, simple language so the reader understands what is being asked.

            Rules:
            - Output ONLY the explanation. No introduction, no labels, no quotes.
            - Do NOT answer the question or suggest what the answer should include.
            - Do NOT add opinions, assumptions, or information not present in the question.
            - Rephrase in clear, concise language — one short paragraph.
            - If the question contains technical terms, briefly clarify them in plain English.

            Question to explain:
            $question
            PROMPT;
    }
}
