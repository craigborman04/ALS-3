# Development & Deployment Guide
## ALS Laboratory Product Catalog v2.0

**Version:** 2.0
**Date:** July 28, 2025

---

### 1. Local Development & Prototyping

This project uses a hybrid approach to allow for rapid frontend development locally while ensuring the final product is a standard WordPress plugin.

#### 1.1. Environment
* **Frontend Prototyping (Firebase Studio):** The React frontend (`assets/src`) will be developed and tested locally using Vite's dev server. To simulate the backend, you can use the **Firebase Emulator Suite** to serve mock data for products and filter options from local JSON files. This allows for complete UI development without needing a full WordPress instance running.
* **WordPress Integration:** For end-to-end testing, the plugin is developed within a Dockerized WordPress environment as defined in `docker-compose.yml`.

#### 1.2. Workflow
1.  Run `npm run dev` to start the Vite dev server for the React app.
2.  Develop UI components against mock data.
3.  Write and run unit/integration tests for the new components (`npm run test`).
4.  Once a feature is complete, test it within the Dockerized WordPress environment to ensure backend integration is working correctly.

---

### 2. Testing Strategy: Test-After-Each-Task

Quality is built-in, not added on. Every single technical task requires immediate testing.

* **Unit Tests (Vitest):** Each React component and utility function must have a corresponding unit test file. A component is not complete until its states and props are fully tested.
* **Integration Tests (React Testing Library):** Test how components work together (e.g., does clicking a filter button update the product grid?).
* **E2E Tests (Playwright):** End-to-end tests simulate full user journeys (e.g., logging in, filtering, requesting a quote). These are run before any deployment to a staging or production environment.
* **Pull Requests:** No pull request will be merged unless all associated tests are passing in the CI pipeline.

---

### 3. Deployment Process

#### 3.1. Building the Plugin
To create a production-ready package, run:
```bash
npm run build:production