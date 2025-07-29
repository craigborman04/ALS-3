import { ProcessedProductData } from './types';
import processedData from './processed-products.json';

export const fetchMockProducts = (): Promise<ProcessedProductData> => {
  return new Promise((resolve) => {
    // Simulate network delay
    setTimeout(() => {
      resolve(processedData as ProcessedProductData);
    }, 500);
  });
};
