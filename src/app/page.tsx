'use client';

import { useEffect, useState, useMemo } from 'react';
import { ProductGrid } from '../components/product-grid';
import { FilterBar } from '../components/filter-bar';
import { CardSkeleton } from '../components/card-skeleton';
import { Product, ProductOption, FilterOptions } from '../lib/types';

// Import fetchMockProducts
import { fetchMockProducts } from '../lib/mock-data';

// If you have a real API service, import it like this:
// import { fetchProductsFromApi } from '../lib/api-service';

export default function Home() {
  const [allProducts, setAllProducts] = useState<Product[]>([]);
  const [allOptions, setAllOptions] = useState<ProductOption[]>([]);
  const [filterOptions, setFilterOptions] = useState<FilterOptions | null>(null);
  const [loading, setLoading] = useState(true);

  // Filter states
  const [searchTerm, setSearchTerm] = useState<string>('');
  const [selectedProduct, setSelectedProduct] = useState<string>('');
  const [selectedSize, setSelectedSize] = useState<string>('');

  useEffect(() => {
    const loadProducts = async () => {
      setLoading(true);
      try {
        if (process.env.NEXT_PUBLIC_USE_MOCK_DATA === 'true') {
          console.log('Using mock data...');
          const data = await fetchMockProducts();
          setAllProducts(data.products);
          setAllOptions(data.options);
          setFilterOptions(data.filterOptions);
        } else {
          console.log('Using real API data (or empty for now)...');
          setAllProducts([]);
          setAllOptions([]);
          setFilterOptions({ sizes: []});
        }
      } catch (error) {
        console.error('Failed to fetch products:', error);
      } finally {
        setLoading(false);
      }
    };

    loadProducts();
  }, []);

  // Filtered products logic
  const filteredProducts = useMemo(() => {
    let currentProducts = allProducts;

    if (searchTerm) {
      currentProducts = currentProducts.filter(product =>
        product.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    if (selectedProduct) {
      currentProducts = currentProducts.filter(product => product.name === selectedProduct);
    }

    // Filter by size based on product options
    if (selectedSize) {
      const filteredProductIds = new Set<string>();
      allOptions.forEach(option => {
        if (option.size === selectedSize) {
          filteredProductIds.add(option.product_id);
        }
      });

      currentProducts = currentProducts.filter(product =>
        filteredProductIds.has(product.id)
      );
    }

    return currentProducts;
  }, [allProducts, allOptions, searchTerm, selectedProduct, selectedSize]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedProduct('');
    setSelectedSize('');
  };

  return (
    <main className="min-h-screen bg-gray-100 p-4">
      <h1 className="text-4xl font-bold text-dna-online-blue mb-6">Product Catalog</h1>
      <FilterBar
        searchTerm={searchTerm}
        onSearchChange={setSearchTerm}
        selectedProduct={selectedProduct}
        onProductChange={setSelectedProduct}
        selectedSize={selectedSize}
        onSizeChange={setSelectedSize}
        onClearFilters={handleClearFilters}
        filterOptions={filterOptions || { sizes: [] }}
        products={allProducts} // Pass allProducts here
      />
      {loading ? (
        <CardSkeleton />
      ) : (
        <ProductGrid products={filteredProducts} isLoading={loading} />
      )}
    </main>
  );
}
