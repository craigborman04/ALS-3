import fs from 'fs';
import path from 'path';
import Papa from 'papaparse';
import { Product, ProductOption, FilterOptions, ProcessedProductData } from '../src/lib/types';

const productsFilePath = path.join(process.cwd(), 'data', 'wp_als_products.csv');
const productOptionsFilePath = path.join(process.cwd(), 'data', 'wp_als_product_options_simple.csv');
const outputFilePath = path.join(process.cwd(), 'src', 'lib', 'processed-products.json');

const readCsv = <T>(filePath: string): Promise<T[]> => {
  return new Promise((resolve, reject) => {
    const fileContent = fs.readFileSync(filePath, 'utf8');
    Papa.parse(fileContent, {
      header: true,
      skipEmptyLines: true,
      complete: (results) => {
        resolve(results.data as T[]);
      },
      error: (error: any) => {
        reject(error);
      },
    });
  });
};

const generateProductData = async () => {
  try {
    const products = await readCsv<Product>(productsFilePath);
    const productOptions = await readCsv<ProductOption>(productOptionsFilePath);

    const uniqueSizes = new Set<string>();
    const uniqueClosureTypes = new Set<string>();
    const uniqueColors = new Set<string>();

    productOptions.forEach(option => {
      if (option.size) uniqueSizes.add(option.size);
      if (option.closure_type) uniqueClosureTypes.add(option.closure_type);
      if (option.color) uniqueColors.add(option.color);
    });

    const productMap = new Map<string, Product>();
    products.forEach(p => productMap.set(p.id, p));

    const variationsCount: { [productId: string]: number } = {};
    const productRepresentativeDetails: { [productId: string]: { size?: string; closure?: string } } = {};

    productOptions.forEach(option => {
      variationsCount[option.product_id] = (variationsCount[option.product_id] || 0) + 1;

      if (!productRepresentativeDetails[option.product_id]) {
        productRepresentativeDetails[option.product_id] = {};
      }
      if (!productRepresentativeDetails[option.product_id].size && option.size) {
        productRepresentativeDetails[option.product_id].size = option.size;
      }
      if (!productRepresentativeDetails[option.product_id].closure && option.closure_type) {
        productRepresentativeDetails[option.product_id].closure = option.closure_type;
      }
    });

    const enrichedProducts: Product[] = products.map(product => ({
      ...product,
      variations: (variationsCount[product.id] || 0).toString(),
      size: productRepresentativeDetails[product.id]?.size || 'N/A',
      closure: productRepresentativeDetails[product.id]?.closure || 'N/A',
    }));

    const filterOptions: FilterOptions = {
      sizes: Array.from(uniqueSizes).sort(),
      closureTypes: Array.from(uniqueClosureTypes).sort(),
      colors: Array.from(uniqueColors).sort(),
    };

    const processedData: ProcessedProductData = {
      products: enrichedProducts,
      options: productOptions,
      filterOptions,
    };

    fs.writeFileSync(outputFilePath, JSON.stringify(processedData, null, 2), 'utf8');
    console.log(`Processed data saved to ${outputFilePath}`);

  } catch (error: any) {
    console.error('Error generating product data:', error.message);
  }
};

generateProductData();
