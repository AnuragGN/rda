<?php

namespace App\Services\AI\Prompts;

class ReadnessPrompt
{
    public static function build($qaList)
    {
        $qaJson = json_encode($qaList, JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
            Act as a strict and experienced grant reviewer.

            Your task is to evaluate the quality of each answer in a grant application.

            Evaluation criteria:
            - Clarity (Is it clearly written?)
            - Relevance (Does it answer the question?)
            - Completeness (Is sufficient detail provided?)
            - Impact (Does it show meaningful outcomes?)

            Scoring rules:
            - If the answer is "No answer provided", empty, null, or irrelevant → score = 0
            - Weak / vague answer → score between 1–4
            - Average answer → score between 5–7
            - Strong answer → score between 8–10
            - Be strict and realistic. Do NOT be generous.

            Output rules (VERY IMPORTANT):
            - Return ONLY valid JSON
            - Do NOT include markdown (no ```json or ```)
            - Do NOT include any explanation or text outside JSON
            - Do NOT add comments or extra keys
            - Ensure the response starts with { and ends with }

            Output format:
            {
            "overall_score": number,
            "answers": [
                {
                "question": "string",
                "score": number,
                "feedback": "short, specific feedback"
                }
            ]
            }

            Calculation rule:
            - overall_score = average of all scores × 10 (convert to percentage)

            Data:
            $qaJson
            PROMPT;
    }
}