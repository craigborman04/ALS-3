# Development & Deployment Guide
## ALS Laboratory Product Catalog v2.0

**Version:** 2.0
**Date:** July 28, 2025

---

### 1. Local Development & Prototyping

This project uses a modern Next.js stack, allowing for rapid frontend development and a seamless path to production.

#### 1.1. Environment
* **Frontend Development (Firebase Studio):** The Next.js frontend (`src/`) is developed and tested locally using the Next.js dev server. To simulate a backend during early development, you can use the **Firebase Emulator Suite** to serve mock data from local JSON files or a simple API route within the Next.js app itself can be used. This allows for complete UI development without needing a complex backend instance.

#### 1.2. Workflow
1.  Run `npm run dev` to start the Next.js dev server.
2.  Develop UI components and pages within the `src/` directory.
3.  Write and run unit/integration tests for new components (`npm run test`).
4.  Once a feature is complete, it can be tested against a staging or production API by updating the environment variables.

---

### 2. Testing Strategy: Test-After-Each-Task

Quality is built-in, not added on. Every single technical task requires immediate testing.

* **Unit Tests (Vitest/Jest):** Each React component and utility function should have a corresponding unit test file. A component is not complete until its states and props are fully tested.
* **Integration Tests (React Testing Library):** Test how components work together (e.g., does clicking a filter button update the product grid?).
* **E2E Tests (Playwright/Cypress):** End-to-end tests simulate full user journeys. These are run before any deployment to a staging or production environment.
* **Pull Requests:** No pull request will be merged unless all associated tests are passing in the CI pipeline.

---

### 3. Deployment Process

#### 3.1. Building the Application
To create a production-ready build of the application, run:
```bash
npm run build
```
This command compiles the Next.js app into an optimized set of static files, serverless functions, and middleware.

#### 3.2. Deployment
The built application can be deployed to any platform that supports Next.js, such as:
* **Vercel:** The easiest and recommended way to deploy Next.js apps.
* **Firebase App Hosting:** A secure, scalable hosting solution from Google.
* **Other Node.js environments:** AWS, Google Cloud Run, etc.

Deployment is typically handled via a CI/CD pipeline that triggers on pushes to the main branch.
