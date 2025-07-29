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
  const [selectedClosureType, setSelectedClosureType] = useState<string>('');
  
  const [availableColors, setAvailableColors] = useState<string[]>([]);
  const [availableClosureTypes, setAvailableClosureTypes] = useState<string[]>([]);

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
        const productOptions = allOptions.filter(option => option.product_id === productId);
        const colors = productOptions.map(option => option.color).filter(Boolean);
        const closureTypes = productOptions.map(option => option.closure_type).filter(Boolean);
        setAvailableColors([...new Set(colors)]);
        setAvailableClosureTypes([...new Set(closureTypes)]);
      }
    } else {
      setAvailableColors([]);
      setAvailableClosureTypes([]);
    }
    setSelectedColor('');
    setSelectedClosureType('');
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
        const baseProduct = allProducts.find(p => p.id === productId);
        if (!baseProduct) return [];

        let productOption;

        if (selectedColor && selectedClosureType) {
           productOption = allOptions.find(option => 
            option.product_id === productId && 
            option.color === selectedColor &&
            option.closure_type === selectedClosureType
          );
        } else if (selectedColor) {
           productOption = allOptions.find(option => 
            option.product_id === productId && 
            option.color === selectedColor
          );
        } else if (selectedClosureType) {
           productOption = allOptions.find(option => 
            option.product_id === productId && 
            option.closure_type === selectedClosureType
          );
        }

        if (productOption) {
          currentProducts = [{
            ...baseProduct,
            color_name: productOption.color,
            color_hex: productOption.color_hex,
            size: productOption.size,
            closure_type: productOption.closure_type,
          }];
        } else {
          // If a product is selected but no variant, show the base product
          currentProducts = [baseProduct];
        }

      } else {
        currentProducts = [];
      }
    }

    return currentProducts;
  }, [allProducts, allOptions, searchTerm, selectedProduct, selectedColor, selectedClosureType]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedProduct('');
    setSelectedColor('');
    setSelectedClosureType('');
    setAvailableColors([]);
    setAvailableClosureTypes([]);
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
        selectedClosureType={selectedClosureType}
        onClosureTypeChange={setSelectedClosureType}
        availableClosureTypes={availableClosureTypes}
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
