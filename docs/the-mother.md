# The Mother - ALS Laboratory Product Catalog Plugin Rebuild Architecture

**Complete Blueprint for Modern WordPress Plugin Development with shadcn/ui Integration**

*Generated: January 28, 2025 | Version: 2.0 Architecture*

---

## Table of Contents

1. [Project Overview & Philosophy](#project-overview--philosophy)
2. [Current State Analysis](#current-state-analysis)
3. [Database Architecture (Preserved)](#database-architecture-preserved)
4. [Modern Frontend Architecture with shadcn/ui](#modern-frontend-architecture-with-shadcnui)
5. [WordPress Plugin Architecture (Enhanced)](#wordpress-plugin-architecture-enhanced)
6. [Development Workflow & Best Practices](#development-workflow--best-practices)
7. [Design System Integration](#design-system-integration)
8. [Testing & Quality Assurance](#testing--quality-assurance)
9. [Security & Performance](#security--performance)
10. [Implementation Roadmap](#implementation-roadmap)
11. [File Structure & Organization](#file-structure--organization)
12. [Component Specifications](#component-specifications)
13. [API Design & Documentation](#api-design--documentation)
14. [Deployment & Maintenance](#deployment--maintenance)
15. [Comprehensive Ultrathink Prompt](#comprehensive-ultrathink-prompt)

---

## Project Overview & Philosophy

### Mission Statement
Rebuild the ALS Laboratory Product Catalog WordPress plugin with modern development practices, shadcn/ui design system, and enhanced user experience while preserving all existing functionality, database structures, and business logic.

### Core Principles
1. **Preserve All Existing Functionality** - No loss of features or data structures
2. **Modern Development Stack** - TypeScript, shadcn/ui, Tailwind CSS from day one
3. **WordPress Standards Compliance** - Security, performance, and plugin guidelines
4. **Incremental Development** - Todo.md workflow with measurable progress
5. **Professional Laboratory Aesthetic** - DNA Online design integration
6. **Scalable Architecture** - Clean code, dependency injection, testable components

### Success Metrics
- **User Experience**: 50% reduction in quote completion time
- **Developer Experience**: 75% faster component development with shadcn/ui
- **Performance**: < 2s page load time, 95+ Lighthouse scores
- **Maintainability**: 80% test coverage, documented APIs
- **Business Impact**: 25% increase in quote requests, improved conversion rates

---

## Current State Analysis

### What Works Well (Preserve)
```
✅ Database Schema - 5 tables with proper relationships
✅ Contact Form 7 Integration - Reliable quote processing  
✅ Admin Interface Structure - Complete CRUD operations
✅ Security Implementation - Nonce verification, sanitization
✅ Development Workflow - Docker, MCP, incremental tasks
✅ Product Filtering Logic - Size, color, closure combinations
✅ Multi-currency Support - Geolocation detection
✅ Quote Management - Database storage, admin notifications
```

### Current Pain Points (Rebuild Focus)
```
❌ Frontend Complexity - Mixed React/vanilla JS causing errors
❌ Styling Inconsistency - Multiple CSS approaches, no design system
❌ Component Reusability - Monolithic JavaScript functions
❌ Type Safety - No TypeScript, runtime errors
❌ Accessibility - Limited ARIA support, keyboard navigation
❌ Mobile Experience - Responsive issues, touch interactions
❌ Developer Tooling - Limited IntelliSense, debugging
❌ Build Process - No modern bundling, optimization
```

### Architecture Evolution
```
Current: WordPress Plugin + Mixed Frontend + Custom CSS
         ↓
New:     WordPress Plugin + shadcn/ui + TypeScript + Tailwind
```

---

## Database Architecture (Preserved)

### Schema Overview
*Note: Preserve exactly as-is - no modifications to table structure*

```sql
-- Products Table (Primary entities)
CREATE TABLE wp_als_lab_catalogue_products (
    product_id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description text,
    size varchar(50) NOT NULL,
    default_color varchar(50),
    default_color_hex varchar(7),
    category varchar(100),
    tags text,
    sort_order int(11) DEFAULT 0,
    image_url varchar(500),
    is_active tinyint(1) DEFAULT 1,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id),
    KEY idx_category (category),
    KEY idx_size (size),
    KEY idx_active (is_active)
);

-- Variations Table (Color/closure combinations)
CREATE TABLE wp_als_lab_catalogue_variations (
    variation_id bigint(20) NOT NULL AUTO_INCREMENT,
    product_id bigint(20) NOT NULL,
    closure_type varchar(100),
    color_name varchar(50),
    color_hex varchar(7),
    part_number varchar(100),
    price_modifier decimal(10,2) DEFAULT 0.00,
    capacity varchar(50),
    dimensions varchar(100),
    weight decimal(8,2),
    is_active tinyint(1) DEFAULT 1,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (variation_id),
    KEY idx_product_id (product_id),
    KEY idx_closure_type (closure_type),
    KEY idx_color (color_name),
    KEY idx_active (is_active),
    FOREIGN KEY (product_id) REFERENCES wp_als_lab_catalogue_products(product_id) ON DELETE CASCADE
);

-- Closures Table (Closure type definitions)
CREATE TABLE wp_als_lab_catalogue_closures (
    closure_id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description text,
    sort_order int(11) DEFAULT 0,
    is_active tinyint(1) DEFAULT 1,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (closure_id),
    UNIQUE KEY uk_closure_name (name),
    KEY idx_active (is_active)
);

-- Currencies Table (Multi-currency support)
CREATE TABLE wp_als_lab_catalogue_currencies (
    currency_id bigint(20) NOT NULL AUTO_INCREMENT,
    currency_code varchar(3) NOT NULL,
    currency_name varchar(50) NOT NULL,
    currency_symbol varchar(10),
    exchange_rate decimal(10,4) DEFAULT 1.0000,
    is_default tinyint(1) DEFAULT 0,
    is_active tinyint(1) DEFAULT 1,
    last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (currency_id),
    UNIQUE KEY uk_currency_code (currency_code),
    KEY idx_default (is_default),
    KEY idx_active (is_active)
);

-- Quotes Table (Contact Form 7 integration storage)
CREATE TABLE wp_als_lab_catalogue_quotes (
    quote_id bigint(20) NOT NULL AUTO_INCREMENT,
    product_id bigint(20) NOT NULL,
    product_name varchar(255),
    size varchar(50),
    closure_type varchar(100),
    color varchar(50),
    quantity int(11) NOT NULL,
    box_quantity int(11),
    unit_price decimal(10,2),
    total_price decimal(10,2),
    currency varchar(3) DEFAULT 'USD',
    customer_name varchar(255),
    customer_email varchar(255),
    customer_phone varchar(50),
    company_name varchar(255),
    message text,
    status varchar(50) DEFAULT 'pending',
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (quote_id),
    KEY idx_product_id (product_id),
    KEY idx_customer_email (customer_email),
    KEY idx_status (status),
    KEY idx_created_at (created_at)
);
```

### Data Relationships
```
Products (1) → (Many) Variations
Products (1) → (Many) Quotes  
Closures (1) → (Many) Variations
Currencies (1) → (Many) Quotes
```

### Data Migration Strategy
```typescript
// Preserve all existing data during rebuild
interface MigrationPlan {
  phase1: "Export current data to JSON backup";
  phase2: "Install new plugin with identical schema";
  phase3: "Import data with duplicate prevention";
  phase4: "Verify data integrity and relationships";
  rollback: "Restore from backup if issues occur";
}
```

---

## Modern Frontend Architecture with shadcn/ui

### Technology Stack
```json
{
  "core": {
    "framework": "React 18+",
    "language": "TypeScript 5+",
    "styling": "Tailwind CSS 3+",
    "components": "shadcn/ui latest",
    "build": "Vite 5+ with WordPress integration"
  },
  "supporting": {
    "state": "React Context + useReducer",
    "forms": "React Hook Form + Zod validation",
    "data": "TanStack Query for AJAX",
    "icons": "Lucide React",
    "animations": "Framer Motion"
  }
}
```

### Component Architecture
```
App Root (WordPress Container)
├── ProductCatalogProvider (Global State)
├── Header (Filters + Search)
│   ├── FilterSelect (shadcn Select)
│   ├── SearchInput (shadcn Input)
│   └── FilterReset (shadcn Button)
├── ProductGrid (Responsive Layout)
│   └── ProductCard[] (shadcn Card)
│       ├── ProductImage (Custom)
│       ├── ProductInfo (Typography)
│       └── QuoteButton (shadcn Button)
├── LoadingStates (shadcn Skeleton)
├── ErrorBoundary (Custom)
└── QuoteModal (shadcn Dialog) - CF7 Integration
```

### shadcn/ui Configuration
```typescript
// tailwind.config.js - DNA Online Theme
module.exports = {
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        // DNA Online Color Palette
        primary: {
          50: '#eff7ff',
          100: '#dbeeff', 
          500: '#0866a8', // Primary blue
          600: '#0654a3', // Hover state
          900: '#0c2340'
        },
        laboratory: {
          50: '#f0fdf4',
          100: '#e7f0e7', // Background green
          500: '#22c55e',
          600: '#16a34a',
          900: '#14532d'
        },
        accent: {
          amber: '#fbbf24',
          red: '#dc2626',
          orange: '#ea580c'
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif']
      },
      borderRadius: {
        lg: '6px', // DNA Online standard
        md: '4px',
        sm: '2px'
      }
    }
  },
  plugins: [require("tailwindcss-animate")]
}
```

### Component Examples
```tsx
// ProductCard.tsx - shadcn/ui Integration
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"

interface ProductCardProps {
  product: Product;
  onQuoteRequest: (product: Product) => void;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product, onQuoteRequest }) => {
  return (
    <Card className="group hover:shadow-lg transition-all duration-200 bg-white border-laboratory-100">
      <CardHeader className="pb-3">
        <div className="relative aspect-[4/3] bg-laboratory-50 rounded-md overflow-hidden mb-3">
          <ProductImage 
            src={product.image_url} 
            alt={product.name}
            size={product.size}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
          />
        </div>
        <CardTitle className="text-lg font-semibold text-gray-900 line-clamp-2">
          {product.name}
        </CardTitle>
        <div className="flex items-center gap-2">
          <Badge variant="secondary" className="bg-primary-50 text-primary-700">
            {product.size}
          </Badge>
          {product.variation_count > 1 && (
            <Badge variant="outline" className="text-xs">
              {product.variation_count} options
            </Badge>
          )}
        </div>
      </CardHeader>
      <CardContent className="pt-0">
        {product.description && (
          <p className="text-sm text-gray-600 mb-4 line-clamp-2">
            {product.description}
          </p>
        )}
        <Button 
          onClick={() => onQuoteRequest(product)}
          className="w-full bg-primary-500 hover:bg-primary-600 text-white"
          size="sm"
        >
          Request Quote
        </Button>
      </CardContent>
    </Card>
  );
};
```

---

## WordPress Plugin Architecture (Enhanced)

### Plugin Structure
```
als-laboratory-catalogue/
├── als-laboratory-catalog.php          # Main plugin file
├── includes/                          # PHP classes
│   ├── class-als-plugin.php          # Main plugin class
│   ├── class-als-database.php        # Database operations
│   ├── class-als-ajax-handler.php    # AJAX endpoints
│   ├── class-als-admin-menu.php      # Admin interface
│   ├── class-als-cf7-integration.php # Contact Form 7
│   ├── class-als-assets.php          # Asset management
│   ├── class-als-security.php        # Security utilities
│   └── class-als-api.php             # REST API endpoints
├── assets/                           # Frontend assets
│   ├── dist/                        # Built assets (Vite output)
│   ├── src/                         # Source files
│   │   ├── components/              # React components
│   │   ├── hooks/                   # Custom hooks
│   │   ├── types/                   # TypeScript definitions
│   │   ├── utils/                   # Utility functions
│   │   └── main.tsx                 # Entry point
│   └── css/                         # Legacy CSS (phase out)
├── data/                            # Data files
├── languages/                       # Translations
├── tests/                           # PHP and frontend tests
├── package.json                     # Node dependencies
├── vite.config.ts                   # Build configuration
├── tsconfig.json                    # TypeScript config
└── tailwind.config.js              # Tailwind config
```

### Main Plugin Class (Enhanced)
```php
<?php
/**
 * Main Plugin Class - Enhanced Architecture
 */
class ALS_Laboratory_Catalogue {
    
    private string $version = '2.0.0';
    private ALS_Database $database;
    private ALS_Assets $assets;
    private ALS_Security $security;
    
    public function __construct() {
        $this->init_dependencies();
        $this->init_hooks();
    }
    
    private function init_dependencies(): void {
        // Dependency injection for better testing
        $this->database = new ALS_Database();
        $this->assets = new ALS_Assets($this->version);
        $this->security = new ALS_Security();
        
        // Initialize other components
        new ALS_Ajax_Handler($this->database, $this->security);
        new ALS_CF7_Integration($this->database);
        
        if (is_admin()) {
            new ALS_Admin_Menu($this->database, $this->assets);
        }
    }
    
    private function init_hooks(): void {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueue_public_assets']);
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueue_admin_assets']);
        
        // REST API endpoints
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        
        // Shortcode registration
        add_shortcode('als_lab_catalogue', [$this, 'catalog_shortcode']);
    }
    
    public function catalog_shortcode(array $atts): string {
        $atts = shortcode_atts([
            'category' => '',
            'products_per_page' => 12,
            'show_filters' => true,
            'color_scheme' => 'dna_online'
        ], $atts);
        
        // Enqueue React app
        $this->assets->enqueue_catalog_app($atts);
        
        // Return container for React mounting
        $container_id = 'als-catalog-' . uniqid();
        return sprintf(
            '<div id="%s" class="als-laboratory-catalog" data-settings="%s"></div>',
            esc_attr($container_id),
            esc_attr(json_encode($atts))
        );
    }
}
```

### Asset Management (Vite Integration)
```php
<?php
/**
 * Enhanced Asset Management with Vite
 */
class ALS_Assets {
    
    private string $version;
    private bool $is_development;
    
    public function __construct(string $version) {
        $this->version = $version;
        $this->is_development = defined('WP_DEBUG') && WP_DEBUG;
    }
    
    public function enqueue_catalog_app(array $settings): void {
        if ($this->is_development) {
            // Development: Vite dev server
            wp_enqueue_script(
                'vite-client',
                'http://localhost:5173/@vite/client',
                [],
                null,
                false
            );
            wp_enqueue_script(
                'als-catalog-app',
                'http://localhost:5173/src/main.tsx',
                [],
                null,
                true
            );
        } else {
            // Production: Built assets
            $manifest = $this->get_vite_manifest();
            
            wp_enqueue_script(
                'als-catalog-app',
                ALS_LAB_CATALOG_PLUGIN_URL . 'assets/dist/' . $manifest['src/main.tsx']['file'],
                [],
                $this->version,
                true
            );
            
            wp_enqueue_style(
                'als-catalog-styles',
                ALS_LAB_CATALOG_PLUGIN_URL . 'assets/dist/' . $manifest['src/main.tsx']['css'][0],
                [],
                $this->version
            );
        }
        
        // Localize data for React app
        wp_localize_script('als-catalog-app', 'alsLabConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('als/v1/'),
            'nonce' => wp_create_nonce('als_nonce'),
            'settings' => $settings,
            'strings' => $this->get_i18n_strings(),
            'theme' => $this->get_theme_config()
        ]);
    }
    
    private function get_vite_manifest(): array {
        $manifest_path = ALS_LAB_CATALOG_PLUGIN_DIR . 'assets/dist/manifest.json';
        return file_exists($manifest_path) 
            ? json_decode(file_get_contents($manifest_path), true)
            : [];
    }
}
```

---

## Development Workflow & Best Practices

### Prerequisites Setup
```bash
# Required software
- Node.js 18+ with npm 8+
- Docker Desktop 4+
- WordPress CLI
- Git with GitHub CLI
- Claude Desktop with MCP servers

# MCP Server Configuration
npm run mcp:install  # Playwright, Docker, shadcn/ui MCPs
```

### Development Environment
```yaml
# docker-compose.yml - Enhanced
version: '3.8'
services:
  wordpress-dev:
    image: wordpress:latest
    ports: ["8080:80"]
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DEBUG: 1
    volumes:
      - ./als-laboratory-catalogue:/var/www/html/wp-content/plugins/als-laboratory-catalogue
      - ./uploads:/var/www/html/wp-content/uploads
    
  db:
    image: mysql:8.0
    ports: ["3306:3306"]
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql
  
  vite-dev:
    image: node:18
    working_dir: /app
    ports: ["5173:5173"]
    volumes:
      - ./als-laboratory-catalogue:/app
    command: npm run dev
    environment:
      - NODE_ENV=development
      
  playwright-tests:
    image: mcr.microsoft.com/playwright:v1.40.0-focal
    working_dir: /app
    volumes:
      - .:/app
    depends_on: [wordpress-dev, vite-dev]

volumes:
  db_data:
```

### Build Configuration
```typescript
// vite.config.ts - WordPress Integration
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  root: 'assets',
  build: {
    outDir: 'dist',
    manifest: true,
    rollupOptions: {
      input: 'src/main.tsx',
      external: ['jquery'], // WordPress provides jQuery
      output: {
        globals: {
          jquery: 'jQuery'
        }
      }
    }
  },
  server: {
    port: 5173,
    host: '0.0.0.0'
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
      '@/components': path.resolve(__dirname, 'assets/src/components'),
      '@/hooks': path.resolve(__dirname, 'assets/src/hooks'),
      '@/types': path.resolve(__dirname, 'assets/src/types'),
      '@/utils': path.resolve(__dirname, 'assets/src/utils')
    }
  }
})
```

### Package Configuration
```json
{
  "name": "als-laboratory-catalogue",
  "version": "2.0.0",
  "private": true,
  "scripts": {
    "dev": "vite",
    "build": "tsc && vite build",
    "preview": "vite preview",
    "test": "vitest",
    "test:e2e": "playwright test",
    "lint": "eslint . --ext ts,tsx --report-unused-disable-directives --max-warnings 0",
    "lint:fix": "eslint . --ext ts,tsx --fix",
    "type-check": "tsc --noEmit",
    "docker:dev": "docker-compose up -d",
    "docker:build": "docker-compose build",
    "docker:logs": "docker-compose logs -f",
    "wp:setup": "wp-env start && wp-env run cli wp plugin activate als-laboratory-catalogue"
  },
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "@radix-ui/react-dialog": "^1.0.5",
    "@radix-ui/react-select": "^2.0.0",
    "@radix-ui/react-card": "^1.0.4",
    "class-variance-authority": "^0.7.0",
    "clsx": "^2.0.0",
    "lucide-react": "^0.294.0",
    "tailwindcss": "^3.3.0",
    "tailwind-merge": "^2.0.0",
    "@tanstack/react-query": "^5.8.4",
    "react-hook-form": "^7.47.0",
    "zod": "^3.22.4",
    "framer-motion": "^10.16.4"
  },
  "devDependencies": {
    "@types/react": "^18.2.37",
    "@types/react-dom": "^18.2.15",
    "@types/wordpress__blocks": "^12.5.0",
    "@typescript-eslint/eslint-plugin": "^6.10.0",
    "@typescript-eslint/parser": "^6.10.0",
    "@vitejs/plugin-react": "^4.1.1",
    "eslint": "^8.53.0",
    "eslint-plugin-react-hooks": "^4.6.0",
    "eslint-plugin-react-refresh": "^0.4.4",
    "typescript": "^5.2.2",
    "vite": "^5.0.0",
    "vitest": "^1.0.0",
    "playwright": "^1.40.0",
    "@wp-env/cli": "^8.7.0"
  }
}
```

### Todo.md Workflow Integration
```markdown
# Development Workflow Rules

## Task Creation
- [ ] **Task Name**: Clear, actionable description
  - Priority: High/Medium/Low
  - Estimated time: Hours (realistic estimates)
  - Dependencies: List blocking tasks
  - Acceptance criteria: Specific, testable requirements
  - Files to modify: Exact file paths
  - Status: pending → in_progress → completed

## Progress Tracking
1. Create task in todo.md
2. Mark as in_progress when starting
3. Update progress in comments/notes
4. Mark completed when acceptance criteria met
5. Add completion notes with implementation details

## Session Management
- Start each session by reviewing todo.md
- Identify highest priority ready tasks
- Update status in real-time during work
- End session with progress summary
- Plan next session priorities
```

---

## Design System Integration

### shadcn/ui Component Library
```bash
# Installation and setup
npx shadcn-ui@latest init
npx shadcn-ui@latest add button card dialog input select badge skeleton
npx shadcn-ui@latest add form alert-dialog dropdown-menu command
```

### DNA Online Theme Implementation
```typescript
// Design tokens
export const dnaTheme = {
  colors: {
    primary: {
      50: '#eff7ff',
      100: '#dbeeff',
      200: '#bfdbfe',
      300: '#93c5fd',
      400: '#60a5fa',
      500: '#0866a8', // Main brand blue
      600: '#0654a3', // Hover state
      700: '#1d4ed8',
      800: '#1e40af',
      900: '#0c2340'
    },
    laboratory: {
      50: '#f0fdf4',
      100: '#e7f0e7', // Light green background
      200: '#bbf7d0',
      300: '#86efac',
      400: '#4ade80',
      500: '#22c55e',
      600: '#16a34a',
      700: '#059669',
      800: '#047857',
      900: '#14532d'
    },
    vial: {
      clear: '#ffffff',
      blue: '#0066cc',
      red: '#dc2626',
      orange: '#ea580c',
      yellow: '#eab308',
      purple: '#9333ea'
    }
  },
  typography: {
    fontFamily: {
      sans: ['Inter', 'system-ui', 'sans-serif'],
      mono: ['JetBrains Mono', 'monospace']
    },
    fontSize: {
      xs: ['0.75rem', { lineHeight: '1rem' }],
      sm: ['0.875rem', { lineHeight: '1.25rem' }],
      base: ['1rem', { lineHeight: '1.5rem' }],
      lg: ['1.125rem', { lineHeight: '1.75rem' }],
      xl: ['1.25rem', { lineHeight: '1.75rem' }],
      '2xl': ['1.5rem', { lineHeight: '2rem' }],
      '3xl': ['1.875rem', { lineHeight: '2.25rem' }]
    }
  },
  spacing: {
    container: '20px',
    section: '40px',
    component: '16px',
    element: '8px'
  },
  borderRadius: {
    sm: '2px',
    md: '4px',
    lg: '6px', // DNA Online standard
    xl: '8px'
  }
} as const;
```

### Component Theming
```tsx
// Button component with DNA Online styling
import { Button as ShadcnButton } from "@/components/ui/button"
import { cva, type VariantProps } from "class-variance-authority"

const buttonVariants = cva(
  "inline-flex items-center justify-center rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none",
  {
    variants: {
      variant: {
        default: "bg-primary-500 text-white hover:bg-primary-600",
        secondary: "bg-laboratory-100 text-laboratory-900 hover:bg-laboratory-200",
        outline: "border border-primary-200 bg-transparent hover:bg-primary-50",
        ghost: "hover:bg-laboratory-100 hover:text-laboratory-900",
        destructive: "bg-red-500 text-white hover:bg-red-600"
      },
      size: {
        default: "h-10 py-2 px-4",
        sm: "h-9 px-3 rounded-md",
        lg: "h-11 px-8 rounded-lg",
        icon: "h-10 w-10"
      }
    },
    defaultVariants: {
      variant: "default",
      size: "default"
    }
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {}

export const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, ...props }, ref) => {
    return (
      <ShadcnButton
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    )
  }
)
```

### Responsive Design System
```typescript
// Breakpoint configuration
export const breakpoints = {
  mobile: '(max-width: 767px)',
  tablet: '(min-width: 768px) and (max-width: 1023px)',
  desktop: '(min-width: 1024px)',
  wide: '(min-width: 1280px)'
} as const;

// Grid system
export const gridConfig = {
  mobile: {
    columns: 1,
    gap: '16px',
    padding: '16px'
  },
  tablet: {
    columns: 2,
    gap: '20px',
    padding: '24px'
  },
  desktop: {
    columns: 3,
    gap: '24px',
    padding: '32px'
  },
  wide: {
    columns: 4,
    gap: '24px',
    padding: '40px'
  }
} as const;
```

---

## Testing & Quality Assurance

### Testing Strategy
```typescript
// Test pyramid approach
interface TestingStrategy {
  unit: "70% - Individual functions/components";
  integration: "20% - Component interactions";
  e2e: "10% - Full user workflows";
  manual: "5% - Edge cases and usability";
}
```

### Automated Testing Setup
```typescript
// vitest.config.ts
import { defineConfig } from 'vitest/config'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  test: {
    environment: 'jsdom',
    setupFiles: ['./src/test/setup.ts'],
    globals: true
  },
  resolve: {
    alias: {
      '@': '/src'
    }
  }
})
```

### Component Testing Examples
```tsx
// ProductCard.test.tsx
import { render, screen, fireEvent } from '@testing-library/react'
import { ProductCard } from '@/components/ProductCard'
import { mockProduct } from '@/test/mocks'

describe('ProductCard', () => {
  test('renders product information correctly', () => {
    const onQuoteRequest = vi.fn()
    
    render(
      <ProductCard 
        product={mockProduct} 
        onQuoteRequest={onQuoteRequest}
      />
    )
    
    expect(screen.getByText(mockProduct.name)).toBeInTheDocument()
    expect(screen.getByText(mockProduct.size)).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /request quote/i })).toBeInTheDocument()
  })
  
  test('calls onQuoteRequest when button clicked', () => {
    const onQuoteRequest = vi.fn()
    
    render(
      <ProductCard 
        product={mockProduct} 
        onQuoteRequest={onQuoteRequest}
      />
    )
    
    fireEvent.click(screen.getByRole('button', { name: /request quote/i }))
    expect(onQuoteRequest).toHaveBeenCalledWith(mockProduct)
  })
})
```

### E2E Testing with Playwright
```typescript
// e2e/product-catalog.spec.ts
import { test, expect } from '@playwright/test'

test.describe('Product Catalog', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/test-page-with-catalog/')
  })
  
  test('loads products and displays them', async ({ page }) => {
    // Wait for products to load
    await page.waitForSelector('[data-testid="product-card"]')
    
    // Check that products are displayed
    const productCards = page.locator('[data-testid="product-card"]')
    await expect(productCards).toHaveCountGreaterThan(0)
    
    // Check product information is displayed
    const firstCard = productCards.first()
    await expect(firstCard.locator('[data-testid="product-name"]')).toBeVisible()
    await expect(firstCard.locator('[data-testid="product-size"]')).toBeVisible()
    await expect(firstCard.locator('[data-testid="quote-button"]')).toBeVisible()
  })
  
  test('filters products by size', async ({ page }) => {
    // Select size filter
    await page.selectOption('[data-testid="size-filter"]', '2oz')
    
    // Wait for filtered results
    await page.waitForTimeout(500)
    
    // Check that only 2oz products are shown
    const productSizes = page.locator('[data-testid="product-size"]')
    const sizeTexts = await productSizes.allTextContents()
    expect(sizeTexts.every(size => size.includes('2oz'))).toBe(true)
  })
  
  test('quote request workflow', async ({ page }) => {
    // Click quote button on first product
    await page.click('[data-testid="product-card"]:first-child [data-testid="quote-button"]')
    
    // Should redirect to quote page with product parameters
    await expect(page).toHaveURL(/\/quote-request\/\?product=.*&size=.*&id=.*/)
    
    // Check that Contact Form 7 form is displayed
    await expect(page.locator('.wpcf7-form')).toBeVisible()
    
    // Check that product information is pre-populated
    await expect(page.locator('input[name="product-name"]')).toHaveValue(/.+/)
    await expect(page.locator('input[name="product-size"]')).toHaveValue(/.+/)
  })
})
```

### PHP Unit Testing
```php
<?php
/**
 * Test database operations
 */
class ALS_Database_Test extends WP_UnitTestCase {
    
    private ALS_Database $database;
    
    public function setUp(): void {
        parent::setUp();
        $this->database = new ALS_Database();
        $this->database->create_tables();
    }
    
    public function test_get_products_returns_active_products(): void {
        // Insert test products
        $this->factory->post->create([
            'post_type' => 'als_product',
            'meta_input' => [
                '_lab_product_id' => 1,
                '_lab_size' => '2oz',
                '_lab_category' => 'vials'
            ]
        ]);
        
        $products = $this->database->get_products(['category' => 'vials']);
        
        $this->assertNotEmpty($products);
        $this->assertEquals('2oz', $products[0]['size']);
    }
    
    public function test_save_quote_stores_quote_data(): void {
        $quote_data = [
            'product_id' => 1,
            'product_name' => 'Test Vial',
            'size' => '2oz',
            'quantity' => 100,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com'
        ];
        
        $result = $this->database->save_quote($quote_data);
        
        $this->assertTrue($result);
        
        // Verify quote was saved
        global $wpdb;
        $quote = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}als_lab_catalogue_quotes WHERE customer_email = 'john@example.com'");
        $this->assertNotNull($quote);
        $this->assertEquals('Test Vial', $quote->product_name);
    }
}
```

---

## Security & Performance

### Security Implementation
```php
<?php
/**
 * Enhanced Security Class
 */
class ALS_Security {
    
    public function verify_nonce(string $action = 'als_nonce'): bool {
        $nonce = $_POST['nonce'] ?? $_GET['nonce'] ?? '';
        return wp_verify_nonce($nonce, $action);
    }
    
    public function sanitize_product_data(array $data): array {
        return [
            'name' => sanitize_text_field($data['name'] ?? ''),
            'description' => wp_kses_post($data['description'] ?? ''),
            'size' => sanitize_text_field($data['size'] ?? ''),
            'category' => sanitize_text_field($data['category'] ?? ''),
            'image_url' => esc_url_raw($data['image_url'] ?? ''),
            'is_active' => (bool) ($data['is_active'] ?? true)
        ];
    }
    
    public function check_rate_limit(string $action = 'general', int $limit = 60): bool {
        $ip = $this->get_client_ip();
        $key = "als_rate_limit_{$action}_" . md5($ip);
        
        $current = (int) get_transient($key);
        if ($current >= $limit) {
            return false;
        }
        
        set_transient($key, $current + 1, MINUTE_IN_SECONDS);
        return true;
    }
    
    public function validate_capabilities(string $capability = 'manage_options'): bool {
        return current_user_can($capability);
    }
    
    private function get_client_ip(): string {
        $headers = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = explode(',', $_SERVER[$header])[0];
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
```

### Performance Optimization
```typescript
// React Query for efficient data fetching
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'

export const useProducts = (filters: ProductFilters) => {
  return useQuery({
    queryKey: ['products', filters],
    queryFn: () => fetchProducts(filters),
    staleTime: 5 * 60 * 1000, // 5 minutes
    cacheTime: 10 * 60 * 1000, // 10 minutes
    refetchOnWindowFocus: false
  })
}

export const useProductVariations = (productId: number) => {
  return useQuery({
    queryKey: ['variations', productId],
    queryFn: () => fetchProductVariations(productId),
    enabled: !!productId,
    staleTime: 10 * 60 * 1000 // 10 minutes
  })
}

// Optimistic updates for better UX
export const useQuoteSubmission = () => {
  const queryClient = useQueryClient()
  
  return useMutation({
    mutationFn: submitQuote,
    onSuccess: () => {
      queryClient.invalidateQueries(['quotes'])
    },
    onError: (error) => {
      console.error('Quote submission failed:', error)
    }
  })
}
```

### Caching Strategy
```php
<?php
/**
 * WordPress Transient Caching
 */
class ALS_Cache {
    
    private const CACHE_PREFIX = 'als_lab_';
    private const DEFAULT_EXPIRY = 3600; // 1 hour
    
    public function get_products(array $filters = []): array {
        $cache_key = self::CACHE_PREFIX . 'products_' . md5(serialize($filters));
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $database = new ALS_Database();
        $products = $database->get_products($filters);
        
        set_transient($cache_key, $products, self::DEFAULT_EXPIRY);
        return $products;
    }
    
    public function invalidate_products_cache(): void {
        global $wpdb;
        
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_' . self::CACHE_PREFIX . 'products_%'
            )
        );
    }
    
    public function get_filter_options(): array {
        $cache_key = self::CACHE_PREFIX . 'filter_options';
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $database = new ALS_Database();
        $options = $database->get_filter_options();
        
        set_transient($cache_key, $options, self::DEFAULT_EXPIRY * 2); // 2 hours
        return $options;
    }
}
```

---

## Implementation Roadmap

### Phase 1: Foundation Setup (Week 1-2)
```markdown
## Foundation Tasks
- [ ] **Environment Setup** (4 hours)
  - Docker configuration with Vite dev server
  - Node.js dependencies and package.json
  - WordPress plugin structure creation
  - MCP server integration (Playwright, Docker, shadcn/ui)

- [ ] **Database Migration** (6 hours) 
  - Export current data to JSON backup
  - Create identical schema in new plugin
  - Import data with duplicate prevention
  - Verify data integrity and relationships

- [ ] **Build System Configuration** (4 hours)
  - Vite configuration for WordPress integration  
  - TypeScript setup with strict mode
  - Tailwind CSS configuration with DNA Online theme
  - ESLint and Prettier configuration

- [ ] **WordPress Plugin Foundation** (6 hours)
  - Main plugin class with dependency injection
  - Asset management class with Vite integration
  - Security utilities and AJAX handlers
  - Admin menu structure preparation
```

### Phase 2: Core Components (Week 3-4)
```markdown
## Component Development
- [ ] **shadcn/ui Setup** (4 hours)
  - Component library installation
  - Theme configuration with DNA Online colors
  - Base component customization (Button, Card, Dialog)
  - Icon system with Lucide React

- [ ] **Product Catalog Components** (12 hours)
  - ProductCard component with shadcn/ui Card
  - ProductGrid with responsive layout
  - FilterBar with shadcn/ui Select components
  - SearchInput with debounced filtering
  - LoadingStates with shadcn/ui Skeleton

- [ ] **State Management** (6 hours)
  - React Context for global state
  - Custom hooks for data fetching (React Query)
  - Filter state management
  - Error boundary implementation

- [ ] **TypeScript Definitions** (4 hours)
  - Product, Variation, Quote interfaces
  - API response types
  - Component prop types
  - WordPress integration types
```

### Phase 3: Integration & Features (Week 5-6)
```markdown
## Integration Tasks
- [ ] **WordPress Integration** (8 hours)
  - React app mounting in WordPress containers
  - AJAX endpoint integration with React Query
  - Nonce verification and security
  - Shortcode functionality

- [ ] **Contact Form 7 Integration** (6 hours)
  - Quote redirect functionality
  - URL parameter handling
  - Form pre-population
  - Database storage integration

- [ ] **Admin Interface** (10 hours)
  - Product management with shadcn/ui forms
  - Quote management dashboard
  - Settings configuration
  - Bulk operations interface

- [ ] **Mobile Optimization** (6 hours)
  - Responsive design testing
  - Touch interaction optimization
  - Mobile-specific UI adjustments
  - Performance optimization for mobile
```

### Phase 4: Testing & Polish (Week 7-8)
```markdown
## Quality Assurance
- [ ] **Automated Testing** (8 hours)
  - Component unit tests with Vitest
  - Integration tests for data flow
  - E2E tests with Playwright
  - PHP unit tests for backend

- [ ] **Performance Optimization** (6 hours)
  - Bundle size optimization
  - Image lazy loading implementation
  - Caching strategy implementation
  - Performance monitoring setup

- [ ] **Accessibility Compliance** (4 hours)
  - ARIA attributes implementation
  - Keyboard navigation testing
  - Screen reader compatibility
  - Color contrast verification

- [ ] **Browser Compatibility** (4 hours)
  - Cross-browser testing (Chrome, Firefox, Safari)
  - IE11 support (if required)
  - Mobile browser testing
  - Progressive enhancement
```

### Phase 5: Deployment & Documentation (Week 9-10)
```markdown
## Launch Preparation
- [ ] **Production Build** (4 hours)
  - Build process optimization
  - Asset minification and compression
  - Production environment configuration
  - Error handling and logging

- [ ] **Documentation Creation** (6 hours)
  - Developer documentation
  - User installation guide
  - API documentation
  - Troubleshooting guide

- [ ] **Migration Strategy** (4 hours)
  - Production migration plan
  - Data backup procedures
  - Rollback plan preparation
  - Launch checklist creation

- [ ] **Training & Handoff** (4 hours)
  - Admin user training
  - Developer handoff documentation
  - Support documentation
  - Maintenance procedures
```

---

## File Structure & Organization

### Complete Directory Structure
```
als-laboratory-catalogue/
├── als-laboratory-catalog.php          # Main plugin file
├── readme.txt                         # WordPress plugin readme
├── uninstall.php                      # Cleanup on uninstall
├── package.json                       # Node dependencies
├── vite.config.ts                     # Build configuration
├── tsconfig.json                      # TypeScript configuration
├── tailwind.config.js                 # Tailwind CSS configuration
├── .eslintrc.json                     # ESLint configuration
├── .gitignore                         # Git ignore rules
├── docker-compose.yml                 # Development environment
│
├── assets/                            # Frontend assets
│   ├── src/                          # Source files
│   │   ├── main.tsx                  # Entry point
│   │   ├── App.tsx                   # Root component
│   │   ├── components/               # React components
│   │   │   ├── ui/                   # shadcn/ui components
│   │   │   │   ├── button.tsx
│   │   │   │   ├── card.tsx
│   │   │   │   ├── dialog.tsx
│   │   │   │   ├── input.tsx
│   │   │   │   ├── select.tsx
│   │   │   │   └── ...
│   │   │   ├── ProductCard.tsx       # Product display component
│   │   │   ├── ProductGrid.tsx       # Product grid layout
│   │   │   ├── FilterBar.tsx         # Filter controls
│   │   │   ├── SearchInput.tsx       # Search functionality
│   │   │   ├── LoadingStates.tsx     # Loading indicators
│   │   │   └── ErrorBoundary.tsx     # Error handling
│   │   ├── hooks/                    # Custom React hooks
│   │   │   ├── useProducts.ts        # Product data fetching
│   │   │   ├── useFilters.ts         # Filter state management
│   │   │   ├── useDebounce.ts        # Debounced input
│   │   │   └── useLocalStorage.ts    # Local storage hook
│   │   ├── types/                    # TypeScript definitions
│   │   │   ├── Product.ts            # Product interfaces
│   │   │   ├── Variation.ts          # Variation interfaces
│   │   │   ├── Quote.ts              # Quote interfaces
│   │   │   ├── API.ts                # API response types
│   │   │   └── WordPress.ts          # WordPress types
│   │   ├── utils/                    # Utility functions
│   │   │   ├── api.ts                # API helper functions
│   │   │   ├── format.ts             # Formatting utilities
│   │   │   ├── validation.ts         # Validation helpers
│   │   │   └── constants.ts          # Application constants
│   │   ├── styles/                   # Global styles
│   │   │   ├── globals.css           # Global CSS
│   │   │   └── components.css        # Component-specific CSS
│   │   └── lib/                      # Library configurations
│   │       ├── utils.ts              # Utility functions
│   │       └── validations.ts        # Form validations
│   ├── dist/                         # Built assets (generated)
│   │   ├── assets/                   # JS/CSS files
│   │   └── manifest.json             # Asset manifest
│   └── legacy/                       # Legacy CSS (phase out)
│       ├── als-catalog.css
│       └── als-admin.css
│
├── includes/                          # PHP classes
│   ├── class-als-plugin.php          # Main plugin class
│   ├── class-als-database.php        # Database operations
│   ├── class-als-ajax-handler.php    # AJAX endpoints
│   ├── class-als-rest-api.php        # REST API endpoints
│   ├── class-als-admin-menu.php      # Admin interface
│   ├── class-als-cf7-integration.php # Contact Form 7 integration
│   ├── class-als-assets.php          # Asset management
│   ├── class-als-security.php        # Security utilities
│   ├── class-als-cache.php           # Caching layer
│   ├── class-als-settings.php        # Plugin settings
│   └── class-als-migrations.php      # Database migrations
│
├── data/                             # Data files
│   ├── als-widget-data.json         # Initial data import
│   └── sample-data.json             # Sample data for testing
│
├── languages/                        # Internationalization
│   ├── als-lab-catalog.pot          # Translation template
│   └── ...                          # Language files
│
├── tests/                           # Test files
│   ├── php/                         # PHP unit tests
│   │   ├── test-database.php
│   │   ├── test-ajax-handler.php
│   │   └── test-security.php
│   ├── e2e/                         # End-to-end tests
│   │   ├── product-catalog.spec.ts
│   │   ├── quote-workflow.spec.ts
│   │   └── admin-interface.spec.ts
│   ├── unit/                        # Frontend unit tests
│   │   ├── ProductCard.test.tsx
│   │   ├── FilterBar.test.tsx
│   │   └── hooks.test.ts
│   └── mocks/                       # Test mocks
│       ├── products.ts
│       └── wordpress.ts
│
└── docs/                            # Documentation
    ├── installation.md              # Installation guide
    ├── development.md               # Development setup
    ├── api.md                       # API documentation
    ├── components.md                # Component documentation
    └── troubleshooting.md           # Troubleshooting guide
```

---

## Component Specifications

### Core Component Architecture
```tsx
// ProductCatalog.tsx - Main container component
interface ProductCatalogProps {
  settings: CatalogSettings;
  initialFilters?: ProductFilters;
}

export const ProductCatalog: React.FC<ProductCatalogProps> = ({ 
  settings, 
  initialFilters 
}) => {
  const [filters, setFilters] = useState<ProductFilters>(initialFilters || {});
  const { data: products, isLoading, error } = useProducts(filters);
  const { data: filterOptions } = useFilterOptions();

  return (
    <div className="als-product-catalog">
      <FilterBar 
        filters={filters}
        onFiltersChange={setFilters}
        options={filterOptions}
      />
      
      <ProductGrid 
        products={products}
        isLoading={isLoading}
        error={error}
        onQuoteRequest={handleQuoteRequest}
      />
    </div>
  );
};
```

### FilterBar Component
```tsx
// FilterBar.tsx - Filter controls with shadcn/ui
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"

interface FilterBarProps {
  filters: ProductFilters;
  onFiltersChange: (filters: ProductFilters) => void;
  options: FilterOptions;
}

export const FilterBar: React.FC<FilterBarProps> = ({
  filters,
  onFiltersChange,
  options
}) => {
  const handleFilterChange = (key: keyof ProductFilters, value: string) => {
    onFiltersChange({ ...filters, [key]: value || undefined });
  };

  return (
    <div className="p-6 bg-laboratory-50 rounded-lg border border-laboratory-100 mb-6">
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Size
          </label>
          <Select 
            value={filters.size || ""} 
            onValueChange={(value) => handleFilterChange('size', value)}
          >
            <SelectTrigger>
              <SelectValue placeholder="All Sizes" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">All Sizes</SelectItem>
              {options.sizes?.map((size) => (
                <SelectItem key={size} value={size}>
                  {size}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Color
          </label>
          <Select 
            value={filters.color || ""} 
            onValueChange={(value) => handleFilterChange('color', value)}
          >
            <SelectTrigger>
              <SelectValue placeholder="All Colors" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">All Colors</SelectItem>
              {options.colors?.map((color) => (
                <SelectItem key={color.color_name} value={color.color_name}>
                  <div className="flex items-center gap-2">
                    <div 
                      className="w-3 h-3 rounded-full border border-gray-300" 
                      style={{ backgroundColor: color.color_hex }}
                    />
                    {color.color_name}
                  </div>
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Closure
          </label>
          <Select 
            value={filters.closure_type || ""} 
            onValueChange={(value) => handleFilterChange('closure_type', value)}
          >
            <SelectTrigger>
              <SelectValue placeholder="All Closures" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">All Closures</SelectItem>
              {options.closures?.map((closure) => (
                <SelectItem key={closure.name} value={closure.name}>
                  {closure.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Search
          </label>
          <Input
            type="text"
            placeholder="Search products..."
            value={filters.search || ""}
            onChange={(e) => handleFilterChange('search', e.target.value)}
            className="w-full"
          />
        </div>
      </div>

      <div className="flex justify-end">
        <Button 
          variant="outline" 
          onClick={() => onFiltersChange({})}
          size="sm"
        >
          Clear Filters
        </Button>
      </div>
    </div>
  );
};
```

### ProductGrid Component
```tsx
// ProductGrid.tsx - Responsive product grid
import { Card } from "@/components/ui/card"
import { Skeleton } from "@/components/ui/skeleton"
import { Alert, AlertDescription } from "@/components/ui/alert"

interface ProductGridProps {
  products?: Product[];
  isLoading: boolean;
  error?: Error | null;
  onQuoteRequest: (product: Product) => void;
}

export const ProductGrid: React.FC<ProductGridProps> = ({
  products,
  isLoading,
  error,
  onQuoteRequest
}) => {
  if (error) {
    return (
      <Alert variant="destructive">
        <AlertDescription>
          Failed to load products. Please try refreshing the page.
        </AlertDescription>
      </Alert>
    );
  }

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {Array.from({ length: 8 }).map((_, i) => (
          <Card key={i} className="p-4">
            <Skeleton className="aspect-[4/3] w-full mb-4" />
            <Skeleton className="h-6 w-3/4 mb-2" />
            <Skeleton className="h-4 w-1/2 mb-4" />
            <Skeleton className="h-10 w-full" />
          </Card>
        ))}
      </div>
    );
  }

  if (!products || products.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500 text-lg">No products found.</p>
        <p className="text-gray-400 text-sm mt-2">
          Try adjusting your filters or search criteria.
        </p>
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      {products.map((product) => (
        <ProductCard
          key={product.id}
          product={product}
          onQuoteRequest={onQuoteRequest}
        />
      ))}
    </div>
  );
};
```

---

## API Design & Documentation

### REST API Endpoints
```php
<?php
/**
 * REST API Handler for ALS Laboratory Catalogue
 */
class ALS_REST_API {
    
    public function register_routes(): void {
        register_rest_route('als/v1', '/products', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_products'],
            'permission_callback' => '__return_true',
            'args' => [
                'category' => ['type' => 'string'],
                'size' => ['type' => 'string'],
                'color' => ['type' => 'string'],
                'closure_type' => ['type' => 'string'],
                'search' => ['type' => 'string'],
                'limit' => ['type' => 'integer', 'default' => 12],
                'offset' => ['type' => 'integer', 'default' => 0]
            ]
        ]);
        
        register_rest_route('als/v1', '/products/(?P<id>\d+)/variations', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_product_variations'],
            'permission_callback' => '__return_true',
            'args' => [
                'id' => ['type' => 'integer', 'required' => true]
            ]
        ]);
        
        register_rest_route('als/v1', '/filter-options', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_filter_options'],
            'permission_callback' => '__return_true'
        ]);
        
        register_rest_route('als/v1', '/quotes', [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'create_quote'],
            'permission_callback' => [$this, 'check_quote_permissions'],
            'args' => [
                'product_id' => ['type' => 'integer', 'required' => true],
                'quantity' => ['type' => 'integer', 'required' => true],
                'customer_name' => ['type' => 'string', 'required' => true],
                'customer_email' => ['type' => 'string', 'required' => true],
                'customer_phone' => ['type' => 'string'],
                'company_name' => ['type' => 'string'],
                'message' => ['type' => 'string']
            ]
        ]);
    }
    
    public function get_products(WP_REST_Request $request): WP_REST_Response {
        $cache = new ALS_Cache();
        $filters = [
            'category' => $request->get_param('category'),
            'size' => $request->get_param('size'),
            'color' => $request->get_param('color'),
            'closure_type' => $request->get_param('closure_type'),
            'search' => $request->get_param('search'),
            'limit' => $request->get_param('limit'),
            'offset' => $request->get_param('offset')
        ];
        
        // Remove empty filters
        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');
        
        try {
            $products = $cache->get_products($filters);
            
            return new WP_REST_Response([
                'success' => true,
                'data' => [
                    'products' => $this->format_products($products),
                    'total' => count($products),
                    'filters' => $filters
                ]
            ], 200);
            
        } catch (Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Failed to fetch products'
            ], 500);
        }
    }
    
    private function format_products(array $products): array {
        return array_map(function($product) {
            return [
                'id' => (int) $product['product_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'size' => $product['size'],
                'default_color' => $product['default_color'],
                'default_color_hex' => $product['default_color_hex'],
                'category' => $product['category'],
                'tags' => $product['tags'] ? explode(',', $product['tags']) : [],
                'image_url' => $product['image_url'],
                'variation_count' => (int) $product['variation_count'],
                'available_colors' => $product['available_colors'] ? 
                    explode(',', $product['available_colors']) : [],
                'available_closures' => $product['available_closures'] ? 
                    explode(',', $product['available_closures']) : [],
                'slug' => sanitize_title($product['name'])
            ];
        }, $products);
    }
}
```

### TypeScript API Client
```typescript
// api.ts - Type-safe API client
interface APIResponse<T> {
  success: boolean;
  data?: T;
  message?: string;
}

interface ProductsResponse {
  products: Product[];
  total: number;
  filters: ProductFilters;
}

class ALSAPIClient {
  private baseUrl: string;
  private nonce: string;

  constructor(baseUrl: string, nonce: string) {
    this.baseUrl = baseUrl;
    this.nonce = nonce;
  }

  async getProducts(filters: ProductFilters = {}): Promise<Product[]> {
    const params = new URLSearchParams();
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value !== undefined && value !== '') {
        params.append(key, String(value));
      }
    });

    const response = await fetch(`${this.baseUrl}/products?${params}`, {
      headers: {
        'X-WP-Nonce': this.nonce
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: APIResponse<ProductsResponse> = await response.json();
    
    if (!data.success || !data.data) {
      throw new Error(data.message || 'Failed to fetch products');
    }

    return data.data.products;
  }

  async getProductVariations(productId: number): Promise<Variation[]> {
    const response = await fetch(`${this.baseUrl}/products/${productId}/variations`, {
      headers: {
        'X-WP-Nonce': this.nonce
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: APIResponse<{ variations: Variation[] }> = await response.json();
    
    if (!data.success || !data.data) {
      throw new Error(data.message || 'Failed to fetch variations');
    }

    return data.data.variations;
  }

  async getFilterOptions(): Promise<FilterOptions> {
    const response = await fetch(`${this.baseUrl}/filter-options`, {
      headers: {
        'X-WP-Nonce': this.nonce
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: APIResponse<FilterOptions> = await response.json();
    
    if (!data.success || !data.data) {
      throw new Error(data.message || 'Failed to fetch filter options');
    }

    return data.data;
  }

  async createQuote(quoteData: QuoteRequest): Promise<Quote> {
    const response = await fetch(`${this.baseUrl}/quotes`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': this.nonce
      },
      body: JSON.stringify(quoteData)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: APIResponse<Quote> = await response.json();
    
    if (!data.success || !data.data) {
      throw new Error(data.message || 'Failed to create quote');
    }

    return data.data;
  }
}

// Export singleton instance
export const apiClient = new ALSAPIClient(
  window.alsLabConfig?.restUrl || '/wp-json/als/v1',
  window.alsLabConfig?.nonce || ''
);
```

---

## Deployment & Maintenance

### Production Build Process
```json
{
  "scripts": {
    "build:production": "npm run type-check && npm run lint && npm run build",
    "build": "vite build",
    "type-check": "tsc --noEmit",
    "lint": "eslint . --ext ts,tsx --max-warnings 0",
    "test": "vitest run",
    "test:e2e": "playwright test",
    "optimize": "npm run build && npm run analyze-bundle",
    "analyze-bundle": "npx vite-bundle-analyzer dist/assets/",
    "zip": "wp-cli plugin create-zip als-laboratory-catalogue"
  }
}
```

### WordPress Plugin Directory Structure
```bash
# Build and package for WordPress.org
npm run build:production
wp plugin zip als-laboratory-catalogue

# Resulting plugin structure
als-laboratory-catalogue.zip
├── als-laboratory-catalog.php
├── readme.txt
├── uninstall.php
├── includes/
├── assets/dist/         # Only built assets
├── data/
├── languages/
└── license.txt
```

### Performance Monitoring
```php
<?php
/**
 * Performance monitoring and optimization
 */
class ALS_Performance {
    
    public function __construct() {
        add_action('wp_head', [$this, 'add_performance_monitoring']);
        add_action('wp_footer', [$this, 'add_performance_metrics']);
    }
    
    public function add_performance_monitoring(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        ?>
        <script>
        // Core Web Vitals monitoring
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (entry.name === 'first-contentful-paint') {
                    console.log('FCP:', entry.startTime);
                }
                if (entry.name === 'largest-contentful-paint') {
                    console.log('LCP:', entry.startTime);
                }
            }
        }).observe({entryTypes: ['paint', 'largest-contentful-paint']});
        
        // Cumulative Layout Shift
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (!entry.hadRecentInput) {
                    console.log('CLS:', entry.value);
                }
            }
        }).observe({entryTypes: ['layout-shift']});
        </script>
        <?php
    }
    
    public function add_performance_metrics(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $memory_usage = memory_get_peak_usage(true);
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        $memory_percent = round(($memory_usage / $memory_limit) * 100, 2);
        
        $query_count = get_num_queries();
        $load_time = timer_stop();
        
        echo "<!-- ALS Performance Metrics: Memory: {$memory_percent}% ({$memory_usage} bytes), Queries: {$query_count}, Load Time: {$load_time}s -->";
    }
}
```

### Maintenance Checklist
```markdown
## Weekly Maintenance
- [ ] Review error logs for PHP and JavaScript errors
- [ ] Check plugin performance metrics
- [ ] Monitor database query counts
- [ ] Update dependencies if security patches available
- [ ] Review Contact Form 7 spam reports

## Monthly Maintenance  
- [ ] Update WordPress, themes, and plugins
- [ ] Database optimization and cleanup
- [ ] Performance audit with Lighthouse
- [ ] Backup validation and restore testing
- [ ] Security scan with Wordfence or similar

## Quarterly Maintenance
- [ ] Full security audit and penetration testing
- [ ] Code review for potential optimizations
- [ ] User experience testing and feedback collection
- [ ] Analytics review for usage patterns
- [ ] Documentation updates and accuracy verification

## Annual Maintenance
- [ ] Technology stack evaluation (WordPress, React, dependencies)
- [ ] Database schema optimization review
- [ ] Performance baseline comparison
- [ ] Accessibility compliance audit
- [ ] Full disaster recovery testing
```

---

## Comprehensive Ultrathink Prompt

### Master Architectural Analysis Prompt

```
As an expert WordPress plugin architect with deep expertise in React, TypeScript, shadcn/ui, and laboratory e-commerce systems, analyze the ALS Laboratory Product Catalog rebuild architecture with ultrathink precision.

## Context & Constraints
You are rebuilding a WordPress plugin that manages laboratory equipment catalogs with the following EXACT requirements:
- Preserve 5-table database schema (products, variations, closures, currencies, quotes) without modifications
- Maintain Contact Form 7 integration for quote processing
- Use shadcn/ui + TypeScript + Tailwind CSS from day one
- Follow incremental todo.md workflow with measurable progress
- Integrate with DNA Online design system (#0866a8 primary, #e7f0e7 background)
- Support WordPress 5.0+ with PHP 7.4+ compatibility
- Docker development environment with Vite build system

## Architecture Analysis Framework

### 1. COMPONENT ARCHITECTURE DEEP DIVE
Analyze each component with surgical precision:

**ProductCard Component:**
- What are the exact shadcn/ui primitives needed (Card, Badge, Button)?
- How should responsive image handling work with WordPress media library?
- What TypeScript interfaces ensure type safety for product data?
- How do you prevent layout shift during image loading?
- What accessibility attributes are required for screen readers?
- How should hover states and animations enhance UX without performance cost?

**FilterBar Component:**
- How should hierarchical filtering work (size affects available colors/closures)?
- What debouncing strategy optimizes search without overwhelming the server?
- How do you persist filter state during navigation without localStorage conflicts?
- What validation prevents malicious filter injection?
- How should mobile filter UI collapse/expand for optimal UX?

**ProductGrid Component:**
- What CSS Grid patterns ensure consistent card heights across viewport sizes?
- How should skeleton loading states match final component dimensions?
- What virtualization strategy handles 1000+ products without memory leaks?
- How do you implement infinite scroll with proper loading states?
- What error boundaries prevent single product failures from crashing the grid?

### 2. STATE MANAGEMENT ARCHITECTURE
Dissect state flow with precision:

**Global State Structure:**
```typescript
interface AppState {
  products: {
    data: Product[];
    filters: ProductFilters;
    pagination: PaginationState;
    loading: LoadingState;
    error: ErrorState;
  };
  ui: {
    selectedProduct: Product | null;
    filterPanelOpen: boolean;
    sortOrder: SortOrder;
  };
  cache: {
    filterOptions: FilterOptions;
    lastFetch: number;
    invalidationKeys: string[];
  };
}
```

**Critical Questions:**
- How do you prevent unnecessary re-renders when filters change?
- What memoization strategies optimize expensive operations?
- How should error states propagate without cascading failures?
- What cache invalidation patterns prevent stale data?
- How do you handle optimistic updates for quote submissions?

### 3. WORDPRESS INTEGRATION COMPLEXITY
Analyze WordPress-specific challenges:

**Asset Loading Strategy:**
- How do you handle Vite dev server in WordPress admin vs frontend?
- What's the exact enqueue priority to avoid theme conflicts?
- How do you conditionally load React only on pages with the catalog?
- What dependency management prevents jQuery version conflicts?
- How should nonce rotation work with long-lived React apps?

**Security Boundaries:**
- Where exactly do you sanitize user input (frontend, AJAX, database)?
- How do you prevent SQL injection in dynamic filter queries?
- What rate limiting prevents AJAX endpoint abuse?
- How do you validate file uploads for product images?
- What capability checks protect admin functionality?

### 4. DATABASE OPTIMIZATION ANALYSIS
Examine query performance with precision:

**Current Schema Weaknesses:**
```sql
-- Potential N+1 queries
SELECT * FROM products WHERE category = 'vials';
-- Then for each product:
SELECT * FROM variations WHERE product_id = ?;
```

**Optimization Questions:**
- What JOIN strategies minimize database roundtrips?
- How do you index combinations (category + size + color) efficiently?
- What caching layers prevent redundant database queries?
- How do you handle database migrations without data loss?
- What query monitoring detects performance regressions?

### 5. BUILD SYSTEM & PERFORMANCE
Analyze build optimization strategies:

**Bundle Analysis:**
- What code splitting strategies minimize initial JavaScript load?
- How do you tree-shake unused shadcn/ui components?
- What image optimization reduces catalog loading times?
- How do you implement proper browser caching headers?
- What service worker strategies enable offline browsing?

**WordPress Specific Performance:**
- How do you prevent WordPress admin from loading React bundles?
- What transient caching optimizes filter option queries?
- How do you lazy load product images without layout shift?
- What database query optimization reduces page generation time?
- How do you implement proper CDN integration for assets?

### 6. TESTING STRATEGY DEEP DIVE
Analyze comprehensive testing approaches:

**Component Testing Challenges:**
- How do you mock WordPress global variables in Jest tests?
- What test data factories ensure consistent product variations?
- How do you test responsive behavior across device sizes?
- What accessibility testing catches keyboard navigation issues?
- How do you integration test AJAX endpoints with nonce verification?

**E2E Testing Complexity:**
- How do you seed WordPress database with test data?
- What Playwright strategies handle WordPress login workflows?
- How do you test Contact Form 7 integration end-to-end?
- What visual regression testing catches design system drift?
- How do you test multi-browser compatibility efficiently?

### 7. MIGRATION & DEPLOYMENT RISKS
Identify critical migration challenges:

**Data Migration Risks:**
- How do you migrate 500+ existing quotes without downtime?
- What rollback strategies protect against data corruption?
- How do you handle concurrent admin usage during migration?
- What validation ensures 100% data integrity post-migration?
- How do you migrate custom field data to new schema?

**WordPress Environment Variations:**
- How do you handle different PHP versions (7.4 vs 8.1+)?
- What compatibility issues exist with popular theme frameworks?
- How do you handle different WordPress multisite configurations?
- What plugin conflicts could break the catalog functionality?
- How do you ensure consistent behavior across hosting providers?

### 8. BUSINESS LOGIC COMPLEXITY
Analyze laboratory-specific requirements:

**Product Variation Complexity:**
- How do you handle color/closure combinations that don't exist?
- What pricing logic applies bulk discounts correctly?
- How do you manage product availability across regions?
- What inventory integration prevents overselling?
- How do you handle product discontinuation gracefully?

**Quote Processing Workflow:**
- How do you integrate quote data with existing CRM systems?
- What email template customization meets business needs?
- How do you handle quote expiration and follow-up workflows?
- What reporting capabilities track conversion rates?
- How do you ensure GDPR compliance for customer data?

## CRITICAL DECISION POINTS

For each architectural decision, provide:
1. **Implementation approach** with exact code patterns
2. **Risk assessment** with mitigation strategies  
3. **Performance impact** with benchmarking approach
4. **Maintenance burden** with long-term cost analysis
5. **Alternative approaches** with trade-off analysis

## SUCCESS METRICS DEFINITION

Define measurable success criteria:
- **Performance**: Specific Lighthouse scores, Core Web Vitals targets
- **Reliability**: Error rates, uptime requirements, recovery times
- **Usability**: Task completion rates, user satisfaction scores
- **Maintainability**: Code coverage percentages, documentation completeness
- **Business Impact**: Conversion rates, quote volume increases

## IMPLEMENTATION PRIORITY MATRIX

Rank all components by:
- **Business Value** (1-10): Revenue/UX impact
- **Technical Risk** (1-10): Implementation complexity  
- **Dependencies** (1-10): Blocking other work
- **Effort** (1-10): Development time required

Create implementation phases that maximize value delivery while minimizing risk.

## FAILURE MODE ANALYSIS

For each critical component, analyze:
- **What could fail?** Specific failure scenarios
- **How would it fail?** Error propagation patterns
- **What's the impact?** Business and user consequences  
- **How do we detect it?** Monitoring and alerting
- **How do we recover?** Automated and manual recovery procedures

Provide this analysis with the depth and precision of a senior architect planning a mission-critical system. Every recommendation should be actionable with specific implementation details.
```

---

## Final Architecture Summary

This comprehensive architecture document provides the complete blueprint for rebuilding the ALS Laboratory Product Catalog plugin with modern development practices while preserving all existing functionality and data structures.

### Key Innovations
1. **shadcn/ui Integration**: Professional component library from day one
2. **TypeScript Safety**: Compile-time error prevention and better DX
3. **Modern Build System**: Vite integration with WordPress compatibility
4. **Performance First**: Optimized bundle splitting and caching strategies
5. **Test-Driven Development**: Comprehensive testing at every level
6. **Accessibility Compliant**: WCAG 2.1 AA standards throughout
7. **Mobile Optimized**: Touch-first responsive design patterns

### Preservation Guarantees
- ✅ **Database Schema**: Exactly preserved, no structural changes
- ✅ **Contact Form 7**: Full integration maintained and enhanced
- ✅ **Admin Interface**: Complete CRUD operations with modern UI
- ✅ **Quote Workflow**: Seamless end-to-end quote processing
- ✅ **Multi-currency**: Geolocation and exchange rate support
- ✅ **Security**: WordPress standards with enhanced protection
- ✅ **Performance**: Caching and optimization improvements

### Implementation Ready
This architecture is immediately actionable with:
- Detailed component specifications
- Complete file structure organization  
- Build configuration examples
- Testing strategies and examples
- Deployment procedures and checklists
- Performance monitoring and optimization
- Maintenance procedures and schedules

### Success Metrics
- **Development Speed**: 75% faster component creation with shadcn/ui
- **User Experience**: 50% reduction in quote completion time
- **Performance**: Sub-2s page loads, 95+ Lighthouse scores
- **Maintainability**: 80% test coverage, comprehensive documentation
- **Business Impact**: 25+ increase in quote requests and conversions

This blueprint ensures a successful rebuild that modernizes the technology stack while preserving all existing functionality and business value.

---

*Generated by Claude Code - Your AI development partner*
*Last Updated: January 28, 2025*