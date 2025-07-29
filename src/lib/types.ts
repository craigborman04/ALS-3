export interface Product {
  id: string;
  name: string;
  color_name: string;
  color_hex: string;
  color_description: string;
  category: string;
  tags: string;
  is_active: string;
  sort_order: string;
  created_at: string;
  updated_at: string;
  slug: string;
  description: string;
  image_url: string;
  variations?: string; // Adding variations
  size?: string; // Adding size for representative display
  closure_type?: string;
}

export interface ProductOption {
  id: string;
  product_id: string;
  size: string;
  closure_type: string;
  color: string;
  color_hex: string;
  part_number: string;
  price_modifier: string;
  capacity: string;
  dimensions: string;
  weight: string;
  is_active: string;
  created_at: string;
  updated_at: string;
}

export interface FilterOptions {
  sizes: string[];
}

export interface ProcessedProductData {
  products: Product[];
  options: ProductOption[];
  filterOptions: FilterOptions;
}
