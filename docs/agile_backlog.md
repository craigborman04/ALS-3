***

### 3. `agile_backlog.md`

```markdown
# Agile Product Backlog
## ALS Laboratory Product Catalog v2.0

**Status:** Ready for Sprint 1 Planning

---

### Epic 1: Foundation & Setup

* **User Story:** As a developer, I need a fully configured local environment so I can start building the plugin efficiently.
    * **Technical Tasks:**
        * [ ] Configure `docker-compose.yml` for WordPress & DB services.
        * [ ] Configure `vite.config.ts` for the React app.
        * [ ] Install all npm dependencies and `shadcn/ui`.
        * [ ] Set up ESLint, Prettier, and TypeScript configs.
    * **Testing Tasks:**
        * [ ] Verify the Vite dev server starts successfully.
        * [ ] Confirm the Dockerized WordPress site loads.

* **User Story:** As a developer, I need the core PHP plugin files and asset loading logic in place.
    * **Technical Tasks:**
        * [ ] Create the main plugin file (`als-laboratory-catalog.php`).
        * [ ] Create the `ALS_Assets` class to enqueue Vite assets.
        * [ ] Create the shortcode `[als_lab_catalogue]` to render the React app container.
    * **Testing Tasks:**
        * [ ] Manually verify that the React app mounts on a page with the shortcode.

---

### Epic 2: Product Catalog View

* **User Story:** As a researcher, I want to see a grid of product cards with their image, name, and size.
    * **Technical Tasks:**
        * [ ] Create the `ProductCard.tsx` component using `shadcn/ui` Card.
        * [ ] Create the `ProductGrid.tsx` component for responsive layout.
        * [ ] Create a mock JSON data file for products.
        * [ ] Fetch and display mock data in the grid.
    * **Testing Tasks:**
        * [ ] Write unit tests for `ProductCard` to ensure it renders all props correctly.
        * [ ] Write an integration test for `ProductGrid` to verify it displays multiple cards from mock data.

* **User Story:** As a user, I want to see a skeleton loading state while the products are being fetched.
    * **Technical Tasks:**
        * [ ] Create a `CardSkeleton.tsx` component using `shadcn/ui` Skeleton.
        * [ ] Modify `ProductGrid.tsx` to show skeletons before data arrives.
    * **Testing Tasks:**
        * [ ] Write a unit test to verify the skeleton state renders correctly.

---

### Epic 3: Filtering & Search

* **User Story:** As a lab manager, I want to filter products by size using a dropdown menu.
    * **Technical Tasks:**
        * [ ] Create the `FilterBar.tsx` component.
        * [ ] Add a `shadcn/ui` Select component for "Size".
        * [ ] Create a mock JSON file for filter options.
        * [ ] Implement state management for the size filter.
        * [ ] Filter the products displayed in the grid based on the selected size.
    * **Testing Tasks:**
        * [ ] Write a unit test for `FilterBar` to ensure the dropdown renders options.
        * [ ] Write an integration test to verify that changing the filter updates the `ProductGrid`.

* **User Story:** As a researcher, I want to use a search bar to find products by name.
    * **Technical Tasks:**
        * [ ] Add a `shadcn/ui` Input component to `FilterBar.tsx`.
        * [ ] Implement a `useDebounce` hook to prevent excessive re-renders on typing.
        * [ ] Add search logic to the filtering state.
    * **Testing Tasks:**
        * [ ] Write an integration test to verify that typing in the search bar filters the product grid.

---

### Epic 4: Backend Integration

* **User Story:** As a developer, I need a REST API endpoint to fetch the product list so the frontend can display real data.
    * **Technical Tasks:**
        * [ ] Create `class-als-rest-api.php`.
        * [ ] Register a `/products` GET endpoint.
        * [ ] Implement the callback function to query the database and return products as JSON.
        * [ ] Implement transient caching for the database query.
    * **Testing Tasks:**
        * [ ] Write a PHP unit test for the database query method.
        * [ ] Manually test the endpoint using a tool like Postman.