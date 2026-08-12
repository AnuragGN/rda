# Product Overview

This is a **Donor-Advised Fund (DAF) management platform** built for community foundations and philanthropic organizations. It serves multiple client configurations (CCT, FFP, GNA, HGA, JCF, JSV, NIF, NTC, GMF, Mercy) from a single multi-tenant codebase.

## Core Capabilities

- DAF account creation, management, and grant recommendations
- Donor and advisor portals with role-based access
- Fund investment tracking and statement generation
- Grant application workflows (proposals, LOI, surveys)
- Charity search and vetting via Candid integration
- Donation processing via Authorize.Net and Stripe
- Real-time notifications via Pusher
- SMS delivery via Twilio
- SAML2-based SSO for enterprise clients
- 2FA authentication (email and SMS)
- AI-powered assistant for content drafting, refinement, translation, and readiness scoring

## User Roles

- **Donor** — manages DAF accounts, recommends grants, views statements
- **Agency / Advisor** — manages donor accounts on behalf of clients
- **Support Staff** — internal operations and oversight
- **Grant Seeker** — submits proposals and letters of intent
- **Admin** — super-user access (CCT client only)

## AI-Powered Assistant

Integrated across content creation areas. Supports:
- Explain Question
- Draft Answer
- Polish Answer
- Translate Answer (26+ languages)
- Proposal Readiness Score

AI responses are cached per-user. The active driver is config-based (`AI_DRIVER` env var).
