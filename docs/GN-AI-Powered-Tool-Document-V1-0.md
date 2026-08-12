# AI-Powered Assistant
### For Understanding, Answer Drafting, Refinement, and Translation
**Version 1.0**

---

89 Headquarters Plaza, Suite 1446
Morristown, NJ 07960
973.984.8200

---

## Table of Contents

1. [Overview](#1-overview)
2. [Objectives](#2-objectives)
3. [AI Features](#3-ai-features)
   - 3.1 [Explain Question](#31-explain-question)
   - 3.2 [Draft Answer](#32-draft-answer)
   - 3.3 [Polish Answer](#33-polish-answer)
   - 3.4 [Translate Answer](#34-translate-answer)
   - 3.5 [Proposal Readiness Score](#35-proposal-readiness-score)
4. [Where This Feature Is Used](#4-where-this-feature-is-used)
5. [AI Feature Compatibility](#5-ai-feature-compatibility)
6. [Maximum Length Handling](#6-maximum-length-handling)
7. [AI Models & Environments](#7-ai-models--environments)
   - 7.1 [Local / Development Environment](#71-local--development-environment)
   - 7.2 [Production Environment](#72-production-environment)
   - 7.3 [Model Switching & Flexibility](#73-model-switching--flexibility)
8. [Performance & User Experience](#8-performance--user-experience)

---

## 1. Overview

The **AI-Powered Assistant** is an AI-enabled feature integrated directly into the platform's question-and-answer interface. It is designed to assist users throughout the content creation process — from understanding what a question is asking, to generating, refining, translating, and evaluating responses.

The assistant helps users:

- Understand what a question is asking
- Generate draft content
- Refine and improve existing content
- Translate content into multiple languages
- Evaluate proposal readiness with a scored, actionable report

All AI-generated output is governed by application-level rules, ensuring predictable, controlled, and reliable behaviour. The feature is designed to **assist users, not replace them**, while keeping full control with the application.

---

## 2. Objectives

The primary objectives of the AI-Powered Assistant are:

- Help users clearly understand questions before answering
- Reduce the time required to draft responses
- Improve the quality, clarity, and readability of answers
- Ensure answers remain within defined word or character limits
- Provide a modern, interactive, and intuitive user experience
- Maintain full control with the application rather than the AI model
- Enable users to review proposal readiness and apply insights to improve approval likelihood before submission

---

## 3. AI Features

Each question provides up to five AI-powered actions, represented by intuitive icons in the interface.

---

### 3.1 Explain Question

**Icon:** ❓

**Purpose:**
Explains what the question is asking in simple, clear language so the user fully understands it before responding.

| Attribute | Detail |
|---|---|
| Input | Question text |
| Output | AI-generated explanation displayed below the question |
| Length limit applied | No |

---

### 3.2 Draft Answer

**Icon:** ✍

**Purpose:**
Generates a first-draft answer based on the question and any available context (e.g. sponsor, fund, ticket type). This gives users a starting point they can review and refine.

| Attribute | Detail |
|---|---|
| Input | Question text, contextual form data, maximum length (if defined) |
| Output | AI-generated draft inserted directly into the answer field |
| Length limit applied | Yes |

---

### 3.3 Polish Answer

**Icon:** ✨

**Purpose:**
Improves the grammar, clarity, and readability of an existing answer while preserving its original meaning and language.

| Attribute | Detail |
|---|---|
| Input | Existing answer text, maximum length (if defined) |
| Output | AI-refined content replaces the existing answer in the answer field |
| Length limit applied | Yes |

> The polished output is always returned in the **same language** as the input. This action does not translate.

---

### 3.4 Translate Answer

**Icon:** 🌐

**Purpose:**
Translates the existing answer into a selected target language. Users can choose from a dropdown list of 26 supported languages or type any language manually.

| Attribute | Detail |
|---|---|
| Input | Existing answer text, selected target language |
| Output | AI-translated content replaces the existing answer in the answer field |
| Length limit applied | No |

**Supported Languages:**
Arabic, Chinese (Simplified), Chinese (Traditional), Dutch, English, French, German, Greek, Hebrew, Hindi, Indonesian, Italian, Japanese, Korean, Malay, Persian, Polish, Portuguese, Russian, Spanish, Swahili, Thai, Turkish, Ukrainian, Urdu, Vietnamese

> Users may also type any language not listed in the dropdown.

---

### 3.5 Proposal Readiness Score

**Icon:** 📊

**Purpose:**
Evaluates the overall quality of a grant proposal by scoring each answer against four criteria: **Clarity**, **Relevance**, **Completeness**, and **Impact**. The result is an overall readiness score (0–100) with per-question feedback to guide improvements before submission.

| Attribute | Detail |
|---|---|
| Input | All question-answer pairs from the proposal or survey |
| Output | Overall score (percentage) and per-question feedback |
| Length limit applied | No |

**Scoring Criteria:**

| Criterion | Description |
|---|---|
| Clarity | Is the answer clearly written and easy to understand? |
| Relevance | Does the answer directly address the question? |
| Completeness | Is sufficient detail provided? |
| Impact | Does the answer demonstrate meaningful outcomes? |

**Score Ranges:**

| Score | Interpretation |
|---|---|
| 0 | No answer provided, empty, or irrelevant |
| 1–4 | Weak or vague answer |
| 5–7 | Average answer |
| 8–10 | Strong, well-developed answer |

The overall score is calculated as the average of all individual scores, converted to a percentage (0–100).

---

## 4. Where This Feature Is Used

The AI-Powered Assistant is integrated across the platform wherever content creation, refinement, or evaluation is required. It is accessible within relevant sections through input fields and AI action buttons, allowing users to interact with AI seamlessly in context.

**Applicable areas include:**

- Grant proposal and survey question forms
- Letter of Intent (LOI) sections
- Support ticket description fields
- Any text input or text area where content is authored or reviewed

---

## 5. AI Feature Compatibility

The following table summarises which AI actions are available for each content type:

| Feature | Text Input | Text Area | Any Text Content | Content Evaluation |
|---|---|---|---|---|
| Explain | ✅ | ✅ | ✅ | — |
| Draft | ✅ | ✅ | — | — |
| Polish | ✅ | ✅ | ✅ | — |
| Translate | ✅ | ✅ | ✅ | — |
| Readiness Score | — | — | — | ✅ |

---

## 6. Maximum Length Handling

Each content item or question may optionally define a maximum allowed length for the response, ensuring clarity and consistency across submissions.

The maximum length may be:

- Mentioned within the question or instructions, or
- Displayed below the input field, where applicable

**When a limit is defined:**

- It may be based on **word count** (e.g. 500 words) or **character count** (e.g. 2000 characters)
- Users should keep their responses within the specified limit
- AI-generated content will also respect the same limit

If the AI-generated content exceeds the allowed length, it will be automatically trimmed to fit within the defined limit before being inserted into the answer field.

---

## 7. AI Models & Environments

The platform supports multiple AI backends. The active model is selected via configuration, with no changes required to the UI, controllers, or business logic.

---

### 7.1 Local / Development Environment

| Attribute | Detail |
|---|---|
| Used for | Development and testing |
| Platform | Ollama (self-hosted) |
| Example models | LLaMA-based models (e.g. Mistral) |

**Benefits:**
- No external API dependency
- Faster development and testing cycles
- All data remains within the local environment

---

### 7.2 Production Environment

| Attribute | Detail |
|---|---|
| Used for | Live applications and scalable deployments |
| Platform | OpenRouter |
| Supported models | OpenAI-compatible models, Mistral, LLaMA, Gemini, Claude, and other OpenRouter-supported models |

**Benefits:**
- High availability
- Easy model switching without code changes
- No vendor lock-in

---

### 7.3 Model Switching & Flexibility

The active AI driver is controlled entirely through the `AI_DRIVER` environment variable in the application configuration.

| Driver | Use Case |
|---|---|
| `openrouter` | Production — access to multiple hosted models |
| `gemini` | Google Gemini via direct API |
| `claude` | Anthropic Claude via direct API |
| `huggingface` | Free / self-hosted inference |
| `ollama` | Local development (no external API) |

Switching models:

- Requires only a configuration or environment variable change
- Does not impact the UI, controllers, or prompt logic
- Is transparent to end users
- Ensures long-term flexibility and cost control

---

## 8. Performance & User Experience

The AI-Powered Assistant is designed for a fast, seamless, and non-disruptive experience:

- No page reloads during any AI action
- Clear "Processing…" status indicators shown during requests
- AI action buttons are disabled while a request is in progress to prevent duplicate submissions
- AI responses are cached per user to avoid redundant API calls and reduce latency on repeated requests
- Fast and responsive interactions with minimal perceived delay
- Clean, minimal, and intuitive interface that integrates naturally into existing workflows
