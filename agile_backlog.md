# Agile Product Backlog
## ALS Laboratory Product Catalog v2.0

**Status:** Ready for Sprint 1 Planning

---

### Epic 1: Foundation & Setup

* **User Story:** As a developer, I need a fully configured local environment so I can start building the plugin efficiently.
    * **Technical Tasks:**
        * [x] Configure Next.js project with TypeScript.
        * [x] Configure `tailwind.config.ts` for the React app.
        * [x] Install all npm dependencies and `shadcn/ui`.
        * [x] Set up ESLint, Prettier, and TypeScript configs.
    * **Testing Tasks:**
        * [x] Verify the Next.js dev server starts successfully.
        * [x] Confirm the React app loads in the browser.

* **User Story:** As a developer, I need the core UI components and data management logic in place.
    * **Technical Tasks:**
        * [x] Create the main page layout to host the catalog.
        * [x] Create mock data for products and filters.
        * [x] Implement state management for filtering and search.
    * **Testing Tasks:**
        * [x] Manually verify that the React app mounts and displays mock data.

---

### Epic 2: Product Catalog View

* **User Story:** As a researcher, I want to see a grid of product cards with their image, name, and size.
    * **Technical Tasks:**
        * [x] Create the `ProductCard.tsx` component using `shadcn/ui` Card.
        * [x] Create the `ProductGrid.tsx` component for responsive layout.
        * [x] Create a mock JSON data file for products.
        * [x] Fetch and display mock data in the grid.
    * **Testing Tasks:**
        * [ ] Write unit tests for `ProductCard` to ensure it renders all props correctly.
        * [ ] Write an integration test for `ProductGrid` to verify it displays multiple cards from mock data.

* **User Story:** As a user, I want to see a skeleton loading state while the products are being fetched.
    * **Technical Tasks:**
        * [x] Create a `CardSkeleton.tsx` component using `shadcn/ui` Skeleton.
        * [x] Modify `ProductGrid.tsx` to show skeletons before data arrives.
    * **Testing Tasks:**
        * [ ] Write a unit test to verify the skeleton state renders correctly.

---

### Epic 3: Filtering & Search

* **User Story:** As a lab manager, I want to filter products by size using a dropdown menu.
    * **Technical Tasks:**
        * [x] Create the `FilterBar.tsx` component.
        * [x] Add a `shadcn/ui` Select component for "Size".
        * [x] Create a mock JSON file for filter options.
        * [x] Implement state management for the size filter.
        * [x] Filter the products displayed in the grid based on the selected size.
    * **Testing Tasks:**
        * [ ] Write a unit test for `FilterBar` to ensure the dropdown renders options.
        * [ ] Write an integration test to verify that changing the filter updates the `ProductGrid`.

* **User Story:** As a researcher, I want to use a search bar to find products by name.
    * **Technical Tasks:**
        * [x] Add a `shadcn/ui` Input component to `FilterBar.tsx`.
        * [x] Implement a `useDebounce` hook to prevent excessive re-renders on typing.
        * [x] Add search logic to the filtering state.
    * **Testing Tasks:**
        * [ ] Write an integration test to verify that typing in the search bar filters the product grid.

---

### Epic 4: Backend Integration

* **User Story:** As a developer, I need a REST API endpoint to fetch the product list so the frontend can display real data.
    * **Technical Tasks:**
        * [ ] Create API route in Next.js (`/api/products`).
        * [ ] Implement the handler to return products as JSON (from a DB or mock source).
        * [ ] Implement caching for the API response.
    * **Testing Tasks:**
        * [ ] Write unit tests for the API handler.
        * [ ] Manually test the endpoint using a tool like Postman or browser.
