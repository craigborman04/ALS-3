'use client';

import { useEffect, useState, useMemo } from 'react';
import { ProductGrid } from '../components/product-grid';
import { FilterBar } from '../components/filter-bar';
import { CardSkeleton } from '../components/card-skeleton';
import { Product, ProductOption, FilterOptions } from '../lib/types';

import { fetchMockProducts } from '../lib/mock-data';

export default function Home() {
  const [allProducts, setAllProducts] = useState<Product[]>([]);
  const [allOptions, setAllOptions] = useState<ProductOption[]>([]);
  const [filterOptions, setFilterOptions] = useState<FilterOptions | null>(null);
  const [loading, setLoading] = useState(true);

  const [searchTerm, setSearchTerm] = useState<string>('');
  const [selectedProduct, setSelectedProduct] = useState<string>('');
  const [selectedColor, setSelectedColor] = useState<string>('');
  const [availableColors, setAvailableColors] = useState<string[]>([]);

  useEffect(() => {
    const loadProducts = async () => {
      setLoading(true);
      try {
        console.log('Using mock data...');
        const data = await fetchMockProducts();
        setAllProducts(data.products);
        setAllOptions(data.options);
        setFilterOptions(data.filterOptions);
      } catch (error) {
        console.error('Failed to fetch products:', error);
      } finally {
        setLoading(false);
      }
    };

    loadProducts();
  }, []);

  useEffect(() => {
    if (selectedProduct) {
      const productId = allProducts.find(p => p.name === selectedProduct)?.id;
      if (productId) {
        const colors = allOptions
          .filter(option => option.product_id === productId && option.color)
          .map(option => option.color);
        setAvailableColors([...new Set(colors)]);
      }
    } else {
      setAvailableColors([]);
    }
    setSelectedColor('');
  }, [selectedProduct, allProducts, allOptions]);

  const filteredProducts = useMemo(() => {
    let currentProducts = allProducts;

    if (searchTerm) {
      currentProducts = currentProducts.filter(product =>
        product.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
    
    if (selectedProduct) {
      const productId = allProducts.find(p => p.name === selectedProduct)?.id;
      
      if (productId) {
        if (selectedColor) {
           const productOption = allOptions.find(option => option.product_id === productId && option.color === selectedColor);
           if(productOption) {
            const baseProduct = allProducts.find(p => p.id === productId);
            if(baseProduct) {
              currentProducts = [{
                ...baseProduct,
                color_name: productOption.color,
                color_hex: productOption.color_hex,
                size: productOption.size
              }];
            } else {
              currentProducts = [];
            }
           } else {
             currentProducts = [];
           }
        } else {
          // If a product is selected but no color, show the base product
          const baseProduct = allProducts.find(p => p.id === productId);
          currentProducts = baseProduct ? [baseProduct] : [];
        }
      } else {
        currentProducts = [];
      }
    } else if (selectedColor) {
      // This case should ideally not happen without a product selected, but as a fallback:
      const productIds = new Set(allOptions.filter(o => o.color === selectedColor).map(o => o.product_id));
      currentProducts = allProducts.filter(p => productIds.has(p.id));
    }


    return currentProducts;
  }, [allProducts, allOptions, searchTerm, selectedProduct, selectedColor]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedProduct('');
    setSelectedColor('');
    setAvailableColors([]);
  };

  return (
    <main className="min-h-screen bg-gray-100 p-4">
      <h1 className="text-4xl font-bold text-dna-online-blue mb-6">Product Catalog</h1>
      <FilterBar
        searchTerm={searchTerm}
        onSearchChange={setSearchTerm}
        selectedProduct={selectedProduct}
        onProductChange={setSelectedProduct}
        selectedColor={selectedColor}
        onColorChange={setSelectedColor}
        availableColors={availableColors}
        onClearFilters={handleClearFilters}
        products={allProducts}
      />
      {loading ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          {Array.from({ length: 8 }).map((_, i) => (
            <CardSkeleton key={i} />
          ))}
        </div>
      ) : (
        <ProductGrid products={filteredProducts} isLoading={loading} />
      )}
    </main>
  );
}