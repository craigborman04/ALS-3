
import fs from 'fs';
import path from 'path';
import Papa from 'papaparse';

const filePath = path.join(process.cwd(), 'data', 'wp_als_products.csv');
const fileContent = fs.readFileSync(filePath, 'utf8');

Papa.parse(fileContent, {
  header: true,
  complete: (results) => {
    console.log('Parsed data:', results.data);
  },
  error: (error: any) => {
    console.error('Error parsing CSV:', error.message);
  },
});
