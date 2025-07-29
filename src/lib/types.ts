
export interface Product {
  id: string;
  name: string;
  size: string;
  imageUrl: string;
  variations: number;
  description: string;
  'data-ai-hint'?: string;
  color?: string;
  closure?: string;
}

export interface FilterOptions {
  sizes: string[];
  colors: string[];
  closures: string[];
  products: string[];
}
