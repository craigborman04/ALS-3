# Architecture Document: The Mother
## ALS Laboratory Product Catalog v2.0

### 1. High-Level Architecture

This document outlines the architecture for the ALS Laboratory Product Catalog, a modern web application built with Next.js and a decoupled backend.

*   **Frontend:** A server-side rendered (SSR) Next.js application responsible for all UI, state management, and user interactions.
*   **Backend:** A separate, headless backend (e.g., WordPress, Strapi, or a custom microservice) that exposes data through a REST or GraphQL API. The Next.js app is a consumer of this API.
*   **Database:** Managed by the backend service. The frontend does not interact with the database directly.

### 2. File Structure & Organization (Next.js)

The project follows a standard Next.js App Router structure.

```
als-product-catalog/
├── src/
│   ├── app/                    # App Router: Pages and layouts
│   │   ├── api/                # API Routes for backend communication
│   │   ├── (components)/       # Page-specific components
│   │   ├── layout.tsx          # Root layout
│   │   └── page.tsx            # Main catalog page
│   ├── components/             # Globally shared React components
│   │   ├── ui/                 # shadcn/ui components
│   │   ├── product-card.tsx
│   │   └── filter-bar.tsx
│   ├── hooks/                  # Custom React hooks (e.g., use-debounce)
│   ├── lib/                    # Utility functions, types, mock data
│   │   ├── utils.ts
│   │   ├── types.ts
│   │   └── mock-data.ts
│   └── styles/                 # Global styles (if needed beyond globals.css)
├── public/                     # Static assets (images, fonts)
├── .eslintrc.json
├── next.config.ts
├── package.json
├── tailwind.config.ts
└── tsconfig.json
```

### 3. Data Flow

1.  A user visits a page in the Next.js application.
2.  The Next.js server (or client component) fetches data from the backend API (e.g., `/api/products`).
3.  The API retrieves data from its database and returns it as JSON.
4.  Next.js uses this data to render the React components (e.g., `ProductGrid`, `ProductCard`).
5.  Client-side interactions (like filtering or searching) update the component state, which may trigger new API calls to fetch filtered data.

### 4. Backend API Endpoints (Example)

The frontend application expects the following API endpoints to be available from the backend service.

*   `GET /api/products`: Fetches a list of all products.
    *   **Query Params:**
        *   `search_query`: A string to search by product name.
        *   `size`: A string to filter by a specific size.
        *   `limit`, `offset`: For pagination.
    *   **Response:** An array of product objects.

*   `GET /api/filters`: Fetches available options for filters.
    *   **Response:** An object containing arrays for `sizes`, `colors`, etc.
    ```json
    {
      "sizes": ["100ml", "250ml", "500ml"],
      "colors": ["Clear", "Amber"]
    }
    ```

### 5. Frontend Technology Stack

*   **Framework:** Next.js 14+ (App Router)
*   **Language:** TypeScript 5+
*   **UI Components:** `shadcn/ui`
*   **Styling:** Tailwind CSS
*   **State Management:** React Hooks (`useState`, `useEffect`, `useContext`)
*   **Data Fetching:** `fetch` API, SWR, or React Query
*   **Testing:** Vitest / React Testing Library for unit/integration tests, Playwright for E2E tests.

### 6. Build & Deployment

*   **Build:** `npm run build` creates a production-optimized Next.js application.
*   **Deployment:** The application is deployed to a platform that supports Node.js and Next.js, such as Vercel or Firebase App Hosting. Continuous deployment is configured via a CI/CD pipeline (e.g., GitHub Actions).
