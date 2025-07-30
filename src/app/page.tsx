'use client';

import { useEffect, useState, useMemo } from 'react';
import { ProductGrid } from '../components/product-grid';
import { FilterBar } from '../components/filter-bar';
import { CardSkeleton } from '../components/card-skeleton';
import { Product, ProductOption, FilterOptions } from '../lib/types';

// This function will extract the API URL from the iframe's query parameters
const getApiUrl = () => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search);
    return params.get('apiUrl') || '/wp-json/als-catalog/v1';
  }
  return '/wp-json/als-catalog/v1';
};


export default function Home() {
  const [allProducts, setAllProducts] = useState<Product[]>([]);
  const [allOptions, setAllOptions] = useState<ProductOption[]>([]);
  const [filterOptions, setFilterOptions] = useState<FilterOptions | null>(null);
  const [loading, setLoading] = useState(true);

  // Filter states
  const [searchTerm, setSearchTerm] = useState<string>('');
  const [selectedProduct, setSelectedProduct] = useState<string>('');
  const [selectedColor, setSelectedColor] = useState<string>('');
  const [selectedClosure, setSelectedClosure] = useState<string>('');

  useEffect(() => {
    const loadData = async () => {
      setLoading(true);
      const API_URL = getApiUrl();
      try {
        const [productsRes, filterOptionsRes] = await Promise.all([
          fetch(`${API_URL}products`),
          fetch(`${API_URL}filter-options`),
        ]);

        if (!productsRes.ok || !filterOptionsRes.ok) {
          throw new Error('Failed to fetch data from the API');
        }

        const productsData = await productsRes.json();
        const filterOptionsData = await filterOptionsRes.json();
        
        setAllProducts(productsData);
        setFilterOptions(filterOptionsData);

      } catch (error) {
        console.error('Failed to fetch data:', error);
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

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

    return currentProducts;
  }, [allProducts, searchTerm, selectedProduct]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedProduct('');
    setSelectedColor('');
    setSelectedClosure('');
  };

  const { availableColors, availableClosureTypes } = useMemo(() => {
    if (!selectedProduct) {
      return { availableColors: [], availableClosureTypes: [] };
    }
    const optionsForSelectedProduct = allOptions.filter(
      (option) => option.product_id === allProducts.find(p => p.name === selectedProduct)?.id
    );
    const colors = [...new Set(optionsForSelectedProduct.map(o => o.color).filter(Boolean))];
    const closures = [...new Set(optionsForSelectedProduct.map(o => o.closure_type).filter(Boolean))];
    return { availableColors: colors, availableClosureTypes: closures };
  }, [selectedProduct, allProducts, allOptions]);

  return (
    <main className="min-h-screen bg-gray-100 p-4">
      <h1 className="text-4xl font-bold text-dna-online-blue mb-6">Product Catalog</h1>
      <FilterBar
        searchTerm={searchTerm}
        onSearchChange={setSearchTerm}
        selectedProduct={selectedProduct}
        onProductChange={(product) => {
          setSelectedProduct(product);
          setSelectedColor('');
          setSelectedClosure('');
        }}
        selectedColor={selectedColor}
        onColorChange={setSelectedColor}
        availableColors={availableColors}
        selectedClosureType={selectedClosure}
        onClosureTypeChange={setSelectedClosure}
        availableClosureTypes={availableClosureTypes}
        onClearFilters={handleClearFilters}
        products={allProducts}
      />
      {loading ? (
        <CardSkeleton />
      ) : (
        <ProductGrid products={filteredProducts} isLoading={loading} />
      )}
    </main>
  );
}
